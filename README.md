# 🚀 Laravel 12 高性能即時客服系統 Demo

這是一個基於 **Laravel 12** 與 **Reverb** 打造的高性能、低延遲即時客服系統。專為處理大量併發連線而優化，採用現代化的前後端分離架構，提供流暢的用戶溝通體驗。

## ✨ 系統亮點

* **極致即時性**：採用 Laravel Reverb 原生 WebSocket 伺服器，擺脫外部 Pusher 限制與延遲。
* **現代化介面**：使用 **Vue 3 (Composition API)** 與 **Tailwind CSS** 打造的兩欄式客服後台與懸浮式訪客聊天窗。
* **狀態管理**：利用 **Pinia** 統一管理會話狀態，確保跨組件數據同步。
* **高效穩定**：支援 Redis 隊列加速廣播任務，並提供短輪詢 (Polling) 作為 WebSocket 斷線時的自動降級備援。
* **容器化部署**：完整 Docker 環境配置，一鍵啟動全棧服務。

---

## 🛠 核心技術棧

* **Backend**: Laravel 12 (PHP 8.3+), Reverb (WebSocket)
* **Frontend**: Vue 3, Pinia, Tailwind CSS, Axios
* **Database**: MySQL 8.0, Redis (Optional for Queue)
* **Infrastructure**: Docker, Nginx, Vite

---

## 📸 功能預覽

### 1. 訪客端 (Visitor Chat)

* 自動生成唯一訪客 ID。
* 支援歷史訊息載入。
* 漸層美觀的懸浮聊天視窗，支援自動捲動與發送狀態顯示。

### 2. 客服端 (Admin Dashboard)

* **兩欄式固定佈局**：左側實時會話列表（含未讀計數、最後訊息摘要）。
* **權限保護**：整合 Laravel Breeze 認證系統。
* **智能廣播過濾**：自動排除發送者自身廣播，防止訊息重複顯示。

---

## 🚀 快速開始

### 1. 複製專案與安裝依賴

```bash
git clone https://github.com/your-repo/chat-demo.git
cd chat-demo

# 安裝 PHP 依賴
composer install

# 安裝前端依賴
npm install

```

### 2. 環境配置

```bash
cp .env.example .env
php artisan key:generate

```

請確保 `.env` 中的廣播設定如下：

```env
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=sync # 開發測試建議使用 sync，若生產環境建議使用 redis

REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

```

### 3. 啟動服務 (Docker)

```bash
docker-compose up -d

```

### 4. 初始化資料庫與編譯資產

```bash
php artisan migrate --seed
npm run dev

```

---

## 🔐 測試帳號 (Demo Access)

為了方便測試，系統已預填測試環境帳號：

* **客服登入網址**: `/login`
* **帳號**: `admin@demo.com`
* **密碼**: `password123`

---

## 🏗 系統架構圖

系統透過 Nginx 將不同路徑的請求精確分流：

* `/app` → 轉發至 **Reverb (8080)** 處理 WebSocket 握手。
* `/api`, `/login` → 轉發至 **PHP-FPM** 處理業務邏輯。

---

## 📝 開發備註

* **廣播過濾**：系統在前端監聽器中透過 `sender_type` 判斷排除發送者自身的廣播，以確保「樂觀更新 (Optimistic UI)」與「廣播同步」不衝突。
* **WebSocket 路徑**：請注意 `bootstrap.js` 中的 `wsPath` 配置需為空字串，以配合 Nginx 的路徑轉發規則。

---

## 📄 授權協議

本專案基於 [MIT License](https://www.google.com/search?q=LICENSE) 開源。

---
