## Installation
 - Clone this project `git clone https://github.com/jamesnogra/nogra-user-voucher.git`
 - Go to the directory of this project `cd nogra-user-voucher`
 - Install packages `composer install`
 - After the installation, run `php artisan serve`
 - In the browser, open the URL `http://127.0.0.1:8000/`

## API Calls

### Create User
Post request to `http://127.0.0.1:8000/api/users` with payload:
```
{
    "username": "james",
    "first_name": "James",
    "email": "john@example.com",
    "password": "password123"
}
```

## Some Other Information
 - Using Laravel 11
 - This project is running sqlite
 - Mailer is my test gmail account