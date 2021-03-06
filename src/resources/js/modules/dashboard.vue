<template>
    <div id="widget-container" class="h-100">
        <div class="widgetArea h-100 position-relative" id="fe_widgetArea">
            <div class="row" id="fe_widgetCtrls" ref="dashArea">
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
            <div id="dashboardWidgetControl" class="modal fade" tabindex="-1" aria-hidden="true" ref="interfaceModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-dark">
                            <h6 class="modal-title">Available Widgets</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="fe_widget_list_area">
                                <div id="fe_widget_list">
                                    <div :class="newWidgetInterface.loadingWidget?'animate__fadeIn':'d-none'" class="text-center sm-col-12 m-t-10 animate__animated">
                                        <i class="fa fa-spinner fa-spin fa-3x fa-fw loading"></i>
                                        <div class="text-center ">Processing...</div>
                                    </div>
                                    <div :class="newWidgetInterface.loadingWidget?'d-none':'animate__fadeIn'" class="animate__animated" id="widget-list-info">
                                        <h5 class="py-2">
                                            <strong>Select</strong> a widget from the list below
                                        </h5>
                                        <select class="btn-block" name="site_widgets" id="site_widgets" style="width:100%" v-model="newWidgetInterface.selectedWidget" @change="loadWidgetDetails">
                                            <option :value="null">Select A Widget</option>
                                            <option v-for="(w, index) in widgetList" :key="index" :value="w.name" >{{w.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="fe_widget_desc" class="f-18 p-10">
                                    <div :class="newWidgetInterface.loadingDetail?'animate__fadeIn':'d-none'" class="text-center sm-col-12 mt-5 animate__animated">
                                        <i class= "fa fa-spinner fa-spin fa-3x fa-fw loading" ></i>
                                        <div class="text-center ">Loading Widget Details...</div>
                                    </div>
                                    <div :class="newWidgetInterface.loadingDetail?'d-none':'animate__fadeIn'" class="widgetDetails animate__animated">
                                        <div class="p-2">
                                            <div>
                                                {{roamingWidget.Description}}
                                            </div>
                                            <div v-if="this.roamingWidget.userSettingOutlet!=undefined && this.roamingWidget.userSettingOutlet.length>0">
                                                <hr class="m-2">
                                                <h6 class="my-2 alert alert-primary py-2">Widget Settings:</h6>
                                                <div class="panel">
                                                    <div v-for="(setting,index) in roamingWidget.userSettingOutlet" :key="index" :id="'wg_setting_'+id" class="form-group row">
                                                        <div class="col-sm-12 col-md-3 control-label align-items-center d-flex">
                                                            <h6>{{setting.key}}</h6>
                                                        </div>
                                                        <div class="col-md-9 col-sm-12" >
                                                            <settings :id="'usr-setting'+index" :config="setting" v-model="roamingWidget.userSettingOutlet[index].value" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer {{ $footerBg??'bg-gray-light' }}">
                            <button type="button" class="btn btn-primary" id="widget_add" @click="addWidgetToPanel">Add Widget</button>
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
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
import settings from './settings.vue';
import {ref,reactive,defineAsyncComponent} from 'vue';
export default {
    name:'dashboard',
    components:{
        widgetframe,
        settings
    },
    props:{
        loadedWidgets:{}
    },
    setup(){
        const newWidgetInterface = reactive({
            description:'',
            selectedWidget:null,
            modal:null,
            loadingWidget:false,
            loadingDetail:false
        });
        const widgets = ref([]);
        const widgetList = ref([]);
        return {
            newWidgetInterface,
            widgets,
            widgetList
        }
    },
    data(){
        return {
            widgetGlobalTimer:null,
            sortableCtr:null,
            roamingWidget:{}
        }
    },
    methods:{
        addWidget(widgetConfig){
            this.widgets.push(widgetConfig);
        },
        removeWidgetPanel(tar) {
            if (!_.isNull(tar.widget) && tar.widget.usrKey>=0) {
                let idx = this.widgets.findIndex((elm)=>{
                    return elm.setting.usr_key==tar.widget.usrKey;
                });
                document.getElementById('wg_'+tar.widget.usrKey).classList.add('pendingRemoval');
                if(idx>=0) this.widgets.splice(idx,1);
                if(document.getElementById('wg_init_'+tar.widget.usrKey)) document.getElementById('wg_init_'+tar.widget.usrKey).remove();
                this.$el.dispatchEvent(new CustomEvent('WidgetLayoutChanged',{detail:{elm:this.$el}}));
            }
        },
        addWidgetToPanel(){
            if(_.isNull(this.newWidgetInterface.selectedWidget) || _.isNull(this.roamingWidget)){
                window.frameUtil.Notify('No widget is selected from the list','error');
            }else{
                axios.post('/GetWidget/' +this.newWidgetInterface.selectedWidget, { 'userSetting': (this.roamingWidget.userSettingOutlet||[])})
                .then((resp)=>{
                    let widgetComponent={
                        setting:resp.data.settings,
                        slots:{}
                    };
                    (new DOMParser().parseFromString(resp.data.html,'text/html')).querySelectorAll('slots').forEach((s)=>{
                        widgetComponent.slots[s.attributes['slot'].value]=s.innerHTML;
                    });
                    if((resp.data.jsInit||'').trim().length>0){
                        let initBlock=document.createElement('script');
                        initBlock.type = 'text/javascript';
                        initBlock.id='wg_init_'+widgetComponent.setting.ID;
                        initBlock.innerHTML=`
                            function jsInit_${widgetComponent.setting.Type}_${widgetComponent.setting.ID}(dom,settings={}){
                                ${resp.data.jsInit}
                            }
                        `;
                        document.body.appendChild(initBlock);
                    }
                    this.addWidget(widgetComponent);
                    this.$el.dispatchEvent(new CustomEvent('wg_added', {detail:{elm:widgetComponent}}));
                    this.newWidgetInterface.modal.hide();
                })
                .catch((err)=>{
                    window.frameUtil.Notify(err);
                });
            }
        },
        loadWidgetDetails(elm){
            if(!_.isNull(elm.target) && !_.isEmpty(elm.target.value)){
                this.newWidgetInterface.loadingDetail=true;
                axios.get('/GetWidgetDetails/'+elm.target.value)
                .then((resp)=>{
                    if(!_.isEmpty(resp.data)){
                        this.roamingWidget = reactive(resp.data);
                    }
                })
                .catch((e)=>{
                    window.frameUtil.Notify(e);
                }).then(()=>{
                    this.newWidgetInterface.loadingDetail=false;
                });
            }else{
                this.roamingWidget.Description='<h3>Widget Info Unavailable...</h3>';
            }
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
        loadWidgetList() {
            this.widgetList=[];
            this.newWidgetInterface.loadingWidget=true;
            axios.get('/GetWidgetList')
            .then((resp)=>{
                if(!_.isEmpty(resp.data)){
                    Object.keys(resp.data||{}).forEach((widget)=>{
                        this.widgetList.push({...resp.data[widget],...{name:widget}});
                    });
                }
            })
            .catch((e)=>{
                window.frameUtil.Notify(e);
            }).then(()=>{
                this.newWidgetInterface.loadingWidget=false;
            });
        },
        renderType(wtype){
            return this.$options.components.hasOwnProperty('wtype')?wtype:'widgetframe';
        },
        updateLayout() {
            let layout = [];
            document.querySelectorAll('#fe_widgetCtrls .fe_widget:not(.pendingRemoval)').forEach((elm)=>{
                let attr = elm.getAttribute('usrkey');
                if (!_.isEmpty(attr) && attr.length > 0) {
                    layout.push(attr);
                }
            })
            axios.post('/updateWidgetLayout',{newLayout: layout})
            .catch((err)=>{
                window.frameUtil.Notify(err);
            });
        }
    },
    mounted(){
        this.widgets=this.loadedWidgets??[];
        this.widgetGlobalTimer= new widgetTimer(15000);
        this.loadWidgetList();
        this.$el.addEventListener('WidgetLayoutChanged',()=>{
            this.updateLayout();
        });
        this.newWidgetInterface.modal = new bootstrap.Modal(this.$refs['interfaceModal']);
        this.sortableCtr=Sortable.create(this.$refs['dashArea'], {//.fe_widget
            animation: 150,
            sort: true,
            filter: ".feWidgetFixed",  // Selectors that do not lead to dragging (String or Function)
            draggable: ".fe_widget",  // Specifies which items inside the element should be draggable
            handle: "div.panel-header, div.panel-controls",  // Drag handle selector within list items
            easing: "cubic-bezier(0.075, 0.82, 0.165, 1)",
            dragClass: "sortable_drag",  // Class name for the dragging item
            ghostClass: 'sortGhosting',
            onSort: (e)=> {
                this.$refs['dashArea'].dispatchEvent(new CustomEvent('WidgetLayoutChanged'));
                this.updateLayout();
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
            this.addWidget(widgetComponent);
        }.bind(this));
        document.getElementById('initial-widgets').remove();
    },
    computed:{
        hasSelected(){
            return !_.isNull(this.newWidgetInterface.selectedWidget);
        }
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
                    &.bg-transparent{
                        top: 0px;
                        & + .panel-content{
                            padding-top: 0px;
                            padding-bottom: 0px;
                            & .flip-card-back{
                                padding-top: 40px !important;
                            }
                        } 
                    } 
                }
                .wg_main_cnt{
                    overflow-y: auto;
                    overflow-x: hidden;
                    width: 100%;
                }
                &.max-widget{
                    position: absolute;
                    left: 0px;
                    right: 0px;
                    top: 0px;
                    bottom: 0px;
                    width: 100%;
                    z-index: 10001;
                    .panel{
                        display: flex;
                        flex-direction: column;
                        height: 100%;
                        .panel-content{
                            flex: 1 1 auto !important;
                        }
                    }
                }
                .panel.bg-transparent{
                    .flip-card-back{
                        border-radius: 10px;
                    }
                }
            }
        }
    }
</style>