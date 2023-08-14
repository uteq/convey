<?php

namespace Uteq\LaravelVoltWebPush;

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
            ->hasViews()
            ->hasViewComponents('web-push', [
                'subscribe-button',
            ]);
    }
}
