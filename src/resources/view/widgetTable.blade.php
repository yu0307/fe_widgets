@extends('fe_widgets::widgetFrame')

@section('Widget_contents')
<table class="table table-striped">
    <thead>
        @isset($config['headers'])
            @if(is_array($config['headers']))
                <tr>
                    @foreach($config['headers'] as $config['header'])
                        <th>{{$config['header']}}</th>
                    @endforeach
                </tr>
            @else
                {!!$config['headers']!!}
            @endif
        @endisset
    </thead>
    <tbody>
        {!!$config['WidgetData']!!}
    </tbody>
</table>
@overwrite