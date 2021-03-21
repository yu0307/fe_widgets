@extends('fe_widgets::widgetFrame')
@section('Widget_contents')
<div class="wg_weather m-t-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 weatherIcon pb-4 pt-5 text-center c-blue-dark">
                <div id="weather_icon">
                    <canvas id="weathericon" width="70" height="70"></canvas>
                </div>
            </div>
        </div>
        <div class="row info">
            <div class="col-sm-3 d-flex align-items-center justify-content-center">
                <h1 class="temperature"><span id="weather_temp"></span></h1>
            </div>
            <div class="col-sm-6 d-flex justify-content-center align-content-center flex-column">
                <h2 class="description m-0 mb-1" id="weather_status">Loading...</h2>
                <h3 class="city m-0" id="weather_location">Loading...</h3>
            </div>
            <div class="col-sm-3 dates d-flex justify-content-center align-content-center flex-column text-center py-3">
                <h4 class="month m-0">{{date('F')}}</h4>
                <h5 class="day m-0">{{date('d')}}</h5>
            </div>
        </div>
    </div>
</div>
@overwrite

@section('jsInit')
    @verbatim
    
    var icons = new Skycons({"color": "black"});
    icons.set(dom.querySelector('#weathericon'), 'cloudy');
    icons.play();
    function decodeWeather(code) {
        switch (true) {
            case (/^800$/.test(code))://clear
                return 'clear-day';
            case (/^611$/.test(code))://sleet
                return 'sleet';
            case (/^741$/.test(code))://fog
                return 'fog';
            case (/^2[0-9]{2}$/.test(code))://Thunderstorm
                return 'storm';
            case (/^3[0-9]{2}$/.test(code))://Drizzle
                return 'drizzle';
            case (/^5[0-9]{2}$/.test(code))://Rain
                return 'rain';
            case (/^6[0-9]{2}$/.test(code))://snow
                return 'snow';
            case (/^7[0-9]{2}$/.test(code))://Atmosphere
                return 'wind';
            case (/^80[0-9]{1}$/.test(code))://Clouds
                return 'cloudy';
            default:
                return '';
        }
        return '';
    }
    
    dom.addEventListener('AjaxUpdated',(resp)=>{
        if(resp.detail.data.weather){
            let rst = resp.detail.data;
            let weather = (rst.weather[0]||{main:'N/A'});
            let icon =decodeWeather(weather.id);
            dom.querySelector('#weather_location').innerText=rst.name;
            dom.querySelector('#weather_status').innerText=weather.main;
            dom.querySelector('#weather_temp').innerHTML=(Math.round(rst.main.temp)+"&deg;"+((rst.unit||'imperial')=='imperial'?'F':'C'))||'N/A';
            icons.set(dom.querySelector('#weathericon'), icon);
        }
    });
    @endverbatim
@overwrite