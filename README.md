## Trip Planner Setup
To get started with Trip Planner, make sure you have PHP 8.x installed and set as your active PHP version.  
Instructions for installing PHP can be found [here](https://www.php.net/manual/en/install.php)  

This project also requires an npm package, `Axios`, for some of the frontend js, so you'll have to ensure that you have npm installed locally. Instructions for installing Node.js and npm can be found [here](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)  

The rest of the dependencies, setup, etc. can be performed by running the `setup` make target:  
`$ make setup`
This will install PHP dependencies, install the Axios package, set up the Laravel key/storage configs, run the database creation migrations, and run the database seeders.  

After you've completed the previous steps, the server can be run with the `server` make target:  
`$ make server`  
You can interact with the app via the web interface at http://localhost:8000/trip-search or 