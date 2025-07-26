<?php

namespace Japseyz\PlotLookup;

use Japseyz\PlotLookup\Commands\PlotLookupCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigration('create_plot_lookup_table')
            ->hasCommand(PlotLookupCommand::class);
    }
}
