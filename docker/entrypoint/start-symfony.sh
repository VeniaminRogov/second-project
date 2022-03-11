composer install

# setup database
php bin/console doctrine:database:create --if-not-exists > /dev/null 2>&1 &&
php bin/console doctrine:migrations:migrate > /dev/null 2>&1 &&
echo "[SYMFONY SETUP SUCCESS]" && php bin/console doctrine:fixtures:load --append 

php-fpm
