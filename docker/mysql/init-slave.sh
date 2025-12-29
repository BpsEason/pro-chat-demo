#!/bin/bash
# docker/mysql/init-slave.sh

echo "⏳ 等待 Master 服務完全就緒..."
until mysql -h mysql-master -u root -p"${MYSQL_ROOT_PASSWORD}" -e "SELECT 1;" > /dev/null 2>&1; do
    sleep 2
done

# 檢查是否已經在同步，防止重複執行
SLAVE_IS_RUNNING=$(mysql -u root -p"${MYSQL_ROOT_PASSWORD}" -e "SHOW SLAVE STATUS\G" | grep "Slave_IO_Running: Yes" || true)

if [ -z "$SLAVE_IS_RUNNING" ]; then
    echo "🔗 正在建立 GTID 自動同步..."
    mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<EOF
STOP SLAVE;
-- 使用 MASTER_AUTO_POSITION=1，MySQL 會自動對齊進度，不需要填寫 File 和 Pos
CHANGE MASTER TO
  MASTER_HOST='mysql-master',
  MASTER_USER='repl_user',
  MASTER_PASSWORD='repl_password',
  MASTER_AUTO_POSITION = 1;
START SLAVE;
EOF
    echo "✅ GTID 自動同步已啟動！"
else
    echo "✅ 同步已在運行中。"
fi