<?php

namespace GBarak\ViewCrudGenerator;

use Illuminate\Support\ServiceProvider;

class ViewCrudServiceProvider extends ServiceProvider
{
    /**
    * Bootstrap the application services.
    *
    * @return void
    */
    public function boot()
    {
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'view-crud-generator');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../src/app/Http/routes.php';
        }
    }

    /**
    * Register the application services.
    *
    * @return void
    */
    public function register()
    {
        $this->commands([
			Console\ViewCrudGenerate::class
        ]);
    }
}
