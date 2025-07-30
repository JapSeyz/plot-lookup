<?php

namespace Japseyz\PlotLookup\DTO;

class Address
{
    public string|null $full_address;
    public string|null $street;
    public string|null $number;
    public string|null $street_code;
    public string|null $city;
    public string|null $postal_code;
    public string|null $city_lower;
    public string|null $municipality;
    public string|null $region;
    public string|null $section;
    public string|null $court_jurisdiction;
    public string|null $police_jurisdiction;
    public string|null $political_jurisdiction;
    public string|null $political_major;
    public string|null $political_region;
    public string|null $zone;
    public string|null $hoa;
    public string|null $hoa_id;
    public string|null $esr;
    public string|null $cadastre;
    public float $lng;
    public float $lat;
    public bool $bridged;
    public int $plot;
    public string|null $map_id;
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
