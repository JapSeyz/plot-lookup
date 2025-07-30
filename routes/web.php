<?php

use Illuminate\Support\Facades\Route;
use Japseyz\PlotLookup\Http\Controllers\MapController;

Route::get('_plot_lookup/_map/{country}/{address}', [MapController::class, 'show'])->name('plot-lookup.maps.show')->where('address', '.*');
