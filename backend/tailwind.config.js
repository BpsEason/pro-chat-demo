/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue", // 🚀 關鍵：讓 Tailwind 找到你的 App.vue 樣式
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}