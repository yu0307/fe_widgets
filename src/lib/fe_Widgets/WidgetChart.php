<?php

namespace feiron\fe_widgets\lib\fe_Widgets;

use feiron\fe_widgets\lib\WidgetAbstract as Widget;

class WidgetChart extends Widget{

    private $chartData=[];
    protected $chartConfig=[];

    public function __construct($viewParameters)
    {
        $defaultParameters['BaseWidget'] = 'WidgetChart';
        $defaultParameters['Width'] = '5';
        $defaultParameters['HeaderIcon'] = 'fas fa-chart-line';
        $defaultParameters['WidgetData'] ='';
        parent::__construct(array_merge($defaultParameters, ($viewParameters ?? [])));
        
        $this->setView('fe_widgets::widgetChart');

        if (false === empty($this->viewParameters['WidgetData'])) {
            $this->chartData = (is_callable($this->viewParameters['WidgetData'])) ? $this->viewParameters['WidgetData']() : $this->viewParameters['WidgetData'];
        }

        $this->enqueueHeader(asset('/feiron/fe_widgets/plugins/chartjs/Chart.bundle.min.js'));
        $this->enqueueHeader(asset('/feiron/fe_widgets/plugins/chartjs/Chart.min.css'));
        $this->chartConfig=[
            'type'=>'line',
            'data'=>[]
        ];
        //additional settings for the chart widget can be set within child constructor by using $this->UpdateWidgetSettings()
        //settings can also be overwritten by $this->updateChartSettings($key,$values)
        //Example: $this->updateChartSettings('type','polarArea');
        //in according to Chart.js Setting option list
        //www.chartjs.org
    }

    public function chartSettings(){
        return $this->chartConfig;
    }

    public function updateChartSettings($key,$setting){
        $this->chartConfig[$key]= (is_array($this->chartConfig[$key])?array_merge($this->chartConfig[$key],$setting):$setting);
    }

    //Use/overload this method to render chart data along with the page
    public function setData($data){
        $this->chartData = (is_callable($data)) ? $data() : $data;
    }

    /* use/overload this method to defer the loading of chart data to ajax request. 
    You do not need to worry about wiring up the Url and controllers to take care the request. All back-end will be make available by default. 
    unless you wish to use customized route and controller. You can then overwrite the default Ajax parameter with $this->updateWidgetSetting() */
    
    public function getAjaxData($request){
        return $this->chartData;
    }

    public function dataFunction()
    {
        return [
                "datasets" => ($this->chartData)
                ];
    }

    public function getWidgetSettings():array{
        $chatSettings=$this->chartSettings();
        $chatSettings['data']=array_merge($chatSettings['data'],[
            'datasets'=>$this->chartData
        ]);
        return array_merge(parent::getWidgetSettings(), [
            "chartSetting"=> $chatSettings
        ]);
    }
}
