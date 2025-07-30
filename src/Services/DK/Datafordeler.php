<?php

namespace Japseyz\PlotLookup\Services\DK;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Japseyz\PlotLookup\DTO\Address;
use Japseyz\PlotLookup\DTO\Building;
use function config;
use function http_build_query;
use function str_contains;

class Datafordeler
{
    public function find(string $address): ?Address
    {
        $client = $this->getClient();
        $client->baseUrl('https://api.dataforsyningen.dk/');
        $uuid = $client->get('/datavask/adresser', [
            'betegnelse' => $address,
        ])->json('resultater.0.adresse.id');

        if (! $uuid) {
            return null;
        }

        return $this->address($uuid);
    }

    public function address(string $uuid): Address
    {
        $client = $this->getClient();
        $query = http_build_query([
            'username' => config('plot-lookup.dk.datafordeler.username'),
            'password' => config('plot-lookup.dk.datafordeler.password'),
            'Format'   => 'JSON',
            'Id'       => $uuid,
        ]);
        $url = "/DAR/DAR/3.0.0/rest/adresse?{$query}";
        $plot = $client->get($url)->json('0.husnummer.jordstykke');

        /** @var array<string,array<string,array<string|int, string|int>|string>> $data */
        $data = Http::get('https://api.dataforsyningen.dk/adresser/' . $uuid)->json('adgangsadresse');
        [$lng, $lat] = $data['adgangspunkt']['koordinater'];

        return new Address([
            'full_address'           => $data['adressebetegnelse'],
            'street'                 => $data['vejstykke']['navn'],
            'number'                 => $data['husnr'],
            'street_code'            => $data['vejstykke']['kode'],
            'city'                   => $data['postnummer']['navn'],
            'postal_code'            => $data['postnummer']['nr'],
            'city_lower'             => $data['supplerendebynavn'],
            'municipality'           => $data['kommune']['navn'],
            'region'                 => $data['region']['navn'],
            'section'                => $data['landsdel']['navn'],
            'court_jurisdiction'     => $data['retskreds']['navn'],
            'police_jurisdiction'    => $data['politikreds']['navn'],
            'political_jurisdiction' => $data['opstillingskreds']['navn'],
            'political_major'        => $data['storkreds']['navn'],
            'political_region'       => $data['valglandsdel']['navn'],
            'zone'                   => $data['zone'] ?? '',
            'hoa'                    => $data['jordstykke']['ejerlav']['navn'] ?? '',
            'hoa_id'                 => $data['jordstykke']['ejerlav']['kode'] ?? '',
            'esr'                    => $data['jordstykke']['esrejendomsnr'] ?? '',
            'cadastre'               => $data['jordstykke']['matrikelnr'] ?? '',
            'lng'                    => (float) $lng,
            'lat'                    => (float) $lat,
            'bridged'                => (int) ($data['brofast'] ?? 0),
            'plot'                   => (int) ($plot ?? 0),
            'map_id'                 => $uuid,
        ]);
    }

    public function buildings(int $plot): array
    {
        $client = $this->getClient();
        $query = http_build_query([
            'username'   => config('plot-lookup.dk.datafordeler.username'),
            'password'   => config('plot-lookup.dk.datafordeler.password'),
            'Format'     => 'JSON',
            'Jordstykke' => $plot,
        ]);

        $url = "/BBR/BBRPublic/1/rest/bygning?{$query}";
        $data = $client->get($url)->json();
        $buildings = [];

        foreach ($data as $building) {
            if (! ($building['byg007Bygningsnummer'] ?? null)) {
                continue;
            }
            if (! ($building['byg038SamletBygningsareal'] ?? $building['byg041BebyggetAreal'] ?? null)) {
                continue;
            }
            $point = $building['byg404Koordinat'] ?? null;
            if ($point) {
                [$x, $y] = explode(' ', substr($point, 6, -1));
            }

            $buildings[] = new Building([
                'number'           => $building['byg007Bygningsnummer'],
                'usage'            => $this->translateCode('BygAnvendelse', $building['byg021BygningensAnvendelse'] ?? null),
                'built_at'         => $building['byg026Opførelsesår'] ?? '',
                'wall_material'    => $this->translateCode('YdervaeggenesMateriale', $building['byg032YdervæggensMateriale'] ?? null),
                'roof_material'    => $this->translateCode('Tagdaekningsmateriale', $building['byg033Tagdækningsmateriale'] ?? null),
                'area_total'       => $building['byg038SamletBygningsareal'] ?? $building['byg041BebyggetAreal'] ?? '',
                'area_residential' => $building['byg039BygningensSamledeBoligAreal'] ?? $building['byg038SamletBygningsareal'] ?? $building['byg041BebyggetAreal'] ?? '',
                'area_garage'      => $building['byg042ArealIndbyggetGarage'] ?? '',
                'floors'           => $building['byg054AntalEtager'] ?? '',
                'heating_device'   => $this->translateCode('EnhVarmeinstallation', $building['byg056Varmeinstallation'] ?? null),
                'heating_type'     => $this->translateCode('Opvarmningsmiddel', $building['byg057Opvarmningsmiddel'] ?? null),
                'x'                => (float) ($x ?? 0),
                'y'                => (float) ($y ?? 0),
                'icon'             => $this->translateCodeToIcon($building['byg021BygningensAnvendelse'] ?? null),
                ''
            ]);
        }

        usort($buildings, fn(Building $a, Building $b) => $a->number <=> $b->number);

        return $buildings;
    }

    protected function translateCodeToIcon(?string $code): string
    {
        if ($code === null) {
            return '';
        }

        $icon = match ($code) {
            '230', '231', '232', '239' => 'energi',
            '233', '234'               => 'vandanlaeg',
            default                    => null,
        };

        if ($icon) {
            return $icon;
        }

        $code = substr($code, 0, 2);
        return match ($code) {
            '11', '51', '52', '54', '58', '59', '95', '96' => 'fritidsformaal',
            '12', '13', '14', '19'                         => 'helaarsbeboelse',
            '15', '16', '44', '49'                         => 'institution',
            '21', '29', '97'                               => 'landbrug',
            '22'                                           => 'industri',
            '31', '32', '33', '34', '41', '42', '43'       => 'erhverv',
            '53'                                           => 'idraetsformaal',
            '91', '92'                                     => 'carport-garage',

            default                                        => 'smaabygning',
        };
    }

    protected function getClient(): PendingRequest
    {
        return Http::throw()
            ->withUserAgent(config('plot-lookup.user_agent'))
            ->baseUrl('https://services.datafordeler.dk')
            ->acceptJson();
    }

    protected function translateCode(string $type, ?string $code): string
    {
        if ($code === null) {
            return '';
        }

        $handle = fopen(__DIR__ . "/lists.csv", "rb");
        if ($handle) {
            $search = $type . ';' . $code . ';';
            while (! feof($handle)) {
                $buffer = fgets($handle);
                if (strpos($buffer, $search) !== FALSE) {
                    fclose($handle);

                    if (str_contains($buffer, 'UDFASES')) {
                        return '';
                    }

                    return trim(str_replace($search, '', $buffer));
                }
            }
        }

        return '';
    }
}
