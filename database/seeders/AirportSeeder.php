<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run()
    {
        $airportsData = [
            [
                'iata_code' => 'YUL',
                'city_code' => 'VMQ',
                'name' => 'Pierre Elliott Trudeau International',
                'city' => 'Montreal',
                'country_code' => 'CA',
                'latitude' => 45.457714,
                'longitude' => -73.749908,
                'timezone' => 'America/Montreal',
            ],
            [
                'iata_code' => 'YVR',
                'city_code' => 'VVR',
                'name' => 'Vancouver International',
                'city' => 'Vancouver',
                'country_code' => 'CA',
                'latitude' => 49.194698,
                'longitude' => -123.179192,
                'timezone' => 'America/Vancouver',
            ],
        ];

        foreach ($airportsData as $data) {
            Airport::create($data);
        }
    }
}
