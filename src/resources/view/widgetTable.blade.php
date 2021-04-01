@extends('fe_widgets::widgetFrame')

@section('Widget_contents')
<table class="table table-striped text-center">
    @isset($config['headers'])
    <thead>
        @if(is_array($config['headers']))
            <tr>
                @foreach($config['headers'] as $header)
                    <th>{{$header}}</th>
                @endforeach
            </tr>
        @else
            {!!$config['headers']!!}
        @endif
    </thead>
    @endisset
    
    <tbody>
        @if(is_array($config['WidgetData']))
            @foreach($config['WidgetData'] as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{$cell}}</td>
                    @endforeach
                </tr>
            @endforeach
        @else
            {!!$config['WidgetData']!!}
        @endif
    </tbody>
</table>
@overwrite