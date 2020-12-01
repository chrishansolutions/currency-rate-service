# Currency rate service #


### Project requirements:

----
you will need to make sure your server meets the following requirements for Laravel 8.16 version:

* PHP >= 7.3
* BCMath PHP Extension
* Ctype PHP Extension
* Fileinfo PHP Extension
* JSON PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension



### Project setup:

1. Clone repository and move into project directory
2. Install dependencies 
```
composer install
```
3. Copy .env.example file, paste on the root folder and rename it to .env. You can type `copy .env.example .env` if using command prompt Windows or `cp .env.example .env` if using terminal, Ubuntu
4. Open your .env file and update DB configuration.  
    
5. Run for generate key
```
php artisan key:generate
```
6. Run for migrations 
```
php artisan migrate
```
7. Configure below directories permission

```
sudo chmod 775 storage
```
```
sudo chmod 775 bootstrap/cache
```

----
### Console commands for Creating a new user & Importing currency rates:

1. For create new user. This command will ask user name and password.
```
php artisan command:createUser
```

1. For Import currency rates.
```
php artisan command:importCurrencyRates
```
----
