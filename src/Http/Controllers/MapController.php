<?php

namespace App\Http\Controllers\Frames;

use App\Http\Controllers\BaseController;
use Illuminate\View\View;
use Japseyz\PlotLookup\Services\DK\Datafordeler;
use function mb_strtoupper;
use function method_exists;
use function view;

class MapController extends BaseController
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
        $data = $service->address($this->address);
        $data['buildings'] = $service->buildings($data['plot']);

        return view('frames.maps.dk.bbr', [
            'plot' => $data
        ]);
    }
}
