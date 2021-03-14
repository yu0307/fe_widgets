<?php

namespace feiron\fe_widgets\lib\fe_Widgets;

use feiron\fe_widgets\lib\WidgetAbstract as Widget;

class WidgetChart extends Widget{

    private $ChartData=[];

    public function __construct($viewParameters)
    {
        $defaultParameters['BaseWidget'] = 'WidgetChart';
        $defaultParameters['Width'] = '5';
        $defaultParameters['HeaderIcon'] = 'fas fa-chart-line';
        $defaultParameters['chartType'] = 'line';
        $defaultParameters['WidgetData'] ='';
        parent::__construct(array_merge($defaultParameters, ($viewParameters ?? [])));
        
        $this->setView('fe_widgets::widgetChart');

        if (false === empty($this->viewParameters['WidgetData'])) {
            $this->ChartData = (is_callable($this->viewParameters['WidgetData'])) ? $this->viewParameters['WidgetData']() : $this->viewParameters['WidgetData'];
        }

        $this->enqueueHeader(asset('/feiron/fe_widgets/plugins/chartjs/Chart.bundle.min.js'));
        $this->enqueueHeader(asset('/feiron/fe_widgets/js/widgetChartGeneral.js'));
        $this->enqueueHeader(asset('/feiron/fe_widgets/plugins/chartjs/Chart.min.css'));
        //additional settings for the chart widget can be set by using $this->UpdateWidgetSettings()
        //in according to Chart.js Setting option list
        //www.chartjs.org
    }

    public function setData($data){
        $this->ChartData = (is_callable($data)) ? $data() : $data;
    }

    public function dataFunction()
    {
        return [
                "data" => ($this->ChartData)
                ];
    }

    public function getWidgetSettings():array{
        return array_merge(parent::getWidgetSettings(), [
            "chartSetting"=> [
                "data" => ($this->ChartData)
            ]
        ]);
    }

}
