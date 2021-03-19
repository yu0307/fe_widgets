<template>
    <div :class="['col-md-'+width, 'fe_widget_'+widgetType, (maximize?'max-widget':'')]" class="col-sm-12 fe_widget" :id="'wg_'+id" :name="widgetName" :usrKey="usrKey">
        <div class="panel" :class="[widgetBackground]">
            <div v-show="!disableHeader" :class="[(showContents?'':'bg-dark'),(disableControls?'panel-controls':''),headerBackground,(hasUsrSettings?'':'HasSettingOutlet')]" class="panel-header">
                <h3>
                    <i :class="headerIcon"></i>
                    <slot name="widget_header"/>
                </h3>
                <div class="control-btn">
                    <a href="#" class="panel-reload d-none">
                        <i class="fas fa-redo-alt"></i>
                    </a>
                    <a @click="toggleMax" href="#" class="panel-maximize d-none">
                        <i class="fas" :class="maximize?'fa-compress-arrows-alt':'fa-expand-arrows-alt'"></i>
                    </a>
                    <a @click="toggleSlide" href="#" class="panel-toggle">
                        <i class="fas fa-angle-down"></i>
                    </a>
                    <a v-if="hasUsrSettings&&!showSettings" @click="toggleSettings" href="#" class="panel-setting">
                        <i class="fas fa-tools"></i>
                    </a>
                    <a @click="removeWidget" href="#" class="panel-close">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            </div>

            <div v-show="showContents" class="panel-content position-relative" ref="widgetContent">
                <div class="withScroll flip-card wg_main_cnt" :class="showSettings?'active':''" :data-height="dataHeight" :style="{maxHeight:dataHeight+'px'}" ref="w-contents">
                    <transition enter-active-class="animate__animated fadeIn" leave-active-class="animate__animated fadeOut" mode="out-in">
                        <keep-alive>
                            
                        </keep-alive>
                    </transition>
                    <div class="animate__animated d-flex content-loader align-items-center justify-content-center text-center" :class="!(loadingContent||ajaxSetting.loading)?'animate__fadeOut d-none':'animate__fadeIn'">
                        <i class= "fa p-0 mt-2 fa-spinner fa-spin fa-3x fa-fw loading" ></i>
                        <div class="text-center "><h4>Loading Widget Contents...</h4></div>
                    </div>
                    <div class="flip-card-inner animate__animated">
                        <transition name="flip-y" mode="out-in">
                            <div v-if="showSettings" class="flip-card-back" ref="w-settings">
                                <div class="row my-2 px-2">
                                    <div class="col-sm-12">
                                        <settings v-for="(configs, sidx) in usrSettings" :key="sidx" :id="'usr-setting'+sidx" :config="configs" v-model="usrSettings[sidx].value"/>
                                    </div>
                                </div>
                                <div class="row my-2 mt-3">
                                    <div class="col-sm-12">
                                        <div class="float-start btn btn-success saveUsrSetting" @click="updateSettings(false)">Update</div> 
                                        <div class="float-end btn btn-danger hideUsrSetting" @click="updateSettings(true)">Cancel</div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="flip-card-front">
                                <slot name="widget_contents"/>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
            
            <div v-show="!disableFooter" class="panel-footer p-t-0 p-b-0" :class="footerBackground">
                <slot name="widget_footer"/>
            </div>
        </div>
    </div>
</template>

