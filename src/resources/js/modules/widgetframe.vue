<template>
    <div :class="['col-md-'+width, 'fe_widget_'+widgetType, (maximize?'max-widget':'')]" class="col-sm-12 fe_widget" :id="id" :name="widgetName" :usrKey="usrKey">
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
            return _.isEmpty(this.usrSettings);
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
        }
    }
}
</script>