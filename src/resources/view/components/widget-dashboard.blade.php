@php
    // app()->FeFrame->enqueueResource(asset('/feiron/felaraframe/plugins/SortableMaster/Sortable.min.js'),'footerscripts');
    //missing flip js library
    // app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/js/WidgetAjax.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/css/dashboard.css'),'headerstyles');
    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/js/dashboard.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/js/test.js'),'footerscripts');
@endphp
<div id="dashboard" class="h-100">
    <dashboard>
        <template v-slot:initialwidgets>
            @yield('Widget_Area')
        </template>
    
        <template v-slot:widgetcontrols>
            <x-fe_theme::fe-modal id="dashboardWidgetControl" header-bg="dark">
                <x-slot name="header">
                    Site Available Widgets
                </x-slot>
    
                <x-slot name="footer">
                    <button type="button" class="btn btn-primary" id="widget_add">Add Widget</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </x-slot>
    
                <div id="fe_widget_list_area">
                    <div id="fe_widget_list">
                        <div class="text-center sm-col-12 m-t-10">
                            <i class="fa fa-spinner fa-spin fa-3x fa-fw loading"></i>
                            <div class="text-center ">Loading Site Widgets...</div>
                        </div>
                    </div>
                    <div id="fe_widget_desc" class="f-18 p-10"></div>
                </div>
            </x-fe_theme::fe-modal>
    
            <div id="new_widget_area" class="animate__animated bd-9 c-gray animate__fadeInUp animate__fadeOutDown" style="z-index: 1010;">
                <div class="front text-center" id="widget_add">
                    <div class="text-center m-2"><i class="fa fa-plus-circle fa-3x levitate animate__animated animate__infinite"></i></div>
                    <h4 class="mt-0 fw-normal ft-12rem"><strong>Add</strong> Widgets</h4>
                </div>
            </div>
    
            <div class="clearfix"></div>
        </template>
    </dashboard>
</div>