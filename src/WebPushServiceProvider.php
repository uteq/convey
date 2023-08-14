<?php

namespace Uteq\LaravelVoltWebPush;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WebPushServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        AutoInjectWebpushAssets::provide();

        $package
            ->name('laravel-volt-web-push')
            ->hasConfigFile()
            ->hasViews();

        Blade::anonymousComponentNamespace(__DIR__ . '/../resources/views/components', 'web-push');
    }
}
