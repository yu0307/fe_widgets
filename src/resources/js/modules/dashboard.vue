<template>
    <div id="widget-container" class="h-100">
        <div class="widgetArea h-100 position-relative" id="fe_widgetArea">
            <div class="list-group row" id="fe_widgetCtrls">
                <div id="initial-widgets">
                    <slot name="initialwidgets" @w-remove="removeWidgetPanel"/>
                </div>
                <component 
                    v-for="(w) in Array.from(widgets)" 
                    :is="renderType(w.setting.Type)"
                    :key="w.setting.ID" 
                    :width="w.setting.width"
                    :init-config="w.setting"
                    @w-remove="removeWidgetPanel"
                    >
                    <template v-for="(content,name,idx) in w.slots" v-slot:[name] :key="idx" >
                        <section v-html="content" />
                    </template>
                </component>
            </div>
            <slot name="widgetcontrols"/>
            <div id="new_widget_area" class="animate__animated bd-9 c-gray animate__fadeInUp animate__fadeOutDown" style="z-index: 1010;">
                <div class="front text-center" id="widget_add" @click="showNewInterface">
                    <div class="text-center m-2"><i class="fa fa-plus-circle fa-3x levitate animate__animated animate__infinite"></i></div>
                    <h4 class="mt-0 fw-normal ft-12rem"><strong>Add</strong> Widgets</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</template>

<script>
import Sortable from 'sortablejs';
import widgetframe from './widgetframe.vue';
import widgetTimer from './widgetTimer.js';
import {ref,reactive} from 'vue';
export default {
    name:'dashboard',
    components:{
        widgetframe
    },
    props:{
        loadedWidgets:{}
    },
    setup(){
        const newWidgetInterface = reactive({
            description:'',
            selectedWidget:null,
            modal:null
        });
        const widgets = ref([]);
        const roamingWidget = reactive({});
        return {
            newWidgetInterface,
            widgets,
            roamingWidget
        }
    },
    data(){
        return {
            widgetGlobalTimer:null,
            sortableCtr:null,
        }
    },
    methods:{
        addWidget(widgetConfig){
            this.widgets.push(widgetConfig);
        },
        removeWidgetPanel(tar) {
            if (!_.isNull(tar.widget) && tar.widget.usrKey.length > 0) {
                removeAjaxWidget(tar.widget.usrKey);
            }
            let idx = this.widgets.findIndex((elm)=>{
                return elm.setting.usrKey==tar.widget.usrKey;
            });
            if(idx>=0) this.widgets.splice(idx,1);
            this.$emit('WidgetLayoutChanged');
        },
        showNewInterface(){
            this.clearWidgetWin();
            this.newWidgetInterface.modal.show();
        },
        clearWidgetWin(){
            this.newWidgetInterface.description='';
            this.newWidgetInterface.selectedWidget=null;
            this.roamingWidget = {};
        },
        renderType(wtype){
            return this.$options.components.hasOwnProperty('wtype')?wtype:'widgetframe';
        }
    },
    mounted(){
        this.widgets=this.loadedWidgets??[];
        this.widgetGlobalTimer= new widgetTimer(15000);
        this.newWidgetInterface.modal = new bootstrap.Modal(this.$el.querySelector('#dashboardWidgetControl'));
        this.sortableCtr=Sortable.create(document.getElementById('fe_widgetCtrls'), {//.fe_widget
            animation: 150,
            sort: true,
            filter: ".feWidgetFixed,.panel-content",  // Selectors that do not lead to dragging (String or Function)
            draggable: ".fe_widget",  // Specifies which items inside the element should be draggable
            handle: ".my-handle, .panel-controls",  // Drag handle selector within list items
            easing: "cubic-bezier(0.075, 0.82, 0.165, 1)",
            dragClass: "sortable_drag",  // Class name for the dragging item
            ghostClass: 'sortGhosting',
            onSort: function (e) {
                document.getElementById('fe_widgetCtrls').dispatchEvent('WidgetLayoutChanged');
            }
        });
        document.getElementById('initial-widgets').querySelectorAll('widgetframes').forEach(function(elm){
            let widgetComponent={
                setting:{},
                slots:{}
            };
            widgetComponent.setting=JSON.parse(elm.attributes['config'].value);
            elm.querySelectorAll('slots').forEach((s)=>{
                widgetComponent.slots[s.attributes['slot'].value]=s.innerHTML;
            });
            this.widgets.push(widgetComponent);
        }.bind(this));
        document.getElementById('initial-widgets').remove();
    }
}
</script>

<style lang="scss">
    #widget-container{
        #fe_widgetCtrls{
            .fe_widget{
                .panel-header{
                    &:hover{
                        .control-btn .d-none{
                            display: inline !important;
                        }
                    }
                }
                &.max-widget{
                    position: absolute;
                    left: 0px;
                    right: 0px;
                    top: 0px;
                    bottom: 0px;
                    width: 100%;
                    z-index: 1000;
                    .panel{
                        display: flex;
                        flex-direction: column;
                        height: 100%;
                        .panel-content{
                            flex: 1 1 auto !important;
                        }
                    }
                }
            }
        }
    }
</style>