<template>
  <div class="fixed bottom-0 right-0 w-96 h-[620px] m-8 bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col border border-gray-100 z-50 transition-all duration-300 hover:shadow-3xl">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white p-6 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="relative">
          <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </div>
          <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 rounded-full border-2 border-white animate-pulse"></span>
        </div>
        <div>
          <h3 class="font-bold text-xl">線上客服團隊</h3>
          <p class="text-sm opacity-90 flex items-center gap-2 mt-1">
            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
            目前上線 • 平均回覆 30 秒
          </p>
        </div>
      </div>
      <button class="hover:bg-white/20 p-2 rounded-xl transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto bg-gradient-to-b from-gray-50 to-gray-100 p-6 space-y-5" ref="scrollContainer">
      <div class="text-center text-xs text-gray-500 mb-4">
        您已加入聊天 • {{ new Date().toLocaleDateString('zh-TW', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
      </div>

      <div v-for="(msg, index) in chatStore.messages" :key="index" :class="msg.self ? 'flex justify-end' : 'flex justify-start'">
        <div class="flex items-start gap-3 max-w-[80%] group">
          <!-- 客服頭像 -->
          <div v-if="!msg.self" class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 flex items-center justify-center text-white text-sm font-bold shadow-lg">
            客
          </div>

          <!-- 訊息氣泡 -->
          <div :class="[
            'px-5 py-4 rounded-3xl shadow-lg relative transition-all duration-200',
            msg.self
              ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-br-none'
              : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none'
          ]">
            <p class="text-sm leading-relaxed">{{ msg.message }}</p>
            <span class="text-xs opacity-70 mt-2 block text-right">
              {{ msg.time }}
            </span>
          </div>

          <!-- 使用者頭像 -->
          <div v-if="msg.self" class="w-9 h-9 rounded-full bg-gradient-to-br from-pink-500 to-rose-500 flex-shrink-0 flex items-center justify-center text-white text-sm font-bold shadow-lg">
            {{ chatStore.myName.slice(-2) }}
          </div>
        </div>
      </div>
    </div>

    <!-- Input Area -->
    <div class="p-5 bg-white border-t border-gray-200">
      <form @submit.prevent="handleSend" class="flex gap-3">
        <input
          v-model="text"
          type="text"
          placeholder="輸入訊息...（按 Enter 發送）"
          class="flex-1 px-5 py-4 bg-gray-100 border border-gray-300 rounded-2xl focus:outline-none focus:ring-4 focus:ring-purple-500/30 focus:border-purple-500 text-gray-800 transition-all duration-200"
          autocomplete="off"
          ref="inputRef"
        >
        <button
          type="submit"
          class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="!text.trim()"
        >
          發送
        </button>
      </form>
      <p class="text-xs text-gray-400 text-center mt-3">
        我們重視您的隱私，所有對話均加密傳輸
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, onUnmounted } from 'vue';
import { useChatStore } from '../stores/chat';

const chatStore = useChatStore();
const text = ref('');
const scrollContainer = ref(null);
const inputRef = ref(null);

const handleSend = async () => {
  const msgText = text.value.trim();
  if (!msgText) return;

  // 樂觀更新在 store 處理，這裡清空輸入框
  text.value = '';
  await chatStore.sendMessage(msgText);
  scrollToBottom();
  inputRef.value?.focus();
};

const scrollToBottom = async () => {
  await nextTick();
  if (scrollContainer.value) {
    scrollContainer.value.scrollTo({
      top: scrollContainer.value.scrollHeight,
      behavior: 'smooth' // 加入平滑滾動增加質感
    });
  }
};

onMounted(async () => {
  // 1. 初始化資料
  await chatStore.fetchMessages();
  scrollToBottom();
  inputRef.value?.focus();

  // 2. 啟動監聽
  if (window.Echo) {
    console.log('訪客端：準備監聽 chat 頻道');
    // ✅ 正確監聽 Reverb/Pusher 連線狀態 (安全檢查)
    const pusher = window.Echo.connector.pusher;
    if (pusher && pusher.connection) {
      if (pusher.connection.state === 'connected') {
        console.log('✅ Echo 已連線成功 (Reverb)');
      }
    }

    // ✅ 正確訂閱事件
    window.Echo.channel('chat')
      .subscribed(() => {
        console.log('✅ 已成功訂閱 chat 頻道');
      })
      .listen('.MessageSent', (e) => {
        console.log('🚀 收到廣播訊息:', e);
        if (e.data.sender_type === 'agent') {
            chatStore.handleIncomingMessage(e.data);
            scrollToBottom();
        }
      });
  }
});

onUnmounted(() => {
  // 離開頁面時停止監聽，節省資源
  if (window.Echo) {
    window.Echo.leave('chat');
  }
});
</script>

<style scoped>
/* 精緻捲軸 */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: rgba(147, 51, 234, 0.3);
  border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 51, 234, 0.5);
}
</style>