<script>
import {ref, reactive} from 'vue';
import settings from './settings.vue';
export default {
    name:"widgetframe",
    components:{
        settings
    },
    props:{
        initConfig:{required:true}
    },
    emits: ['wRemove'],
    data(){
        return{
            showContents:true,
            maximize:false,
            ajaxTimer:null,
            showSettings:false,
            loadingContent:true
        };
    },
    computed:{
        hasUsrSettings(){
            return !_.isEmpty(this.usrSettings);
        }
    },
    setup(props){
        const width = ref(props.initConfig.Width||4);
        const widgetType = ref(_.isNull(props.initConfig.Type)?'widgetframe':props.initConfig.Type);
        const id = ref(props.initConfig.ID);
        const widgetName = ref(props.initConfig.WidgetName);
        const usrKey = ref(props.initConfig.usr_key);
        const widgetBackground = ref(props.initConfig.WidgetBackground||'bg-white');
        const disableHeader = ref(props.initConfig.DisableHeader||false);
        const disableControls = ref(props.initConfig.DisableControls||false);
        const headerBackground = ref(props.initConfig.HeaderBackground||'bg-primary');
        const usrSettings = reactive([].concat(props.initConfig.usrSettings));
        const headerIcon = ref(props.initConfig.HeaderIcon==false?'':(props.initConfig.HeaderIcon||'far fa-star'));
        const dataHeight = ref(props.initConfig.DataHeight||400);
        const disableFooter = ref(props.initConfig.DisableFooter||false);
        const footerBackground = ref(props.initConfig.FooterBackground||'bg-dark');
        const ajaxSetting = reactive({...props.initConfig.Ajax,...{key:usrKey,AjaxLoad:props.initConfig.AjaxLoad,loading:false }});
        return {
            width,
            widgetType,
            id,
            widgetName,
            usrKey,
            widgetBackground,
            disableHeader,
            disableControls,
            headerBackground,
            usrSettings,
            headerIcon,
            dataHeight,
            disableFooter,
            footerBackground,
            ajaxSetting
        };
    },
    mounted(){
        if(!_.isNull(this.ajaxSetting) && this.ajaxSetting.AjaxLoad===true){
            switch(this.ajaxSetting.AjaxInterval){
                case true://global interval
                    this.$parent.widgetGlobalTimer.addWidgetGlobalTimer(this.ajaxSetting);
                    break;
                case false://once only
                    setTimeout(() => {
                        this.$parent.widgetGlobalTimer.sendWidgetAjax(this.ajaxSetting);
                    }, 1500);
                    break;
                default://custom interval
                    this.ajaxTimer = setInterval(()=>{
                        this.$parent.widgetGlobalTimer.sendWidgetAjax(this.ajaxSetting);
                    }, (this.ajaxSetting.AjaxInterval||5000));
            }
        }
        this.attachResources();
    },
    methods:{
        removeWidget(tar){
            this.$emit('wRemove',{elm:tar,widget:this});
        },
        toggleSlide(){
            this.showContents=!this.showContents;
        },
        toggleMax(){
            this.maximize=!this.maximize;
        },
        toggleSettings(){
            this.showSettings=true;
        },
        updateSettings(skip=false){
            if(skip){
                this.showSettings=false;
            }else{
                axios.post('/updateUserWidgetSetting',{ target: this.usrKey, Settings: this.usrSettings })
                .then((resp)=>{
                    window.frameUtil.Notify('Widget Setting Updated.','info');
                    this.$el.dispatchEvent(new CustomEvent('wgUserSettingUpdated',{detail:{elm:this.$el}}));
                    this.showSettings=false;
                })
                .catch((err)=>{
                    window.frameUtil.Notify(err);
                })
            }
        },
        async attachResources(callback=null){
            let location = ['headerscripts','headerstyles','footerscripts','footerstyles'];
            for(let i=0; i<location.length;i++){
                let key =location[i];
                let resourceList = (this.initConfig[key]||[]);
                for(let j=0; j<resourceList.length;j++){
                    let resource = resourceList[j];
                    await new Promise((resolve,reject)=>{
                        let [startidx,lastidx]=[(resource.file.lastIndexOf('/')||0),resource.file.lastIndexOf('.')];
                        let [fileName,extension] = [resource.file.substr((startidx + 1),lastidx-startidx-1),resource.file.substr((lastidx + 1))];
                        let res = null;
                        if (extension == 'css') {
                            if (document.querySelectorAll('link[href$="' + resource.file + '"]').length <= 0) {
                                res=document.createElement('link');
                                res.rel="stylesheet";
                                res.type="text/css";
                                res.media="screen";
                                res.href=resource.file;
                            }
                        } else {
                            if ((resource.duplicate === true || document.querySelectorAll('script[src$="' + resource.file + '"]').length <= 0)) {
                                res=document.createElement('script');
                                res.src=resource.file;
                                res.type = 'text/javascript';
                            }
                        }
                        if(!_.isNull(res)){
                            const cp=()=>{
                                resolve(true);
                            };
                            res.onload=cp;
                            res.onreadystatechange = function() {
                                if (this.readyState == 'complete') {
                                    cp();
                                }
                            }
                            document.querySelector((key.includes('footer')?'body':'head')).appendChild(res);
                        }else{
                            resolve(true);
                        }
                    });
                }
            }
            if(!_.isEmpty(this.initConfig['initCall']) && typeof window['jsInit_'+this.initConfig['initCall']] =='function'){
                window['jsInit_'+this.initConfig['initCall']](this.$el,this.initConfig);
            }
            this.$el.dispatchEvent(new CustomEvent('widgetReady',{bubbles: true,detail:{elm:this.$el,w_config:this.initConfig}}));
            if(typeof callback=='function') callback();
            this.loadingContent=false;
        }
    }
}
</script>

<style lang="scss">
    .wg_main_cnt{
        .flip-card-back,.flip-card-front{
            transition-delay: 0s !important;
            transition: all 0s !important;
        }
        .flip-y-enter-active,
        .flip-y-leave-active {
            transition: transform 0.2s ease-in-out, opacity 0.2s ease-in !important;
            transform-style: preserve-3d;
            &.flip-card-back{
                transform: scale(-1, 1);
            }
        }

        .flip-y-enter-from,
        .flip-y-leave-to {
            &.flip-card-front{
                transform: rotateY(90deg);
                opacity: 0;
            }
            &.flip-card-back{
                transform: rotateY(-90deg);
                opacity: 0;
            }
        }
        .flip-y-enter-to,
        .flip-y-leave-from {
            &.flip-card-front{
                transform: rotateY(0deg);
                opacity: 1;
            }
            &.flip-card-back{
                transform: rotateY(0deg);
                opacity: 1;
            }
        }
        .content-loader{
                position: absolute;
                left: 0px;
                right: 0px;
                top: 0px;
                bottom: 0px;
                background: #ffffffa1;
        }
    }
</style>