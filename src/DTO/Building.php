<?php

namespace Japseyz\PlotLookup\DTO;

class Building
{
    public string $number;
    public string $usage;
    public string $built_at;
    public string $wall_material;
    public string $roof_material;
    public string $area_total;
    public string $area_residential;
    public string $area_garage;
    public string $floors;
    public string $heating_device;
    public string $heating_type;
    public string $x;
    public string $y;
    public string $icon;

    public function __construct(?array $data = null)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
