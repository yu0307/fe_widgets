<template>
    <div :class="['col-md-'+width, 'fe_widget_'+widgetType, (maximize?'max-widget':'')]" class="col-sm-12 fe_widget" :id="'wg_'+id" :name="widgetName" :usrKey="usrKey">
        <div class="panel" :class="[widgetBackground]">
            <div v-show="!disableHeader" :class="[(disableControls?'panel-controls':''),headerBackground,(hasUsrSettings?'':'HasSettingOutlet')]" class="panel-header">
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
                    <a v-if="hasUsrSettings" href="#" class="panel-setting">
                        <i class="fas fa-tools"></i>
                    </a>
                    <a @click="removeWidget" href="#" class="panel-close">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            </div>

            <div v-show="showContents" class="panel-content" ref="widgetContent">
                <div class="withScroll wg_main_cnt" :data-height="dataHeight" :style="{maxHeight:dataHeight+'px'}">
                    <slot name="widget_contents"/>
                </div>
            </div>
            
            <div v-show="!disableFooter" class="panel-footer p-t-0 p-b-0" :class="footerBackground">
                <slot name="widget_footer"/>
            </div>
        </div>
    </div>
</template>

<script>
import {ref, reactive} from 'vue'
export default {
    name:"widgetframe",
    props:{
        initConfig:{require:true}
    },
    emits: ['wRemove'],
    data(){
        return{
            showContents:true,
            maximize:false,
            ajaxTimer:null
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
        const usrSettings = reactive(props.initConfig.usrSettings||{});
        const headerIcon = ref(props.initConfig.HeaderIcon==false?'':(props.initConfig.HeaderIcon||'far fa-star'));
        const dataHeight = ref(props.initConfig.DataHeight||400);
        const disableFooter = ref(props.initConfig.DisableFooter||false);
        const footerBackground = ref(props.initConfig.FooterBackground||'bg-dark');
        const ajaxSetting = reactive({...props.initConfig.Ajax,...{key:usrKey,AjaxLoad:props.initConfig.AjaxLoad }});
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
        async attachResources(callback=null){
            let location = ['headerscripts','headerstyles','footerscripts','footerstyles'];
            for(let i=0; i<location.length;i++){
                let key =location[i];
                let resourceList = (this.initConfig[key]||[]);
                for(let j=0; j<resourceList.length;j++){
                    let resource = resourceList[j];
                    await new Promise((resolve,reject)=>{
                        let extension = resource.file.substr((resource.file.lastIndexOf('.') + 1));
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
            if(!_.isEmpty(this.initConfig['initCall']) && typeof window[this.initConfig['initCall']] =='function'){
                window[this.initConfig['initCall']](this.$el,this.initConfig);
            }
            this.$el.dispatchEvent(new CustomEvent('widgetReady',{detail:{elm:this.$el,w_config:this.initConfig}}));
            if(typeof callback=='function') callback();
        }
    }
}
</script>