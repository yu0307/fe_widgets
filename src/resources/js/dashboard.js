import dashboard from './modules/dashboard.vue';
window.dashboard= window.Vue.createApp({
    components:{
        dashboard
    }
});
window.ready(()=>{
    window.dashboard.mount(document.getElementById("dashboard"));
});