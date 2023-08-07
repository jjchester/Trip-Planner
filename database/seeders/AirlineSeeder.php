<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline;

class AirlineSeeder extends Seeder
{
    public function run()
    {
        $airlinesData = [
            [
                'iata_code' => 'AC',
                'name' => 'Air Canada',
            ],
        ];

        foreach ($airlinesData as $data) {
            Airline::create($data);
        }
    }
}
