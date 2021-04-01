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
    </dashboard>
</div>