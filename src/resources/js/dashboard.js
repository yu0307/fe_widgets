import dashboard from './modules/dashboard.vue';
window.dashboard= window.Vue.createApp({
    components:{
        dashboard
    }
});
window.components=[{name:'test',src:'/feiron/fe_widgets/js/test.js'}];
window.ready(()=>{
    window.dashboard.mount(document.getElementById("dashboard"));
});