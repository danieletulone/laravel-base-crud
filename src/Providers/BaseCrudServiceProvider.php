<?php

namespace DanieleTulone\BaseCrud\Providers;

use Illuminate\Support\ServiceProvider;

class BaseCrudServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/basecrud.php' => config_path('basecrud.php'),
        ], 'basecrud-config');

        $this->publishes([
            __DIR__.'/../Http/Controllers/CrudController.php' => app_path('Http/Controllers/CrudController.php'),
            __DIR__.'/../Http/Controllers/RestCrudController.php' => app_path('Http/Controllers/RestCrudController.php'),
        ], 'basecrud-controllers');
    }
}
