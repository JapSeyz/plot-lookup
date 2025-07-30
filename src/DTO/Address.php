<?php

namespace Japseyz\PlotLookup\DTO;

class Address
{
    public ?string $full_address;

    public ?string $street;

    public ?string $number;

    public ?string $street_code;

    public ?string $city;

    public ?string $postal_code;

    public ?string $city_lower;

    public ?string $municipality;

    public ?string $region;

    public ?string $section;

    public ?string $court_jurisdiction;

    public ?string $police_jurisdiction;

    public ?string $political_jurisdiction;

    public ?string $political_major;

    public ?string $political_region;

    public ?string $zone;

    public ?string $hoa;

    public ?string $hoa_id;

    public ?string $esr;

    public ?string $cadastre;

    public float $lng;

    public float $lat;

    public bool $bridged;

    public int $plot;

    public ?string $map_id;

    public array $buildings = [];

    public function __construct(?array $data = null)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
