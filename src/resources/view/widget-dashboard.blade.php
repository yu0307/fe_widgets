@php
    // app()->FeFrame->enqueueResource(asset('/feiron/felaraframe/plugins/SortableMaster/Sortable.min.js'),'footerscripts');
    //missing flip js library
    // app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/js/WidgetAjax.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/css/dashboard.css'),'headerstyles');
    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/js/dashboard.js'),'footerscripts');
@endphp
<div id="dashboard" class="h-100">
    <dashboard>
        <template v-slot:initialwidgets>
            @yield('Widget_Area')
            {!!app()->WidgetManager->renderUserWidgets(Auth::user())!!}
        </template>
    
        <template v-slot:widgetcontrols>
            <x-fe_theme::fe-modal id="dashboardWidgetControl" header-bg="dark">
                <x-slot name="header">
                    Available Widgets
                </x-slot>
    
                <x-slot name="footer">
                    <button type="button" class="btn btn-primary" id="widget_add">Add Widget</button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
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
        </template>
    </dashboard>
</div>