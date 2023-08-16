# Makefile for Laravel Project

# Set your PHP binary here
PHP_BIN = php

# Set your Laravel Artisan binary here
ARTISAN_BIN = $(PHP_BIN) artisan

# Set your server host and port
SERVER_HOST = localhost
SERVER_PORT = 8000

# Commands
server:
	@echo "Starting the development server..."
	$(ARTISAN_BIN) serve --host=$(SERVER_HOST) --port=$(SERVER_PORT)
	@echo "App is live at http://localhost:8000/trip-search"

migrate:
	touch database/database.sqlite
	@echo "Running migrations..."
	$(ARTISAN_BIN) migrate

seed:
	@echo "Seeding the database..."
	$(ARTISAN_BIN) db:seed --class=AirlineSeeder
	$(ARTISAN_BIN) db:seed --class=AirportSeeder
	$(ARTISAN_BIN) db:seed --class=FlightSeeder


setup:
	@echo "Setting up the project..."
	composer install
	cp .env.example .env
	npm install axios
	$(ARTISAN_BIN) key:generate
	$(ARTISAN_BIN) storage:link
	make migrate
	make seed
	@echo "Project setup complete!"
	@echo "Run 'make server' to start the development server"

.PHONY: server migrate seed setup
