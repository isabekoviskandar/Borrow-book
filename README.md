first of all install composer 

composer install

Than install FilamentPhp 

composer require filament/filament:"^3.2" -W
 
php artisan filament:install --panels



Create user to enter admin page

php artisan make:filament-user

enter username , email and password

