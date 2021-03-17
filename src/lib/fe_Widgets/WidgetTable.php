<?php

namespace feiron\fe_widgets\lib\fe_Widgets;

use feiron\fe_widgets\lib\WidgetAbstract as Widget;

class WidgetTable extends Widget{

    private $tableData=[];
    /*
        $viewParameters: 
        Extends @parent:$viewParameters
        Widget specific vars:
        $viewParameters['headers'] : table headers
    */
    public function __construct($viewParameters){
        //Widget Defaults 
        $defaultParameters['BaseWidget']='WidgetTable';
        $defaultParameters['Width'] = '5';
        $defaultParameters['HeaderIcon'] = 'th-list';
        $defaultParameters['headers']=[];
        parent::__construct(array_merge($defaultParameters, ($viewParameters ?? [])));
        $this->setView('fe_widgets::widgetTable');
        if(false===empty($this->viewParameters['WidgetData'])){
            $this->tableData = (is_callable($this->viewParameters['WidgetData'])) ? $this->viewParameters['WidgetData']() : $this->viewParameters['WidgetData'];
        }
    }

    public function setHeader($headers){
        $this->viewParameters['headers']=$headers;
    }

    public function setData($data){
        $this->tableData = (is_callable($data)) ? $data() : $data;
        $this->viewParameters['WidgetData']=$this->tableData;
    }

    public function getAjaxData($request){
        return $this->tableData;
    }

    public function dataFunction(){
        if(false===empty($this->tableData)){
            if(is_array($this->tableData)===false){
                return $this->tableData;
            }else{
                $content='';
                foreach($this->tableData as $row){
                    $content .= '<tr>';
                    foreach ($row as $cell) {
                        $content.='<td>'. $cell.'</td>';
                    }
                    $content .= '</tr>';
                }
                return $content;
            }
        }
        return '';
    }
}

?>