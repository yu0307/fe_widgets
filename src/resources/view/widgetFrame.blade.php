@hasSection ('jsInit')

    @push('footerscripts')
    <script type="text/javascript" id="wg_init_{{$config['usr_key']}}">
        function jsInit_{{$config['Type'].'_'.$config['usr_key']}}(dom,settings={}){
            @yield('jsInit')
        }
    </script>
    @endpush

@endif
@if ($config['asView']===false)
    @section('jsInit')
    @overwrite
@endif
<widgetframes
    config='@json($config)'
>
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
