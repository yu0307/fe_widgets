<?php

namespace feiron\fe_widgets\lib\stock;

use feiron\fe_widgets\lib\WidgetAbstract as Widget;
use feiron\fe_widgets\models\userWidgetLayout;

use function PHPUnit\Framework\isEmpty;

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
            'Width'=>'3',
            'DataHeight'=>'240',
            'HeaderIcon'=>"fas fa-cloud-sun",
            'WidgetBackground'=> 'bg-transparent',
            'HeaderBackground'=> 'bg-transparent',
            'AjaxLoad'=>true
        ];
        parent::__construct(array_merge($defaultParameters, ($viewParameters ?? [])));
        $this->setView('fe_widgets::stock.wg_weather');
        $this->enqueueHeader(asset('/feiron/fe_widgets/plugins/skycons/skycons.min.js'));
        $this->enqueueHeader(asset('/feiron/fe_widgets/css/wg_weather.css'));
    }

    public function getAjaxData($request){
        $api_key = '13abbb52fe069d005b73bef3cd35b232';
        $setting = userWidgetLayout::where('layoutable_id', auth()->user()->id)->find($request->input('key'));
        $setting = json_decode($setting->settings??[]);
        $api_endpoint = ($request->input('URLaction')== 'get5days')? 'https://api.openweathermap.org/data/2.5/forecast': 'https://api.openweathermap.org/data/2.5/weather';
        $setting['q']=$setting['location']??'Mountain View, US';        
        $setting['units']=$setting['unit']??'imperial';
        unset($setting['location']);
        unset($setting['unit']);
        $param='';
        foreach($setting as $name=>$val){
            $val=join(',',array_map(function($v){
                return str_replace(' ','+',trim($v));
            },explode(',',trim($val))));
            $param.="&$name=".$val;
        }
        $api_url = $api_endpoint. "?".ltrim($param,'&') . '&appid=' . $api_key;
        if (!isset($api_url)) {
            return (['status'=>'error','message'=>'no api URL found']);
        }
        if ($this->get_http_response_code($api_url) !== '200') {
            return (['status' => 'error', 'message' => 'URL format invalid']);
        }
        $api_data = file_get_contents($api_url);
        return (['status' => false, 'message' => 'bypass for direct output', 'data' => array_merge(['unit'=>$setting['units']],json_decode($api_data,true))]);
    }

    private function get_http_response_code($url){
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
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
