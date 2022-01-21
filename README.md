# Employee record keeping application
Small web application with few API endpoints created in subject BI-TWA.

## Build setup
1. Download and install [PHP](https://www.php.net/downloads) and [composer](https://getcomposer.org/download/)
2. Run following commands from root folder of the repository:
   1. Install dependencies `php composer.phar install`
   2. Setup PostgreSQL database in `.env` file
   3. Run migrations `php bin/console doctrine:migrations:migrate`
   4. Start PHP development server `php -S localhost:8080 -t public/`
3. Application will be available via [http://localhost:8080](http://localhost:8080)
