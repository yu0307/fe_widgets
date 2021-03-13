<?php

namespace feiron\fe_widgets\lib\stock;

use feiron\fe_widgets\lib\WidgetAbstract as Widget;

class wg_clock extends Widget{

    /*
        $viewParameters: 
        Extends @parent:$viewParameters
        Widget specific vars: none
    */
    public function __construct($viewParameters){
        //Widget Defaults 
        $defaultParameters=[
            'WidgetName'=>'clock',
            'Width'=>'2',
            'DataHeight'=>300,
            // 'HeaderBackground'=> 'bg-transparent',
            // 'WidgetBackground'=> 'bg-transparent',
            'HeaderIcon'=>false,
            'DisableDigital'=>false
        ];
        parent::__construct(array_merge($defaultParameters, ($viewParameters ?? [])));
        $this->setView('fe_widgets::stock.wg_clock');
        $this->enqueueFooter(asset('/feiron/fe_widgets/js/wg_clock.js'));
        $this->enqueueHeader(asset('/feiron/fe_widgets/css/wg_clock.css'));
    }

    public function dataFunction()
    {
        return $this->viewParameters['Widget_contents'];
    }
    public static function userSettingOutlet()
    {
        return [
            ['key'=>'location','type'=>'text','placeholder'=>''],
            ['key' => 'unit', 'type' => 'radio','options'=>['Metric', 'Imperial'],'value'=> 'Imperial']
        ];
    }
}
