# REST API with JWT Authentication

A secure REST API built with Core PHP, featuring JWT authentication, MVC architecture, and Role-Based Access Control (RBAC) principles.

## Features
- **MVC Architecture**: Clean separation of concerns.
- **JWT Authentication**: Secure token-based access.
- **Middleware**: JSON input handling and Auth protection.
- **Security**: Password hashing, protected routes, .env configuration.

## Setup

1.  **Clone the repository** to your server root (e.g., `c:\wamp64\www\REST_API_with_JWTAuthentication`).
2.  **Database Setup**:
    - Create a database named `api_auth_db`.
    - Import the following schema:
    ```sql
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE patients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        age INT NOT NULL,
        gender VARCHAR(50),
        phone VARCHAR(20),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ```
3.  **Configuration**:
    - Rename `.env.example` to `.env` (if applicable) or ensure `.env` exists with:
    ```ini
    DB_HOST=localhost
    DB_NAME=api_auth_db
    DB_USER=root
    DB_PASS=
    JWT_SECRET=your_secret_key
    JWT_EXPIRY=3600
    ```
4.  **Usage**:
    - **Register**: `POST /api/register`
    - **Login**: `POST /api/login`
    - **Get Patients**: `GET /api/patients` (Requires Bearer Token)

## API Testing
Use Postman or Thunder Client to test endpoints. Ensure `Content-Type: application/json` is set for POST requests.
