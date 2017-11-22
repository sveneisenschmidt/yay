#!/bin/bash

if [ -f docker-run.sh ]
then
    ./docker-run.sh
else 
    apache2-foreground
fi
