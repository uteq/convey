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

        $this->loadViewsFrom(__DIR__.'/resources/view/components', 'web-push');

        Blade::component('web-push::subscribe-button', 'subscribe-button');

        //        Blade::anonymousComponentNamespace('components', 'web-push');
        //
        //        Blade::anonymousComponentPath('resources.views.components.subscribe-button', 'web-push');
        //        dd(Blade::getAnonymousComponentNamespaces());

    }
}
