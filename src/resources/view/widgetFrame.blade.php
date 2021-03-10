<widgetframes
    config='@json($config)'
>
    @if (false!==($config['AjaxLoad']??false))
        @php
            unset($config['Ajax']['AjaxJS']);
        @endphp
    <script type="text/javascript">
        AjaxWidgetPool['{{$ID}}']=@json($config['Ajax'])
    </script>
    @endif

    @yield('Widget_InlineScript')

    <slots slot=widget_header>
        @if(isset($config['Widget_header']))
            {!! $config['Widget_header'] !!}
        @else
            @yield('Widget_header')
        @endif
    </slots>
    <slots slot=widget_contents>
        @if(!empty($config['Widget_contents']))
            {!! $config['Widget_contents'] !!}
        @else
            @yield('Widget_contents')
        @endif
    </slots>
    <slots slot=widget_footer>
        @if(isset($config['Widget_footer']))
            {!! $config['Widget_footer'] !!}
        @else
            @yield('Widget_footer')
        @endif
    </slots>
</widgetframes>

@push('JsBeforeReady')
    @if ((!empty($config['usrSettings'])===true) || (!empty($config['widgetConfig'])===true))
        DashBoardWidgetBank['{{'wg_'.$config['usr_key']}}']={
                        settings:@json($config['usrSettings']??[]),
                        widgetConfig:@json($config['widgetConfig']??[])
                    };
    @endif
    @yield('Widget_JsBeforeReady');
@endpush

@php
    foreach ($config['headerscripts'] as $script){
        app()->FeFrame->enqueueResource($script['file'],'headerscripts');
    }
    foreach ($config['headerstyles'] as $script){
        app()->FeFrame->enqueueResource($script['file'],'headerstyles');
    }
    foreach ($config['footerscripts'] as $script){
        app()->FeFrame->enqueueResource($script['file'],'footerscripts');
    }
    foreach ($config['footerstyles'] as $script){
        app()->FeFrame->enqueueResource($script['file'],'footerstyles');
    }
@endphp

@push('DocumentReady')
    @yield('Widget_DocumentReady')
@endpush