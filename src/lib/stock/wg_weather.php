<?php

namespace feiron\fe_widgets\lib\stock;

use feiron\fe_widgets\lib\WidgetAbstract as Widget;

class wg_weather extends Widget{

    /*
        $viewParameters: 
        Extends @parent:$viewParameters
        Widget specific vars: none
    */
    public function __construct($viewParameters){
        //Widget Defaults 
        $defaultParameters=[
            'WidgetName'=>'weather',
            'Width'=>'4',
            'DataHeight'=>'auto',
            'HeaderIcon'=>"fas fa-cloud-sun"
        ];
        parent::__construct(array_merge($defaultParameters, ($viewParameters ?? [])));
        $this->setView('fe_widgets::stock.wg_weather');
        $this->enqueueHeader(asset('/feiron/fe_widgets/css/wg_weather.css'));
    }

    public function dataFunction()
    {
        return $this->viewParameters['Widget_contents'];
    }
}
