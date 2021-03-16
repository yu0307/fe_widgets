@extends('fe_widgets::widgetFrame')

@section('Widget_contents')

<canvas class="wg_Charts fe_widget_WidgetChart w-100" >

</canvas>

@overwrite

@section('jsInit')
    @verbatim
    let chart = new Chart(dom.querySelector('.wg_Charts').getContext('2d'),_.cloneDeep(settings.widgetConfig.chartSetting));
    dom.addEventListener('AjaxUpdated',(resp)=>{
        chart.data.datasets=resp.detail.data.data;
        chart.update();
    });
    @endverbatim
@endsection