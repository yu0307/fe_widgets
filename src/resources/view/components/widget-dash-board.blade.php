@php
    app()->FeFrame->enqueueResource(asset('/feiron/felaraframe/plugins/SortableMaster/Sortable.min.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/felaraframe/plugins/select2/dist/js/select2.full.min.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/felaraframe/plugins/flip-master/jquery.flip.min.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/felaraframe/plugins/select2/dist/css/select2.min.css'),'headerstyles');

    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/js/dashboard.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/js/WidgetAjax.js'),'footerscripts');
    app()->FeFrame->enqueueResource(asset('/feiron/fe_widgets/css/dashboard.css'),'headerstyles');
@endphp
<div id="widget-container">
    <div class="container-fluid widgetArea" id="fe_widgetArea">
        <div class="list-group" id="fe_widgetCtrls">
            @yield('Widget_Area')
            {!!app()->WidgetManager->renderUserWidgets(Auth::user())!!}    
        </div>
        <x-fe-modal
            id="dashboardWidgetControl"
            header-bg="dark"
        >
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
        </x-fe-modal>
        <div id="new_widget_area" class="animated bd-9 c-gray fadeInUp fadeOutDown" style="z-index: 1010;">
            <div class="front text-center" id="widget_add">
                <div class="text-center m-5"><i class="fa fa-plus-circle fa-3x faa-float animated"></i></div>
                <h4 class="m-t-0"><strong>Add</strong> Widgets</h4>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>