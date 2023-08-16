<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;

class TripController extends Controller
{

    public function searchView()
    {
        $airports = Airport::all(); // Retrieve the list of airports from the database

        return view('trip-search', compact('airports'));
    }

    public function oneWayTrip(Request $request)
    {
        $departureAirportCode = $request->query('departure_airport');
        $arrivalAirportCode = $request->query('arrival_airport');
        $sort = $request->query('sort');

        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->whereHas('departureAirport', function ($query) use ($departureAirportCode) {
                $query->where('iata_code', $departureAirportCode);
            })
            ->whereHas('arrivalAirport', function ($query) use ($arrivalAirportCode) {
                $query->where('iata_code', $arrivalAirportCode);
            })
            ->get();

        $tripOptions = [];

        foreach ($flights as $flight) {
            $tripOptions[] = [
                'airline' => $flight->airline->name,
                'flight_number' => $flight->flight_number,
                'departure_airport' => $flight->departureAirport->name,
                'arrival_airport' => $flight->arrivalAirport->name,
                'departure_time' => $flight->departure_time,
                'duration_minutes' => $flight->duration_minutes,
                'price' => $flight->price,
            ];
        }
        usort($tripOptions, array($this, $sort == 'price' ? "compareByPrice" : "compareByDuration"));
        return response()->json($tripOptions);
    }

    function compareByPrice($a, $b)
    {
        return ($a['price'] > $b['price']);
    }

    function compareByDuration($a, $b)
    {
        return ($a['duration_minutes'] > $b['duration_minutes']);
    }

    function compareByTotalPrice($a, $b)
    {
        return ($a['total_price'] > $b['total_price']);
    }

    function compareByTotalDuration($a, $b)
    {
        return ($a['total_duration'] > $b['total_duration']);
    }

    public function roundTrip(Request $request)
    {
        $departureAirportCode = $request->query('departure_airport');
        $arrivalAirportCode = $request->query('arrival_airport');
        $sort = $request->query('sort');

        // Find outbound flights
        $outboundFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->whereHas('departureAirport', function ($query) use ($departureAirportCode) {
                $query->where('iata_code', $departureAirportCode);
            })
            ->whereHas('arrivalAirport', function ($query) use ($arrivalAirportCode) {
                $query->where('iata_code', $arrivalAirportCode);
            })
            ->get();

        // Find return flights
        $returnFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->whereHas('departureAirport', function ($query) use ($arrivalAirportCode) {
                $query->where('iata_code', $arrivalAirportCode);
            })
            ->whereHas('arrivalAirport', function ($query) use ($departureAirportCode) {
                $query->where('iata_code', $departureAirportCode);
            })
            ->get();

        $roundTripOptions = [];

        foreach ($outboundFlights as $outbound) {
            foreach ($returnFlights as $return) {
                $roundTripOptions[] = [
                    'outbound' => [
                        'airline' => $outbound->airline->name,
                        'flight_number' => $outbound->flight_number,
                        'departure_airport' => $outbound->departureAirport->name,
                        'arrival_airport' => $outbound->arrivalAirport->name,
                        'departure_time' => $outbound->departure_time,
                        'duration_minutes' => $outbound->duration_minutes,
                        'price' => $outbound->price,
                    ],
                    'return' => [
                        'airline' => $return->airline->name,
                        'flight_number' => $return->flight_number,
                        'departure_airport' => $return->departureAirport->name,
                        'arrival_airport' => $return->arrivalAirport->name,
                        'departure_time' => $return->departure_time,
                        'duration_minutes' => $return->duration_minutes,
                        'price' => $return->price,
                    ],
                    'total_price' => $outbound->price + $return->price,
                    'total_duration' => $outbound->duration_minutes + $return->duration_minutes
                ];
            }
        }
        usort($roundTripOptions, array($this, $sort == 'price' ? "compareByTotalPrice" : "compareByTotalDuration"));

        return response()->json($roundTripOptions);
    }
}
