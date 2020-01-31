<?php

namespace Bioforce\SeoSettings;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SeoSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Bioforce\SeoSettings\Controllers\SeoSettingController');
        $this->app->make('Bioforce\SeoSettings\Controllers\SeoMetaTagController');
        $this->app->make('Bioforce\SeoSettings\Controllers\SeoTagGroupController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->registerHelpers();

        $this->loadViewsFrom(
            __DIR__ . '/resources/views',
            'seo_settings'
        );

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/seo_settings')
        ], 'views');

        $this->publishes([
            __DIR__ . '/resources/js' =>
            resource_path('vendor/seo-settings'
        )], 'vue-seo-settings');

        $this->publishes([
            __DIR__ . '/assets' =>  public_path('vendor/bioforce'),
        ], 'assets');

        $this->publishes([
            __DIR__ . '/config/seo-settings.php' => config_path('seo-settings.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->registerBladeDirectives();
    }

    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        if (file_exists($file = __DIR__ .'/helpers.php'))
        {
            require_once $file;
        }
    }

    /**
     * Register blade directives for metatags
     *
     */
    public function registerBladeDirectives()
    {

        Blade::directive('metatags', function () {
            return "<?php echo view('seo_settings::meta', ['metaTags' => meta_tags_array()])->render(); ?>";
        });
    }
}
