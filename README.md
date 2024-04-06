## Project Information/Specification
 - Using Laravel 11 (Requires at least PHP 8.2)
 - This project is running SQLite
 - Mailer is my test gmail account

## Installation
 - Clone this project `git clone https://github.com/jamesnogra/nogra-user-voucher.git`
 - Go to the directory of this project `cd nogra-user-voucher`
 - Copy the .env.example `cp .env.example .env`
 - Install packages `composer install`
 - Run the command `php artisan key:generate` to generate an app key
 - Run the migrations `php artisan migrate`
 - After the installation, run `php artisan serve`
 - In the browser, open the URL `http://127.0.0.1:8000/` to see if the application is working

## API Calls

### Create User
POST request to `http://127.0.0.1:8000/api/user/create` with payload:
```
{
    "username": "james",
    "first_name": "James",
    "email": "testemail@yahoo.com",
    "password": "123456"
}
```

### Login to Create Token
POST request to `http://127.0.0.1:8000/api/user/login` with payload:
```
{
    "username": "james",
    "password": "123456"
}
```

### Creating Vouchers
POST request to `http://127.0.0.1:8000/api/voucher/create` with payload (use the token string response from login):
```
{
    "token": "dcx1svSWIWKRsBlsdO0g14ZMZGxS41v6"
}
```


### Deleting Vouchers
POST request to `http://127.0.0.1:8000/api/voucher/delete` with payload (use the token string response from login):
```
{
    "token": "dcx1svSWIWKRsBlsdO0g14ZMZGxS41v6",
    "voucher_code": "O20LS"
}
```

### List of Vouchers by User
GET request to `http://127.0.0.1:8000/api/vouchers?token=dcx1svSWIWKRsBlsdO0g14ZMZGxS41v6` where token is from the token string response from login

## Running the test
 - Just run in the command `php artisan test`