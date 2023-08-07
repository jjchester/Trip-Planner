<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;

class FlightSeeder extends Seeder
{
    public function run()
    {
        $flightsData = [
            [
                'airline' => 'AC',
                'number' => '301',
                'departure_airport' => 'YUL',
                'departure_time' => '07:30',
                'arrival_airport' => 'YVR',
                'duration_minutes' => 330,
                'price' => '600.31',
            ],
            [
                'airline' => 'AC',
                'number' => '304',
                'departure_airport' => 'YVR', // Assuming a typo and correcting it to 'YVR'
                'departure_time' => '08:55', // Assuming a typo and correcting it to '08:55'
                'arrival_airport' => 'YUL',
                'duration_minutes' => 277,
                'price' => '499.93',
            ],
            // Add more flights if needed
        ];

        foreach ($flightsData as $data) {
            $airline = Airline::where('iata_code', $data['airline'])->firstOrFail();
            $departureAirport = Airport::where('iata_code', $data['departure_airport'])->firstOrFail();
            $arrivalAirport = Airport::where('iata_code', $data['arrival_airport'])->firstOrFail();

            Flight::create([
                'airline_id' => $airline->id,
                'flight_number' => $data['number'],
                'departure_airport_id' => $departureAirport->id,
                'arrival_airport_id' => $arrivalAirport->id,
                'departure_time' => $data['departure_time'],
                'duration_minutes' => $data['duration_minutes'],
                'price' => $data['price'],
            ]);
        }
    }
}
