# Laravel Sanctum Authentication System

This Laravel application now has a complete Sanctum-based authentication system set up and ready to use.

## Database Configuration

- **Database**: `billdesk_db`
- **Connection**: MySQL
- **Host**: 127.0.0.1:3306
- **Username**: root
- **Password**: (empty)

## API Endpoints

### Authentication Routes

#### 1. Register User
- **URL**: `POST /api/register`
- **Body**:
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```
- **Response**:
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-09-18T06:39:01.000000Z",
        "updated_at": "2025-09-18T06:39:01.000000Z"
    },
    "access_token": "1|5oeQZOZYBSoILZoPcZNjqV4ey8nyU4ceB4j7nNV491f229c5",
    "token_type": "Bearer"
}
```

#### 2. Login User
- **URL**: `POST /api/login`
- **Body**:
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```
- **Response**:
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-09-18T06:39:01.000000Z",
        "updated_at": "2025-09-18T06:39:01.000000Z"
    },
    "access_token": "2|RFSFRdm7E6ZAet28NpFMJBH0vJF1zvQJZNuFwdUS2ee44043",
    "token_type": "Bearer"
}
```

#### 3. Get User Profile
- **URL**: `GET /api/profile`
- **Headers**: `Authorization: Bearer {access_token}`
- **Response**:
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2025-09-18T06:39:01.000000Z",
        "updated_at": "2025-09-18T06:39:01.000000Z"
    }
}
```

#### 4. Logout User
- **URL**: `POST /api/logout`
- **Headers**: `Authorization: Bearer {access_token}`
- **Response**:
```json
{
    "message": "Logout successful"
}
```

## Usage Examples

### Using cURL

#### Register a new user:
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Access protected route:
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Accept: application/json"
```

#### Logout:
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Accept: application/json"
```

### Using JavaScript/Fetch

```javascript
// Register
const registerResponse = await fetch('http://localhost:8000/api/register', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        name: 'John Doe',
        email: 'john@example.com',
        password: 'password123',
        password_confirmation: 'password123'
    })
});

const registerData = await registerResponse.json();
const token = registerData.access_token;

// Access protected route
const profileResponse = await fetch('http://localhost:8000/api/profile', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});

const profileData = await profileResponse.json();
```

## Features Implemented

✅ **User Registration** - Create new user accounts with validation
✅ **User Login** - Authenticate users with email/password
✅ **Token-based Authentication** - Secure API access with Sanctum tokens
✅ **Protected Routes** - Access user profile with authentication
✅ **User Logout** - Revoke access tokens
✅ **Password Hashing** - Secure password storage
✅ **Input Validation** - Comprehensive request validation
✅ **Error Handling** - Proper error responses

## Database Tables

The system uses the following database tables:
- `users` - User accounts
- `personal_access_tokens` - Sanctum authentication tokens
- `migrations` - Laravel migration tracking

## Security Features

- **Password Hashing**: All passwords are securely hashed using Laravel's built-in hashing
- **Token-based Authentication**: Secure API access without session dependencies
- **Input Validation**: All inputs are validated before processing
- **CSRF Protection**: Built-in CSRF protection for web routes
- **CORS Support**: Configured for cross-origin requests

## Running the Application

1. Start the Laravel development server:
```bash
php artisan serve
```

2. The API will be available at: `http://localhost:8000/api/`

3. Test the endpoints using the examples above or any HTTP client like Postman.

## Next Steps

You can now:
- Build frontend applications that consume these APIs
- Add more protected routes by using the `auth:sanctum` middleware
- Extend the User model with additional fields
- Add role-based permissions
- Implement email verification
- Add password reset functionality
