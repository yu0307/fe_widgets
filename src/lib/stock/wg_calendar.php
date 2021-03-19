<?php

namespace feiron\fe_widgets\lib\stock;

use feiron\fe_widgets\lib\WidgetAbstract as Widget;

class wg_calendar extends Widget{

    /*
        $viewParameters: 
        Extends @parent:$viewParameters
        Widget specific vars: none
    */
    public function __construct($viewParameters){
        //Widget Defaults 
        $defaultParameters=[
            'WidgetName'=>'calendar',
            'Width'=>'3',
            'DataHeight'=>'auto',
            'HeaderIcon'=>"far fa-calendar-alt",
            'HeaderBackground'=> 'bg-transparent',
            'WidgetBackground'=> 'bg-transparent',
        ];
        parent::__construct(array_merge($defaultParameters, ($viewParameters ?? [])));
        $this->setView('fe_widgets::stock.wg_calendar');
        $this->enqueueHeader(asset('/feiron/fe_widgets/css/wg_calendar.css'));
    }

    public function dataFunction()
    {
        return $this->viewParameters['Widget_contents'];
    }
}
