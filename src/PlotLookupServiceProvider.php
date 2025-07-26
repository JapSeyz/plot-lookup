<?php

namespace Japseyz\PlotLookup;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PlotLookupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('plot-lookup')
            ->hasConfigFile()
            ->hasViews();
    }
}
