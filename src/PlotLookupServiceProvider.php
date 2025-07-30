<?php

namespace Japseyz\PlotLookup;

use Japseyz\PlotLookup\Commands\PlotLookupCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PlotLookupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('plot-lookup')
            ->hasConfigFile()
            ->hasAssets()
            ->hasRoutes('web')
            ->hasViews();
    }
}
