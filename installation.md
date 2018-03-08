This is the installation instructions for manual setup environment and Homestead environment.

# 1. System requirements:
The system requirements listed here are based on the Laravel 5.5 documentation.
- PHP >= 7.0.0
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MySQL >= 5.0.0
- Composer
- Node >= 8.10.0 (for development only)
- Npm (for development only)

All of these requirements are satisfied by the Laravel Homestead virtual machine, so it's highly recommended that you use Homestead as your local Laravel development environment. Detailed instructions on how to install and setup Homestead can be found here (https://laravel.com/docs/5.5/homestead).

# 2. System configurations
All of the configurations have been predefined so you don't have to, all you have to do is cloning the `.env.example` containing all of the environment variables into `.env` and modify it accordingly to your environment parameters.
## 2.1. Mailing options
The current settings use mail logging as mailing mechanism. It will log all email sent from the system to Laravel logs located at `{app_root}/storage/logs/laravel.log`. To make it functioning in a real-life project, please change the `MAIL_DRIVER` in `.env` file to `smtp` with an email service of your choice.

## 2.2. Other configs
Two other settings worth mentioning are `ADMIN_EMAIL` which is the sender of all emails sending to users and receiver of emails containing applicant info. Another config is `TOKEN_EXPIRE_TIME` which dictates the period in hour(s) each token will be expired.

# 3. Installing package dependencies
This project used composer and npm to manage package installation and dependencies for php and javascript, respectively. To install the packages for Laravel, simply run `composer install`.

That's all you need for the app to run since this project shipped with all frontend codes compiled so you don't have to install npm packages and compile them yourself. In case you want to develop it further, run `npm install` to update the packages, and `npm run dev` to compile the frontend code.

# 4. Database migrations
The project source code shipped with its database migrations. After providing the correct configurations for the MySQL database to work in section 2, please run `php artisan migrate` to update the database schema. The migration not included database creation so you need to create an empty one with UTF8 support, using this query:
`CREATE DATABASE [db_name] CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`

# 5. Unit testing
This project included unit tests for data models and controllers to check the hosting environment and identify potential issues. To run the test cases, run `./vendor/bin/phpunit` in the hosting environment and check the status. All test cases passed means that the web application is ready to go.
