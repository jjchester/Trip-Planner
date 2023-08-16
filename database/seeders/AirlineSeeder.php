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
            [
                'iata_code' => 'DL',
                'name' => 'Delta Air Lines',
            ],
            [
                'iata_code' => 'UA',
                'name' => 'United Airlines',
            ],
            // Add more airlines if needed
        ];

        foreach ($airlinesData as $data) {
            Airline::create($data);
        }
    }
}
