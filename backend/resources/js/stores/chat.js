import { defineStore } from 'pinia';
import axios from 'axios';

export const useChatStore = defineStore('chat', {
    state: () => {
        // 從 LocalStorage 獲取或生成唯一的 visitor_id
        let vId = localStorage.getItem('chat_visitor_id');
        if (!vId) {
            vId = 'v_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('chat_visitor_id', vId);
        }

        let savedName = localStorage.getItem('chat_user_name');
        if (!savedName) {
            savedName = '訪客' + vId.substr(-4);
            localStorage.setItem('chat_user_name', savedName);
        }

        return {
            visitorId: vId,
            conversation: null,
            messages: [],
            myName: savedName,
            isLoaded: false
        };
    },

    actions: {
        /**
         * 🚀 獲取歷史紀錄 (同時具備初始化對話的功能)
         * 對應後端：ChatController@getMessages
         */
        async fetchMessages() {
            try {
                const response = await axios.get('/api/messages', {
                    params: {
                        visitor_id: this.visitorId,
                        username: this.myName
                    }
                });

                // 後端回傳格式：{ data: { conversation: {...}, messages: [...] } }
                const { messages, conversation } = response.data.data;

                this.conversation = conversation;
                this.messages = (messages || []).map(msg => this.formatMessage(msg));
                this.isLoaded = true;
            } catch (err) {
                console.error('載入對話失敗:', err);
            }
        },

        /**
         * 🚀 發送訊息
         * 對應後端：ChatController@send
         */
        async sendMessage(text) {
            if (!text.trim()) return;

            // 🚀 關鍵修正：如果目前沒有對話資訊，先抓取（初始化）一次
            if (!this.conversation) {
                console.log('對話尚未初始化，正在自動建立...');
                await this.fetchMessages();
            }

            const tempId = Date.now();
            this.messages.push({
                id: tempId,
                username: this.myName,
                message: text,
                time: new Date().toLocaleTimeString('zh-TW', { hour: '2-digit', minute: '2-digit' }),
                self: true,
                sending: true
            });

            try {
                const response = await axios.post('/api/messages', {
                    visitor_id: this.visitorId,
                    message: text
                });

                const dbMessage = response.data.data;
                const index = this.messages.findIndex(m => m.id === tempId);

                if (index !== -1) {
                    this.messages[index].id = dbMessage.id;
                    this.messages[index].message = dbMessage.content;
                    this.messages[index].sending = false;
                }
            } catch (err) {
                // ... 錯誤處理邏輯
                console.error('發送失敗:', err.response?.data);
                this.messages = this.messages.filter(m => m.id !== tempId);
            }
        },

        /**
         * 🚀 處理廣播
         */
        handleIncomingMessage(payload) {
            const msgData = payload.data || payload;

            // 安全過濾：確保對話 ID 匹配
            if (!this.conversation || String(msgData.conversation_id) !== String(this.conversation.id)) return;

            // 避免重複推入
            if (this.messages.some(m => m.id === msgData.id)) return;

            this.messages.push(this.formatMessage(msgData));
        },

        /**
         * 🚀 格式化輔助方法 (適配後端 Model)
         */
        formatMessage(msg) {
            return {
                id: msg.id,
                username: msg.sender_type === 'visitor' ? this.myName : '客服團隊',
                message: msg.content, // 對齊後端 content
                time: new Date(msg.created_at).toLocaleTimeString('zh-TW', {
                    hour: '2-digit',
                    minute: '2-digit'
                }),
                self: msg.sender_type === 'visitor'
            };
        }
    }
});