#!/bin/bash

if [ "${APP_MODE}" == "install" ]
then
    php bin/console doctrine:schema:drop --force --em=default --env=${APP_ENV};
    php bin/console doctrine:schema:create --em=default --env=${APP_ENV};
    php bin/console yay:integration:enable default integration/default --env=${APP_ENV};
    php bin/console yay:integration:enable demo integration/demo --env=${APP_ENV};
    while sleep 3600; do :; done;
fi

if [ "${APP_MODE}" == "web" ]
then
    php bin/console yay:integration:enable default integration/default --config-only --env=${APP_ENV};
    php bin/console yay:integration:enable demo integration/demo --config-only --env=${APP_ENV};
    apache2-foreground
fi
