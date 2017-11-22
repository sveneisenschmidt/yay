#!/bin/bash

if [ -f docker-run.sh ]
then
    ./docker-run.sh
else 
    echo "docker-run.sh not found"
fi
