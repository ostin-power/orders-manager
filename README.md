# Orders-manager
System to monitor and manage daily user orders.

## Table of Contents
- [Architecture](#architecture)
  - [Design](#design)
  - [Overview](#overview)
  - [Services](#services)
    - [APP (Frontend Service)](#app-frontend-service)
    - [API (Backend API Service)](#api-backend-api-service)
    - [DB (Database Service)](#db-database-service)
  - [Networks](#networks)
  - [Volumes](#volumes)
- [Project Setup](#project-setup)
  - [Requirements](#requirements)
  - [Steps to Run the Project](#steps-to-run-the-project)
- [API Endpoints](#api-endpoints)
  - [Swagger documentation](#swagger-documentation)
- [Tests](#tests)
  - [Prerequisites](#prerequisites)
  - [Running Tests](#running-tests)
- [Troubleshooting](#troubleshooting)
  - [Containers Won't Start or Exit Immediately](#containers-wont-start-or-exit-immediately)
  - [MySQL Database Fails to Start](#mysql-database-fails-to-start)
  - [Application (App or API) Cannot Connect to Database](#application-app-or-api-cannot-connect-to-database)
  - [Unable to Access App or API on Exposed Ports](#unable-to-access-app-or-api-on-exposed-ports)
  - [Volume Changes Not Reflecting](#volume-changes-not-reflecting)


# Architecture
This architecture supports a clear separation of concerns, with distinct services for the frontend, backend, and database. Docker containers provide isolation, ease of deployment, and flexibility. Environment variables allow configuration to be managed externally, making it adaptable to different environments like development, staging, or production.

### Design
<div align="center">
	<img src="./docs/general_architecture_1_0.png">
</div>

### Overview
This system architecture is based on Docker containers, which are orchestrated to run a web application with a front-end (app), a back-end API (api), and a MySQL database (db).

Below is a breakdown of each service (docker-compose.yml description):

#### **Services**
1. **APP** (Frontend Service)<br>
A Laravel-based service serves as the user-facing interface, which communicates with the backend API to handle data and application logic.
    - **Dockerfile**: The frontend service is built using the Dockerfile found in the **src/orders-app** directory.
    - **Ports**: The service exposes port **8000 internally**, but maps it to an environment variable (**FRONTEND_APP_PORT**), allowing flexibility in assigning the external port.
    - **Environment Variables**: Variables like **APP_NAME, APP_ENV, APP_DEBUG** are set using values from the environment, allowing configuration between environments (e.g., development, production). The **BACKEND_URL** points to the backend API using the internal Docker network, making the api service available on its internal port (9005) through the private network.
    - **Volumes**: The source code for the frontend is mounted from the host machine (./src/orders-app) into the container (/var/www), **enabling code updates without rebuilding the container**.
    - **Networks**: This service is connected to the private network, enabling **internal communication** with other services (like api and db).
    - **Command**: The service runs a setup script setupenv.py, installs PHP dependencies via composer, and starts the Laravel application on port 8000.
2. **API** (Backend API Service)<br>
A Laravel API that processes business logic and interacts with the MySQL database.
    - **Dockerfile**: Similar to the frontend, the backend API is built using the Dockerfile located in **src/orders-api**.
    - **Ports**: The API exposes port 9005 internally and maps it to an environment variable (BACKEND_API_PORT).
    - **Environment Variables**: Key settings like **APP_NAME, APP_ENV, and APP_DEBUG** are set based on environment variables. Database configuration (**DB_CONNECTION, DB_HOST, DB_PORT, etc.**) allows the API to connect to the db service. The **FRONTEND_URL** and **BACKEND_URL** define the internal addresses for communication between the frontend and backend services.
    - **Volumes**: The API source code is mounted from the host directory ./src/orders-api to /var/www, similar to the frontend.
    - **Depends On**: This service depends on the db service, meaning Docker will ensure the db service starts before the api service.
    - **Networks**: Connected to the **private network** for internal communication.
    - **Command**: The same setup script setupenv.py is run, followed by the installation of PHP dependencies and the starting of the Laravel API server on port 9005.
3. **DB** (Database Service)<br>
A MySQL 8.0 instance for data persistence, shared between the API.
    - **Docker**: This service uses the official **MySQL 8.0** Docker image.
    - **Environment Variables**: MYSQL_DATABASE, MYSQL_USER, MYSQL_PASSWORD, and MYSQL_ROOT_PASSWORD are provided from environment variables to initialize the MySQL database.
    - **Ports**: MySQL is exposed internally on port 3306 and mapped externally to a configurable MYSQL_DB_PORT.
    - **Volumes**: Data persistence is managed by **mounting a volume db_data**, which ensures database data is stored even if the container is restarted.
    - **Networks**: The database is connected to the private network, allowing it to **communicate securely** with the app and api services.

#### **Networks**
All services are part of the private network, ensuring secure, internal communication with each other.

- **Private**: All services (app, api, db) are part of a **shared, isolated private** Docker network. This network facilitates internal communication between services, making them accessible by service name (e.g., app, api, db).

#### **Volumes**
- **db_data**: This named volume is used by the db service **to persist MySQL data** across container restarts. It ensures that database changes are not lost when the container stops or is rebuilt.


## Project Setup

### Requirements:
- Docker
- Docker Compose

### Steps to Run the Project:
1. Clone the repository: `git clone https://github.com/ostin-power/orders-manager.git`
2. Go to project directory `cd orders-manager/`
3. In the root directory copy .env.example: `cp .env.example .env`
4. Change any conf values you need such as ports or db name **(it is advisable to keep the default values)**
5. Run `docker compose up --build`.
6. Wait for both app and api services to start: the above command (without the ‘-d’ option for detached) will return the following output:<br>
    - `INFO  Server running on [http://0.0.0.0:8000]` 
    - `INFO  Server running on [http://0.0.0.0:9005]`
6. Run `docker ps` to make sure that application is up&running.
7. Run `docker exec -it <api-container-name> php artisan migrate:fresh` to run database migrations
8. Run `docker exec -it <api-container-name> php artisan db:seed` to create fake data to make some tests (not mandatory)
9. Access the API at `localhost:<BACKEND_API_PORT>/api/v1/` (see Swagger documentation below) and the frontend at `localhost:<FRONTEND_APP_PORT>`. Ports values are specified inside .env file.

## API Endpoints:
- `GET /api/v1/orders`: Fetch all orders with optional filters.
- `GET /api/v1/orders/{order}`: Fetch a specific order by ID.
- `GET /api/v1/products`: Fetch all products.
- `GET /api/v1/products/{product}`: Fetch a specific product by ID.
- `POST /api/v1/orders`: Create a new order.
- `POST /api/v1/products`: Create a new product.
- `PUT /api/v1/orders/{order}`: Update an existing order.
- `PUT /api/v1/products/{product}`: Update an existing product.
- `DELETE /api/v1/orders/{order}`: Delete an order by ID.
- `DELETE /api/v1/products/{product}`: Delete a product by ID.

### Swagger documentation
You can try backend APIs from Swagger interface: this will simplify your tests or give a powerful instrument to use api without the Frontend application. For more details about Endopoints, once your containers are up&running, go to :
```bash
http://localhost:<BACKEND_API_PORT>/api/documentation
```


## Tests

### Coverage
- Order/product Creation: simulate the process of creating an order and check if the data is correctly saved in the database.
- Order/product Update/Deletion: test functionality of updating and deleting orders/product. These tests ensure that data is modified and removed as expected.
- APIs: ensures that all routes respond with the correct status codes and data structures. For example, testing the order API endpoints to ensure they return the correct response when queried.
- Validation: ensure that invalid order data is handled correctly (e.g., when required fields are missing or contain invalid values).

### Scripts
1. APP tests
	- Unit: src/orders-app/tests/Unit/BaseAppTest.php
	- Feature: src/orders-app/tests/Feature/WebOrderControllerTest.php
	- Feature: src/orders-app/tests/Feature/WebProductControllerTest.php
2. APIs tests
	- Unit: src/orders-api/tests/Unit/BaseAppTest.php
	- Feature: src/orders-api/tests/Feature/OrderControllerTest.php
	- Feature: src/orders-api/tests/Feature/ProductControllerTest.php

### Prerequisites
Before running the tests, ensure that all the required dependencies and Docker containers are running. You will need:

- Docker and Docker Compose installed.
- The necessary services (app, api, and database) running using Docker Compose.

**Make sure that you ran database migration in previous setup steps** 
```bash
docker exec -it <api-container-name> php artisan migrate:fresh
```

### Running Tests
1. **Bring Up the Docker Environment**: if the containers are not already running, use Docker Compose to start them:
```bash
docker-compose up --build
```

This will start the following services:
- app: The main application running on port 8080.
- api: The API service running on port 9005.
- db: The MySQL database running on port 3306.

2. Running Unit and Feature Tests on both backend and frontend<br>
To run the tests use the following commands:
```bash
docker exec -it <orders-app-container> php artisan test
docker exec -it <orders-api-container> php artisan test
```

Wait for the output. It would be like this:
<div align="center">
	<img src="./docs/tests_results.png">
</div>

**After test execution database is clean from test-data automatically, to get new data run again the migration and seed commands:** 
```bash
docker exec -it <api-container-name> php artisan migrate:fresh #Not mandatory
docker exec -it <api-container-name> php artisan db:seed
```

## Troubleshooting
### Containers Won't Start or Exit Immediately
1. _Check Container Logs_: to see why a container is exiting or failing to start, view the logs. Check the logs for any errors, such as missing dependencies or incorrect environment configurations :
```bash
docker-compose logs <service_name>
```

2. _Rebuild Containers_: if you've made changes to the Dockerfile or the project source code, you may need to rebuild the containers:
```bash
docker-compose up --build
```

### MySQL Database Fails to Start
1. Incorrect Environment Variables
Ensure that the environment variables for the MySQL service (db) are correctly set in your docker-compose.yml file. Ensure the environment variables are consistent and match your application’s configuration. For example:
```bash
MYSQL_DATABASE: The name of the database (default: ordersdb)
MYSQL_ROOT_PASSWORD: The root password (default: orderspassword)
MYSQL_USER: The user to create (default: admin)
MYSQL_PASSWORD: The password for the user (default: orderspassword)
```


2. Port Conflict
Check if another service is using port MYSQL_DB_PORT. If it's occupied, modify the docker-compose.yml file and change the port mapping:
```bash
ports:
  - "3307:3306"
  ```
  
Then restart the containers:
```bash
docker-compose down
docker-compose up
  ```
  
3. Persistent Volume Issues
If the MySQL container cannot start due to volume corruption, try removing the persistent volume and re-creating it.<br>
**Warning**: This will delete all data stored in the db_data volume.
```bash
docker-compose down -v
docker-compose up
  ```
  
### Application (App or API) Cannot Connect to Database
1. **Verify Network Configuration**<br>
Ensure all services are using the same network. The private network should be defined and used by all services in the docker-compose.yml file:
```bash
networks:
  private:
  ```
2. **Check Database Host**<br>
Verify that the application (either app or api) is using the correct host to connect to the database. In Docker Compose, the service name (db) should be used as the host, not localhost:
```bash
DB_HOST=db
  ```
  If you're using environment variables, ensure the correct ones are passed to the application.
  
3. **Wait for Database Initialization**<br>
The application may attempt to connect to the database before it’s ready. Add a depends_on clause in the application services to ensure the database is up before the app or API starts:
```bash
depends_on:
  - db
  ```
Alternatively, use a wait-for-it script to delay the app's startup until the database is ready.

### Unable to Access App or API on Exposed Ports
1. **Verify Port Mapping**<br>
Check that the ports are correctly mapped in docker-compose.yml. For example, the app service should map port 8080 on the host to port 8000 in the container and similarly the API service should map port 9005:
```bash
ports:
  - "8080:8000"
  
ports:
  - "9005:9005"
  ```
  
### Volume Changes Not Reflecting
If changes made to the code are not reflected in the running containers, ensure that the volumes are correctly mounted:
```bash
volumes:
  - ./src/orders-app:/var/www
  - ./src/orders-api:/var/www
  ```

Make sure the host directories (./src/orders-app and ./src/orders-api) contain the expected files.

These troubleshooting steps should cover most of the common issues encountered with the docker-compose setup for this project. If problems persist, reviewing the container logs and ensuring the configuration matches your development environment will be essential for further debugging.
