<?php

namespace Cpvm\Subject;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SubjectServiceProvider extends ServiceProvider
{

    protected $package = 'cpvm';
    protected $module = 'subject';

    protected $namespace = __NAMESPACE__ . '\App\Http\Controllers';

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $routesDir = __DIR__ . '/routes';
        $ls = @scandir($routesDir);
        if ($ls) {
            foreach ($ls as $index => $routeFile) {
                switch (substr($routeFile, 0, -4)) {
                    case 'web':
                        if (file_exists(__DIR__ . '/routes/web.php')) {
                            Route::middleware('web')
                                ->namespace($this->namespace)
                                ->group(__DIR__ . '/routes/web.php');
                        }
                    case 'api':
                        if (file_exists(__DIR__ . '/routes/api.php')) {
                            Route::prefix('api')
                                ->middleware('api')
                                ->namespace($this->namespace)
                                ->group(__DIR__ . '/routes/api.php');
                        }
                        break;
                }
            }
        }

        /** load translations **/
        $this->loadTranslationsFrom(__DIR__ . '/translations', $this->package . '-' . $this->module);

        /** load views **/
        $groupName = config('site.group_name');
        $groupName = $groupName ? $groupName : 'default';
        $desktopTemplate = config('site.desktop.template');
        $desktopTemplate = $desktopTemplate ? $desktopTemplate : 'default';
        $templateName = $groupName . '/' . $desktopTemplate;

        /** load views **/
        $this->loadViewsFrom(__DIR__.'/views/' . $templateName . '/views', strtoupper($this->package . '-' . $this->module));

        /** publishes **/
        $skin = config('site.desktop.skin');
        $skin = $skin ? $skin : 'default';
        $this->publishes([
            __DIR__ . '/views/' . $groupName . '/' . $skin . '/public' => public_path('vendor/' . $groupName . '/' . $skin . '/' . $this->package . '/' . $this->module),
        ], $this->package . '_' . $this->module . '_public');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
