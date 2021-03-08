<?php

namespace feiron\fe_widgets\lib\components;

use Illuminate\View\Component;

class widgetDashBoard extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('fe_widgets::components.widget-dashboard');
    }
}
