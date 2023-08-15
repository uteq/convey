<?php

namespace Uteq\Convey;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Uteq\Convey\Commands\InstallCommand;

class ConveyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        AutoInjectConveyAssets::provide();

        $package
            ->name('convey')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommands(InstallCommand::class);
    }

    public function boot()
    {
        Blade::anonymousComponentPath(__DIR__.'/../components', 'webpush');
    }
}
