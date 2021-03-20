@extends('fe_widgets::widgetFrame')
@section('Widget_contents')
<div class="wg_weather m-t-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 weatherIcon py-4 text-center">
                <i class="fas fa-cloud-sun fa-8x"></i>
            </div>
        </div>
        <div class="row info">
            <div class="col-sm-3 d-flex align-items-center justify-content-center">
                <h1 class="temperature">25&deg;</h1>
            </div>
            <div class="col-sm-6 d-flex justify-content-center align-content-center flex-column">
                <h2 class="description m-0 mb-1">Partly Cloudy</h2>
                <h3 class="city m-0">Tirana, Albania</h3>
            </div>
            <div class="col-sm-3 dates d-flex justify-content-center align-content-center flex-column text-center py-3">
                <h4 class="month m-0">{{date('F')}}</h4>
                <h5 class="day m-0">{{date('d')}}</h5>
            </div>
        </div>
    </div>
</div>
@overwrite