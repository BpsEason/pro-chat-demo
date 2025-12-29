<template>
  <div class="bg-gray-50 min-h-screen flex flex-col">
    <!-- 標頭 -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white shadow-lg">
      <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </div>
          <div>
            <h3 class="text-xl font-bold">即時客服後台</h3>
            <p class="text-white/80 text-sm flex items-center gap-2">
              <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
              上線中 • 平均回覆 30 秒
            </p>
          </div>
        </div>
        <div v-if="store.totalUnread > 0" class="bg-red-500 px-4 py-2 rounded-full text-sm font-bold animate-pulse">
          {{ store.totalUnread }} 條未讀訊息
        </div>
      </div>
    </div>

    <!-- 主內容區 -->
    <div class="flex-1 flex flex-col md:flex-row max-w-7xl mx-auto w-full shadow-2xl overflow-hidden my-6 rounded-2xl">
      <!-- 左側：會話列表 -->
      <div class="w-full md:w-96 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-5 bg-gray-50 border-b border-gray-200">
          <h4 class="font-semibold text-gray-800">活躍會話 ({{ store.conversations.length }})</h4>
        </div>

        <div class="flex-1 overflow-y-auto">
          <div v-for="conv in store.conversations" :key="conv.id"
               @click="handleSelect(conv.id)"
               :class="{'bg-indigo-50 border-l-4 border-indigo-600': store.activeId === conv.id}"
               class="p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition flex items-center gap-4">
            <!-- 訪客頭像 -->
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
              {{ conv.visitor_name.charAt(0).toUpperCase() }}
            </div>

            <div class="flex-1 min-w-0">
              <div class="font-medium text-gray-900 truncate">{{ conv.visitor_name }}</div>
              <div class="text-sm text-gray-500 truncate">{{ conv.last_message || '開始對話' }}</div>
            </div>

            <div class="text-right">
              <div class="text-xs text-gray-400 mb-1">{{ formatTime(conv.last_message_at) }}</div>
              <span v-if="conv.unread_count > 0" class="inline-block bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">
                {{ conv.unread_count }}
              </span>
            </div>
          </div>

          <div v-if="store.conversations.length === 0" class="p-16 text-center text-gray-400">
            <div class="text-5xl mb-4">💬</div>
            <p class="font-medium">目前沒有活躍會話</p>
            <p class="text-sm mt-2">等待客戶發起對話</p>
          </div>
        </div>
      </div>

      <!-- 右側：聊天視窗 -->
      <div class="flex-1 flex flex-col bg-gray-50">
        <div v-if="store.activeId" class="flex flex-col h-full">
          <!-- 聊天標頭 -->
          <div class="px-6 py-4 bg-white border-b border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
              {{ currentConversationName.charAt(0).toUpperCase() }}
            </div>
            <div>
              <h4 class="font-semibold text-gray-800">{{ currentConversationName }}</h4>
              <p class="text-xs text-gray-500">訪客 ID: {{ store.activeId }} • 線上</p>
            </div>
          </div>

          <!-- 訊息區域 -->
          <div class="flex-1 overflow-y-auto p-6 space-y-4">
            <div v-for="msg in store.currentMessages" :key="msg.id"
                 :class="msg.self ? 'flex justify-end' : 'flex justify-start'">
              <div :class="[
                'max-w-[70%] px-5 py-3 rounded-3xl text-sm shadow-md relative',
                msg.self
                  ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white'
                  : 'bg-white text-gray-800'
              ]">
                <p class="break-words">{{ msg.content }}</p>
                <div class="text-xs opacity-70 mt-2 text-right">{{ msg.time }}</div>
              </div>
            </div>
          </div>

          <!-- 輸入區域 -->
          <div class="p-5 bg-white border-t border-gray-200">
            <div class="flex gap-4">
              <input v-model="replyText"
                     @keyup.enter="sendReply"
                     :disabled="store.isSending"
                     class="flex-1 px-6 py-3.5 border border-gray-300 rounded-full focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition text-base"
                     placeholder="輸入回覆訊息..."
              />
              <button @click="sendReply"
                      :disabled="store.isSending || !replyText.trim()"
                      class="px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-full font-semibold disabled:opacity-50 transition shadow-lg hover:shadow-xl">
                {{ store.isSending ? '傳送中...' : '發送' }}
              </button>
            </div>
          </div>
        </div>

        <!-- 無選中會話提示 -->
        <div v-else class="flex-1 flex items-center justify-center text-gray-400">
          <div class="text-center">
            <div class="text-7xl mb-6">💬</div>
            <p class="text-xl font-medium">請選擇一個會話</p>
            <p class="text-base mt-2">開始為客戶提供專業支援</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { useAdminChatStore } from '../stores/adminChat';

const store = useAdminChatStore();
const replyText = ref('');

const currentConversationName = computed(() => {
    const conv = store.conversations.find(c => c.id === store.activeId);
    return conv ? conv.visitor_name : '';
});

onMounted(() => {
    store.fetchConversations();
    store.startPolling();

    if (window.Echo) {
        // 先移除所有舊的監聽，避免重複綁定
        window.Echo.leaveChannel('chat');

        window.Echo.channel('chat')
            .subscribed(() => {
                console.log('✅ 已成功訂閱 chat 頻道');
            })
            .listen('.MessageSent', (e) => {
                console.log('📩 收到廣播事件:', e);
                if (e.data.sender_type === 'visitor') {
                  store.handleIncomingMessage(e.data);
                }
            })
            .error((error) => {
                console.error('❌ WebSocket 監聽出錯:', error);
            });
    }
});

onUnmounted(() => {
    store.stopPolling();
});

const handleSelect = (id) => {
    store.selectConversation(id);
};

const sendReply = async () => {
    if (!replyText.value.trim()) return;
    const text = replyText.value;
    replyText.value = '';
    await store.reply(text);
};

const formatTime = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleTimeString('zh-TW', { hour: '2-digit', minute: '2-digit' });
};
</script>