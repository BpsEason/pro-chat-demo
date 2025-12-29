#!/bin/sh

cd /var/www/html

# 1. 環境變數處理
if [ ! -f ".env" ]; then
    echo "📄 建立 .env 檔案..."
    cp .env.example .env
    php artisan key:generate --ansi
fi

# 2. 初始權限校準
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 3. 檢查 PHP 套件是否存在
if [ ! -f "vendor/autoload.php" ]; then
    echo "📦 vendor 缺失，開始強制修復安裝..."

    # 徹底清理舊殘留（包含全局快取）
    rm -rf vendor composer.lock /root/.composer/cache /tmp/composer-cache

    # 清除 Composer 全局快取（更徹底）
    composer clear-cache --no-interaction || true

    # 設定全局記憶體無限（避免 OOM）
    export COMPOSER_MEMORY_LIMIT=-1

    # 先強制使用官方 Packagist（最穩定）
    composer config -g --unset repos.packagist
    composer config -g repo.packagist composer https://packagist.org

    echo "🚀 開始安裝 PHP 套件（使用官方鏡像）..."

    # 第一次嘗試：官方鏡像 + prefer-dist + verbose
    if composer install \
        --no-interaction \
        --no-dev \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts \
        --verbose; then
        echo "✅ 第一次安裝成功！"
    else
        echo "⚠️ 第一次失敗，切換阿里雲鏡像重試..."

        # 切換阿里雲鏡像（中國或網路慢時超快）
        composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

        if composer install \
            --no-interaction \
            --no-dev \
            --prefer-dist \
            --optimize-autoloader \
            --no-scripts \
            --verbose; then
            echo "✅ 阿里雲鏡像安裝成功！"
        else
            echo "⚠️ 第二次也失敗，嘗試腾讯鏡像..."
            composer config -g repo.packagist composer https://mirrors.tencent.com/composer/

            if composer install \
                --no-interaction \
                --no-dev \
                --prefer-dist \
                --optimize-autoloader \
                --no-scripts \
                --verbose; then
                echo "✅ 腾讯鏡像安裝成功！"
            else
                echo "❌ 所有鏡像都失敗，最後嘗試 prefer-source（會比較慢）..."
                composer config -g repo.packagist composer https://packagist.org
                composer install \
                    --no-interaction \
                    --no-dev \
                    --prefer-source \
                    --optimize-autoloader \
                    --no-scripts \
                    --verbose || {
                    echo "💥 所有方式都失敗！請檢查網路或手動安裝。"
                    exit 1
                }
            fi
        fi
    fi

    # 安裝完成後補跑 scripts（必要）
    composer run-script post-autoload-dump

    echo "✅ Composer 安裝完全成功！"
else
    echo "✅ vendor 已存在，跳過安裝"
fi

# 4. 前端編譯邏輯（僅 app 模式）
if [ "$1" != "reverb" ]; then
    if [ ! -d "node_modules/tailwindcss" ] || [ ! -d "node_modules/@tailwindcss/postcss" ]; then
        echo "📦 [APP 模式] 偵測到 Tailwind v4 套件缺失..."
        npm cache clean --force
        rm -rf node_modules package-lock.json || true
        npm install --no-bin-links --legacy-peer-deps --no-audit
        npm install pinia laravel-echo pusher-js tailwindcss@^3.4 postcss@latest autoprefixer@latest @vitejs/plugin-vue --no-bin-links --save-dev
        cat <<EOF > postcss.config.js
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOF
    fi

    echo "🎨 [APP 模式] 正在執行 Vite 編譯..."
    rm -rf public/build
    if [ -f "node_modules/vite/bin/vite.js" ]; then
        node node_modules/vite/bin/vite.js build || echo "⚠️ Vite 編譯失敗"
    fi
else
    echo "📡 [REVERB 模式] 跳過前端編譯..."
fi

mkdir -p public/build
chown -R www-data:www-data public/build

# 5. 清理殘留與快取
rm -f storage/logs/octane.pid
php artisan view:clear
php artisan config:clear

# 6. 確保 Master 資料庫就緒
echo "⌛ 正在偵測 Master 端口與帳號權限 ($DB_HOST)..."

until php -r "exit(@fsockopen('$DB_HOST', 3306) ? 0 : 1);" > /dev/null 2>&1; do
    echo "🔄 Master 服務啟動中..."
    sleep 2
done

echo "🔑 正在驗證用戶 $DB_USERNAME 是否已就緒..."
until php -r "
try {
    new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
" > /dev/null 2>&1; do
    echo "🔄 等待用戶 $DB_USERNAME 帳號初始化..."
    sleep 2
done

echo "✅ 帳號驗證成功！資料庫已完全就緒。"

# 7. 啟動服務分流邏輯
if [ "$1" = "reverb" ]; then
    echo "📡 啟動 Laravel Reverb..."
    exec php artisan reverb:start --host=0.0.0.0 --port=8080 --debug
else
    # 🔥 正確判斷：只有設定了 IS_MIGRATE_LEADER=1 的容器才執行 migrate
    if [ "$IS_MIGRATE_LEADER" = "1" ]; then
        echo "🗄️ [APP Leader - $(hostname)] 執行資料庫遷移..."
        export DB_READ_HOST=$DB_HOST
        DB_USERNAME=root \
        DB_PASSWORD=root_password_2025 \
        php artisan migrate:fresh --seed --force
        unset DB_READ_HOST
        echo "✅ Leader 遷移完成"
    else
        echo "👥 [APP Worker - $(hostname)] 跳過資料庫遷移（由 Leader 負責）"
    fi
    echo "⚡ 啟動 Octane Swoole 引擎（$(hostname)）..."
    exec php artisan octane:start \
        --server=swoole \
        --host=0.0.0.0 \
        --port=8000 \
        --workers=auto \
        --task-workers=auto
fi