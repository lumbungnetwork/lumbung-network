<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    public function boot() {
        parent::boot();
    }

    public function map() {
//        $this->mapApiRoutes();
        $this->mapAdminRoutes();
    }

    protected function mapApiRoutes() {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
    
    protected function mapAdminRoutes(){
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/admin.php'));
    }
}
