## Installation
 - Clone this project `git clone https://github.com/jamesnogra/nogra-user-voucher.git`
 - Go to the directory of this project `cd nogra-user-voucher`
 - Install packages `composer install`
 - After the installation, run `php artisan serve`
 - In the browser, open the URL `http://127.0.0.1:8000/`

## API Calls

### Create User
Post request to `http://127.0.0.1:8000/api/user/create` with payload:
```
{
    "username": "james",
    "first_name": "James",
    "email": "testemail@yahoo.com",
    "password": "123456"
}
```

### Login to Create Token
Post request to `http://127.0.0.1:8000/api/user/login` with payload:
```
{
    "username": "james",
    "password": "123456"
}
```

### Creating vouchers
Post request to `http://127.0.0.1:8000/api/voucher/create` with payload (use the token string response from login):
```
{
    "token": "dcx1svSWIWKRsBlsdO0g14ZMZGxS41v6"
}
```

## Some Other Information
 - Using Laravel 11 (Requires at least PHP 8.2)
 - This project is running sqlite
 - Mailer is my test gmail account