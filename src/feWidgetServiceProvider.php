<?php

namespace feiron\fe_widgets;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use feiron\fe_widgets\widgets\WidgetProvider;
use feiron\fe_widgets\widgets\WidgetManager;

class feWidgetServiceProvider extends ServiceProvider {

    protected $defer = true;

    public function boot(){

        $PackageName='fe_widgets';
        //locading package route files
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');        
        //location package view files
        $this->loadViewsFrom(__DIR__ . '/resources/view', $PackageName);
        //loading migration scripts
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        //publish widget assets
        $this->publishes([
            __DIR__ . '/assets' => public_path('feiron/' . $PackageName. "/widgets/"),
        ], ($PackageName . '_widgets'));

        Blade::component('w-dashboard', feiron\fe_widgets\lib\components\widgetDashBoard::class);
        Blade::directive('Widgets', function ($widgetExpression) {
            return "<?=(app()->Widget->BuildWidget($widgetExpression)->render())?>";
        });
    }

    public function register(){
        $this->app->singleton('Widget', function ($app) {
            return new WidgetProvider($app);
        });

        $this->app->singleton('WidgetManager', function ($app) {
            return new WidgetManager($app);
        });    
    }
}

?>