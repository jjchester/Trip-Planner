<!DOCTYPE html>

<div class="container">
    <h1>Trip Search</h1>

    <form id="trip-search-form">
        @csrf
        <div class="form-group">
            <label for="trip_type">Trip Type:</label>
            <select class="form-control" id="trip_type" name="trip_type">
                <option value="one-way">One-Way</option>
                <option value="round-trip">Round-Trip</option>
            </select>
        </div>

        <div class="form-group">
            <label for="departure_airport">Departure Airport:</label>
            <select class="form-control" id="departure_airport" name="departure_airport">
                @foreach ($airports as $airport)
                <option value="{{ $airport->iata_code }}">{{ $airport->name }} ({{ $airport->iata_code }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="arrival_airport">Arrival Airport:</label>
            <select class="form-control" id="arrival_airport" name="arrival_airport">
                @foreach ($airports as $airport)
                <option value="{{ $airport->iata_code }}">{{ $airport->name }} ({{ $airport->iata_code }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="sort_option">Sort By:</label>
            <select class="form-control" id="sort_option" name="sort_option">
                <option value="price">Lowest Price</option>
                <option value="duration">Shortest Duration</option>
            </select>
        </div>

        <button type="button" id="search-button" class="btn btn-primary">Search</button>
    </form>

    <div id="results">
        <!-- Display search results dynamically using JavaScript -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('search-button').addEventListener('click', async () => {
        const tripType = document.getElementById('trip_type').value;
        const departureAirport = document.getElementById('departure_airport').value;
        const arrivalAirport = document.getElementById('arrival_airport').value;
        const sortOption = document.getElementById('sort_option').value;

        try {
            let response;
            if (tripType === 'one-way') {
                response = await axios.get(`/api/trips/one-way?departure_airport=${departureAirport}&arrival_airport=${arrivalAirport}&sort=${sortOption}`);
            } else if (tripType === 'round-trip') {
                response = await axios.get(`/api/trips/round-trip?departure_airport=${departureAirport}&arrival_airport=${arrivalAirport}&sort=${sortOption}`);
            }

            const trips = response.data;

            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = '';

            if (trips.length == 0) {
                const tripContainer = document.createElement('div'); // Create a container for each trip
                tripContainer.classList.add('trip-container'); // Add a CSS class for styling

                tripContainer.innerHTML = `
                <p><b>There are no flights that match the provided query.</b></p>
                `;

                resultsDiv.appendChild(tripContainer); // Append the container to the results div
            } else {
                trips.forEach(trip => {
                    const tripContainer = document.createElement('div'); // Create a container for each trip
                    tripContainer.classList.add('trip-container'); // Add a CSS class for styling

                    if (trip.outbound && trip.return) {
                        // Display round-trip details
                        tripContainer.innerHTML = `
                            <hr>
                            <b>Outbound Airline: ${trip.outbound.airline}</b>
                            <p>Outbound Flight Number: ${trip.outbound.flight_number}</p>
                            <p>Outbound Departure Airport: ${trip.outbound.departure_airport}</p>
                            <p>Outbound Arrival Airport: ${trip.outbound.arrival_airport}</p>
                            <p>Outbound Departure Time: ${trip.outbound.departure_time}</p>
                            <p>Outbound Duration: ${trip.outbound.duration_minutes} minutes</p>
                            <p>Outbound Price: $${trip.outbound.price}</p>
                            <br/>
                            <b>Return Airline: ${trip.return.airline}</b>
                            <p>Return Flight Number: ${trip.return.flight_number}</p>
                            <p>Return Departure Airport: ${trip.return.departure_airport}</p>
                            <p>Return Arrival Airport: ${trip.return.arrival_airport}</p>
                            <p>Return Departure Time: ${trip.return.departure_time}</p>
                            <p>Return Duration: ${trip.return.duration_minutes} minutes</p>
                            <p>Return Price: $${trip.return.price}</p>
                            <p><b>Total Trip Price: $${trip.total_price}</b></p>
                            <p><b>Total Trip Duration: ${trip.total_duration} minutes</b></p>
                        `;
                    } else if (trip.airline) {
                        // Display one-way details
                        tripContainer.innerHTML = `
                            <hr>
                            <b>Airline: ${trip.airline}</b>
                            <p>Flight Number: ${trip.flight_number}</p>
                            <p>Departure Airport: ${trip.departure_airport}</p>
                            <p>Arrival Airport: ${trip.arrival_airport}</p>
                            <p>Departure Time: ${trip.departure_time}</p>
                            <p>Duration: ${trip.duration_minutes} minutes</p>
                            <b>Price: $${trip.price}</b>
                        `;
                    }

                    resultsDiv.appendChild(tripContainer); // Append the container to the results div
                });
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    });

    document.getElementById('sort_option').addEventListener("change", (event) => {
        document.getElementById('search-button').click();
    });
</script>