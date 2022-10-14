![This is an image](https://oshatechnology.com/static/media/oranye.9f948cc76edf88d2a4fc.png)

# **Human Resource Information System**
- [Installation](https://github.com/OSHATechnology/BE_HRIS/tree/develop#installation)
- [Api Referance](https://github.com/OSHATechnology/BE_HRIS/tree/develop#api-reference)
  - [login](https://github.com/OSHATechnology/BE_HRIS/tree/develop#login-user-api)

##   **Installation**

1. Clone project
```
git clone https://github.com/OSHATechnology/BE_HRIS.git
```

2. Install Package
```
composer install
```

3. Setting database in `.env`

4. Key generate
```
php artisan key:generate
```

5. Run migration and seeder
```
php artisan migrate --seed
```

## **API Reference**
if you want to see json response you can install [postman](https://www.postman.com/)

### Login User API

#### Endpoint
```
POST api/auth/login
```

#### Body request example
```json
{
  "email": "example@email.com",
  "password": "password"
}
```
