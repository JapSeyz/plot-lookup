<?php

use Japseyz\PlotLookup\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('_plot_lookup/_map/{country}/{address}', [MapController::class, 'show'])->name('plot-lookup.maps.show')->where('address', '.*');
