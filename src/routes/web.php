<?php
    Route::group(['namespace' => 'feiron\fe_widgets\controller', 'middleware' => ['web']], function () {        

        Route::get('dashboard',function(){return view('fe_widgets::dashboard');})->name('dashboard');
        
        Route::post('WidgetsAjax/{tarWidget}/{tarControl}', function(\Illuminate\Http\Request $request, $tarWidget, $tarControl){
            return app()->Widget->BuildWidget($tarWidget)->SetID($tarControl)->renderAjax($request);
        })->name('WidgetsAjaxPost');

        Route::get('WidgetsAjax/{tarWidget}/{tarControl}', function(\Illuminate\Http\Request $request, $tarWidget, $tarControl){
            return app()->Widget->BuildWidget($tarWidget)->SetID($tarControl)->renderAjax($request);
        })->name('WidgetsAjaxGet');

        Route::post('GetWidget/{WidgetName}', 'userWidgetLayoutController@addWidget')->name('WidgetsAjaxBuild');

        Route::get('GetWidgetList', function () {
            foreach(app()->FeFrame->getFilterBlock()??[] as $filter){
                $filter->filter(request(),app()->WidgetManager);
            }
            return response()->json(app()->WidgetManager->getSiteWidgetList());
        })->name('GetWidgetList');

        Route::get('GetWidgetDetails/{WidgetName}', function ($WidgetName) {
            return response()->json(app()->WidgetManager->getSiteWidgetDetail($WidgetName));
        })->name('GetWidgetDetails');

        Route::post('updateWidgetLayout', 'userWidgetLayoutController@UpdateWidgetLayout')->name('updateWidgetLayout');

        Route::post('updateUserWidgetSetting', 'userWidgetLayoutController@updateUserWidgetSetting')->name('updateWidgetSetting');
    });
?>