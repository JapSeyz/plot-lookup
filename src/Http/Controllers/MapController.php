<?php

namespace Japseyz\PlotLookup\Http\Controllers;

use Illuminate\View\View;
use Japseyz\PlotLookup\Services\DK\Datafordeler;
use function mb_strtoupper;
use function method_exists;
use function view;

class MapController
{
    protected string $country;
    protected string $address;

    public function show(string $country, string $address): View
    {
        $this->country = $country;
        $this->address = $address;

        if (method_exists($this, mb_strtoupper($this->country))) {
            return $this->{mb_strtoupper($this->country)}();
        }

        abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch data for respective countries
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    protected function DK(): View
    {
        $service = new Datafordeler();
        $address = $service->find($this->address);

        if (! $address) {
            abort(404, 'Address not found');
        }

        $address->buildings = $service->buildings($address->plot);

        return view('plot-lookup::dk', [
            'plot' => $address
        ]);
    }
}
