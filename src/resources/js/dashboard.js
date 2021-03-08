import dashboard from './modules/dashboard.vue';
window.dashboard= window.Vue.createApp({});
window.dashboard.component('dashboard', dashboard);
window.ready(()=>{
    window.dashboard.mount(document.getElementById("dashboard"));
});