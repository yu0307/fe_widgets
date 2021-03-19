@extends('fe_widgets::widgetFrame')
@php
    $days=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
@endphp
@section('Widget_contents')
<div class="wg_calendarbox1 wg_calendarbasf wg_calendarcal wg_calendar m-t-30">
    <div class="wg_calendarcalt">{{date('M Y')}}</div>
    <div class="wg_calendardays">
        @foreach ($days as $day)
            <span>{{$day}}</span>
        @endforeach

        @foreach ($days as $day)
            @if (date('D',strtotime(date('1-M-Y')))==$day)
                @php
                    break;
                @endphp
            @endif
            <span class="wg_calendarb"><!--BLANK--></span>
        @endforeach
        
        @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')); $i++)
            @if ($i==date('j'))
                <span class="circle" data-title="Today!">
            @else
                <span>
            @endif
            {{$i}}</span>
        @endfor
    </div>
</div>
@overwrite