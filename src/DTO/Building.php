<?php

namespace Japseyz\PlotLookup\DTO;

class Building
{
    public string $number;
    public string $usage;
    public string $builtAt;
    public string $wallMaterial;
    public string $rootMaterial;
    public string $areaTotal;
    public string $areaResidential;
    public string $areaGarage;
    public string $floors;
    public string $heatingDevice;
    public string $heatingType;
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
