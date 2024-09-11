# Orders-manager
System to monitor and manage daily user orders

## Architecture Overview
<div align="center">
	<img src="./docs/general_architecture_1_0.png">
</div>

## Project Setup

### Requirements:
- Docker
- Docker Compose

### Steps to Run the Project:
1. Clone the repository.
2. Run `docker-compose up --build`.
3. Run `docker ps` to make sure that application is up&running.
4. Run `docker exec -it <api-container-name> php artisan migrate:fresh` to run database migrations
5. Run `docker exec -it <api-container-name> php artisan db:seed` to create fake data to make some tests
6. Access the API at `localhost:9005` and the frontend at `localhost:8080`.

### API Endpoints:
- `GET /api/v1/orders`: Fetch all orders with optional filters.
- `GET /api/v1/orders/{order}`: Fetch a specific order by ID.
- `POST /api/v1/orders`: Create a new order.
- `PUT /api/v1/orders/{order}`: Update an existing order.
- `DELETE /api/v1/orders/{order}`: Delete an order.

### Swagger documentation
For more details on the API Endopoints, once your container are up&running, go to : /api/documentation

### Running Tests:
To run backend tests:
```bash
php artisan test

