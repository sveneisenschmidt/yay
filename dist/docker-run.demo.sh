#!/bin/bash

php bin/console doctrine:schema:drop --force --em=default --env=${APP_ENV}
php bin/console doctrine:schema:create --em=default --env=${APP_ENV}
php bin/console yay:integration:enable default integration/default --env=${APP_ENV}
php bin/console yay:integration:enable demo integration/demo --env=${APP_ENV}
php bin/console server:run 0.0.0.0:80 --env=${APP_ENV}