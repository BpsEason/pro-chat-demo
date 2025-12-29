import { defineStore } from 'pinia';
import axios from 'axios';

export const useAdminChatStore = defineStore('adminChat', {
    state: () => ({
        conversations: [],    // 左側對話列表
        activeId: null,       // 當前選中的會話 ID
        currentMessages: [],  // 當前右側顯示的訊息紀錄
        isLoading: false,     // 訊息載入狀態
        isSending: false,     // 發送中狀態鎖
        pollingTimer: null    // 短輪詢計時器
    }),

    getters: {
        // 🚀 計算所有會話的未讀總數
        totalUnread: (state) => {
            return state.conversations.reduce((sum, conv) => sum + (conv.unread_count || 0), 0);
        },
        // 🚀 獲取當前選中的會話物件
        activeConversation: (state) => {
            return state.conversations.find(c => c.id === state.activeId) || null;
        }
    },

    actions: {
        /**
         * 🚀 獲取會話列表
         */
        async fetchConversations() {
            try {
                const res = await axios.get('/api/admin/conversations');
                const newConversations = res.data.data;

                // 優化：保持當前選中會話的未讀數為 0，避免輪詢時數字跳動
                this.conversations = newConversations.map(newConv => {
                    if (newConv.id === this.activeId) {
                        return { ...newConv, unread_count: 0 };
                    }
                    return newConv;
                });
            } catch (err) {
                console.error('獲取會話列表失敗:', err);
            }
        },

        /**
         * 🚀 選擇會話並載入訊息
         */
        async selectConversation(id) {
            // 切換會話時立即清空舊訊息，提升 UI 反饋感
            if (this.activeId !== id) {
                this.currentMessages = [];
            }

            this.activeId = id;
            this.isLoading = true;
            try {
                const res = await axios.get(`/api/admin/conversations/${id}/messages`);
                const { messages } = res.data.data;

                this.currentMessages = messages.map(msg => ({
                    id: msg.id,
                    content: msg.content,
                    sender_type: msg.sender_type,
                    time: new Date(msg.created_at).toLocaleTimeString('zh-TW', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }),
                    self: msg.sender_type === 'agent'
                }));

                // 本地立即清空未讀
                const conv = this.conversations.find(c => c.id === id);
                if (conv) conv.unread_count = 0;

            } catch (err) {
                console.error('載入訊息失敗:', err);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * 🚀 客服回覆訊息 (含樂觀更新)
         */
        async reply(text) {
            if (!this.activeId || !text.trim() || this.isSending) return;

            this.isSending = true;

            // 1. 樂觀更新：立刻顯示在畫面上
            const tempId = Date.now();
            const tempMsg = {
                id: tempId,
                content: text,
                sender_type: 'agent',
                time: new Date().toLocaleTimeString('zh-TW', { hour: '2-digit', minute: '2-digit' }),
                self: true,
                sending: true
            };

            this.currentMessages.push(tempMsg);

            try {
                const res = await axios.post(`/api/admin/conversations/${this.activeId}/reply`, {
                    message: text
                });

                if (res.data.success) {
                    const savedMsg = res.data.data;
                    // 2. 將臨時訊息替換為資料庫真實資料
                    const index = this.currentMessages.findIndex(m => m.id === tempId);
                    if (index !== -1) {
                        this.currentMessages[index] = {
                            ...tempMsg,
                            id: savedMsg.id,
                            sending: false
                        };
                    }

                    // 3. 更新左側列表摘要
                    const conv = this.conversations.find(c => c.id === this.activeId);
                    if (conv) {
                        conv.last_message = text;
                        conv.last_message_at = new Date().toISOString();
                    }
                }
            } catch (err) {
                // 發送失敗則移除該臨時訊息
                this.currentMessages = this.currentMessages.filter(m => m.id !== tempId);
                alert('發送回覆失敗，請檢查網路連線');
            } finally {
                this.isSending = false;
            }
        },

        /**
         * 🚀 核心：處理 WebSocket 廣播訊息
         * 對齊 MessageSent.php 的 payload.data 結構
         */
        handleIncomingMessage(payload) {
            // 由於 MessageSent 事件中我們將數據包裹在 data 屬性內
            const msg = payload.data || payload;

            // 防止重複推入 (如果是自己發送的，reply 方法已經推過一次了)
            const exists = this.currentMessages.some(m => m.id === msg.id);
            if (exists) return;

            // 1. 如果訊息屬於當前選中的對話
            if (this.activeId === msg.conversation_id) {
                this.currentMessages.push({
                    id: msg.id,
                    content: msg.content,
                    sender_type: msg.sender_type,
                    time: new Date(msg.created_at).toLocaleTimeString('zh-TW', { hour: '2-digit', minute: '2-digit' }),
                    self: msg.sender_type === 'agent'
                });
            }

            // 2. 更新左側列表狀態
            const conv = this.conversations.find(c => c.id === msg.conversation_id);
            if (conv) {
                conv.last_message = msg.content;
                conv.last_message_at = msg.created_at;

                // 如果不是當前對話，增加未讀數
                if (this.activeId !== msg.conversation_id) {
                    conv.unread_count = (conv.unread_count || 0) + 1;
                }
            } else {
                // 如果是全新訪客的第一則訊息，重新抓取列表
                this.fetchConversations();
            }
        },

        startPolling() {
            if (this.pollingTimer) return;
            this.fetchConversations();
            this.pollingTimer = setInterval(() => {
                this.fetchConversations();
            }, 5000);
        },

        stopPolling() {
            if (this.pollingTimer) {
                clearInterval(this.pollingTimer);
                this.pollingTimer = null;
            }
        }
    }
});