<?php

namespace Japseyz\PlotLookup;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Japseyz\PlotLookup\Commands\PlotLookupCommand;

class PlotLookupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('plot-lookup')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(PlotLookupCommand::class);
    }
}
