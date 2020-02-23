<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Providers;

use Illuminate\Support\ServiceProvider;
use ShibuyaKosuke\LaravelPostalcodeJapan\Console\PostalCodeUpdate;

/**
 * Class CommandServiceProvider
 * @package Shibuyakosuke\LaravelCrudGenerator\Providers
 */
class CommandServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->registerCommands();

        // migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        // routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->publishes([
        ]);
    }

    public function register()
    {
        // register bindings
    }

    protected function registerCommands()
    {
        $this->app->singleton('command.shibuyakosuke.postalcode.update', function () {
            return new PostalCodeUpdate();
        });

        $this->commands([
            'command.shibuyakosuke.postalcode.update'
        ]);
    }

    public function provides()
    {
        return [
            'command.shibuyakosuke.postalcode.update'
        ];
    }
}
