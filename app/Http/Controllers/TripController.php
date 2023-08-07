<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function oneWayTrip($departureAirportCode, $arrivalAirportCode)
    {
        $departureAirport = Airport::where('iata_code', $departureAirportCode)->firstOrFail();
        $arrivalAirport = Airport::where('iata_code', $arrivalAirportCode)->firstOrFail();

        $flights = Flight::where('departure_airport_id', $departureAirport->id)
            ->where('arrival_airport_id', $arrivalAirport->id)
            ->get();

        $trips = [];
        foreach ($flights as $flight) {
            $trip = [
                'type' => 'one-way',
                'airline' => $flight->airline->name,
                'flight_number' => $flight->flight_number,
                'departure_airport' => $departureAirport->name,
                'departure_time' => $flight->departure_time,
                'arrival_airport' => $arrivalAirport->name,
                'duration_minutes' => $flight->duration_minutes,
                'price' => $flight->price,
            ];

            $trips[] = $trip;
        }
        return response()->json(['trips' => $trips]);
    }

    public function roundTrip($departureAirportCode, $arrivalAirportCode)
    {
        $outboundFlights = Flight::where('departure_airport_id', $departureAirport->id)
            ->where('arrival_airport_id', $arrivalAirport->id)
            ->get();

        $inboundFlights = Flight::where('departure_airport_id', $arrivalAirport->id)
            ->where('arrival_airport_id', $departureAirport->id)
            ->get();

        $trips = [];
        foreach ($outboundFlights as $outbound) {
            foreach ($inboundFlights as $inbound) {
                $trip = [
                    'type' => 'round-trip',
                    'outbound_airline' => $outbound->airline->name,
                    'outbound_flight_number' => $outbound->flight_number,
                    'outbound_departure_airport' => $departureAirport->name,
                    'outbound_departure_time' => $outbound->departure_time,
                    'outbound_arrival_airport' => $arrivalAirport->name,
                    'outbound_duration_minutes' => $outbound->duration_minutes,
                    'outbound_price' => $outbound->price,
                    'inbound_airline' => $inbound->airline->name,
                    'inbound_flight_number' => $inbound->flight_number,
                    'inbound_departure_airport' => $arrivalAirport->name,
                    'inbound_departure_time' => $inbound->departure_time,
                    'inbound_arrival_airport' => $departureAirport->name,
                    'inbound_duration_minutes' => $inbound->duration_minutes,
                    'inbound_price' => $inbound->price,
                ];

                $trips[] = $trip;
            }
        }

        return response()->json(['trips' => $trips]);
    }
}
