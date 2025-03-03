<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ChartHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the barcode service provider
        $this->app->register(\Milon\Barcode\BarcodeServiceProvider::class);
        $this->app->alias('DNS1D', \Milon\Barcode\Facades\DNS1DFacade::class);
        $this->app->alias('DNS2D', \Milon\Barcode\Facades\DNS2DFacade::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the getChartColor function for use in Blade templates
        Blade::directive('getChartColor', function ($index) {
            return "<?php echo \App\Helpers\ChartHelper::getChartColor($index); ?>";
        });
    }
}
