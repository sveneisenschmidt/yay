#!/bin/bash

if [ -f docker-run.sh ]
then
    ./docker-run.sh
else
    php bin/console cache:warmup  --env=${APP_ENV};
    apache2-foreground;
fi
