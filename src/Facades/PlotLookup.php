<?php

namespace Japseyz\PlotLookup\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Japseyz\PlotLookup\PlotLookup
 */
class PlotLookup extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Japseyz\PlotLookup\PlotLookup::class;
    }
}
