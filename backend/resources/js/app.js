import '../css/app.css';
import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia';

// 1. 引入組件
import App from './components/App.vue';               // 訪客端
import AdminDashboard from './components/AdminDashboard.vue'; // 客服端

// 🚀 關鍵：保留 Breeze 需要的 Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// 2. 建立實例與註冊
const app = createApp({}); // 使用空物件作為根實例
const pinia = createPinia();

app.use(pinia);

// 3. 註冊全域組件
// 這樣你在 Blade 檔案裡只要寫標籤就能叫出對應功能
app.component('visitor-chat', App);
app.component('admin-dashboard', AdminDashboard);

// 4. 掛載到 id="app" 的容器
app.mount('#app');