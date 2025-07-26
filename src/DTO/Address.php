<?php

namespace Japseyz\PlotLookup\DTO;

class Address
{
    public string $fullAddress;
    public string $street;
    public string $number;
    public string $streetCode;
    public string $city;
    public string $postalCode;
    public string $cityLower;
    public string $municipality;
    public string $region;
    public string $section;
    public string $courtJurisdiction;
    public string $policeJurisdiction;
    public string $politicalJurisdiction;
    public string $politicalMajor;
    public string $politicalRegion;
    public string $zone;
    public string $hoa;
    public string $hoaId;
    public string $esr;
    public string $cadastre;
    public float $lng;
    public float $lat;
    public bool $bridged;
    public int $plot;
    public string $mapId;

    public function __construct(?array $data = null)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
