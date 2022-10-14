![This is an image](https://oshatechnology.com/static/media/oranye.9f948cc76edf88d2a4fc.png)

# **Human Resource Information System**
- [Installation](https://github.com/OSHATechnology/BE_HRIS/tree/develop#installation)
- [Api Referance](https://github.com/OSHATechnology/BE_HRIS/tree/develop#api-reference)
  - [login](https://github.com/OSHATechnology/BE_HRIS/tree/develop#login-user-api)
  - [Employee](https://github.com/OSHATechnology/BE_HRIS/tree/develop#login-user-api)
    - [Show All](https://github.com/OSHATechnology/BE_HRIS/tree/develop#show-all-employee) 
    - [Create](https://github.com/OSHATechnology/BE_HRIS/tree/develop#create-employee)
    - [show](https://github.com/OSHATechnology/BE_HRIS/tree/develop#show-employee-by_id)
    - [Delete](https://github.com/OSHATechnology/BE_HRIS/tree/develop#delete-employee)
    - [Soft Delete trash](https://github.com/OSHATechnology/BE_HRIS/tree/develop#soft-delete-trash-employee)
    - [Soft Delete restore](https://github.com/OSHATechnology/BE_HRIS/tree/develop#soft-delete-restore-employee)

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
**Important!!!**
if you want to see json response you can install [postman](https://www.postman.com/). If you have logged in, don't forget to enter the token in the postman token bearer at each endpoint.

### Login User API

##### Endpoint
```
POST api/auth/login
```

##### Body request example
```json
{
  "email": "example@email.com",
  "password": "password"
}
```

### Employee API

#### show all employee
##### Endpoint
```
GET api/employee
```

#### create employee
##### Endpoint
```
POST api/employee
```
##### Body request example
```json
{
  "firstName": "John",
  "lastName": "Doe",
  "phone": "081212121212",
  "email": "user@email.com",
  "password": "password",
  "photo": "john.jpg",
  "gender": "man",
  "birthDate": "1999-10-21",
  "address": "address",
  "city": "Jakarta",
  "nation": "Indonesia",
  "roleId": 1,
  "isActive": 1,
  "emailVerifiedAt": "2022-10-14",
  "rememberToken": "-",
  "joinedAt": "2022-10-14",
  "resignedAt": "2022-10-14",
  "statusHireId": 1,
}
```

#### show employee by id
##### Endpoint
```
GET api/employee/{id}
```

#### update employee
##### Endpoint
```
PUT api/employee/{id}
```
##### Body request example
```json
{
  "firstName": "Charles",
  "lastName": "Doe",
  "phone": "081212121212",
  "email": "user@email.com",
  "password": "password",
  "photo": "john.jpg",
  "gender": "man",
  "birthDate": "1999-10-21",
  "address": "address",
  "city": "Jakarta",
  "nation": "Indonesia",
  "roleId": 1,
  "isActive": 1,
  "emailVerifiedAt": "2022-10-14",
  "rememberToken": "-",
  "joinedAt": "2022-10-14",
  "resignedAt": "2022-10-14",
  "statusHireId": 1,
}
```

#### Delete employee
##### Endpoint
```
DELETE api/employee/{id}
```

#### Soft delete trash employee
##### Endpoint
```
GET api/employee/trash
```

#### Soft delete restrore employee
##### Endpoint
```
GET api/employee/restrore
```
