#!/bin/bash
set -e

function username {
    echo -n $1 | md5| cut -c 1-8
}

function email {
    echo "${1}@trivago.com"
}

function image {
    echo "https://api.adorable.io/avatars/128/${RANDOM}"
}
# Enable demo integration
make enable-demo

# Start application
make start

# Available Players
PLAYERS=("Jane Doe" "Alex Doe" "John Doe" "Paul Winter" "Dieter Meier" "Jack Snowden" "Claire Miller" "Moe Sisleck" "Charles Burns" "Carl Carlson")

# Create players
for i in "${PLAYERS[@]}"
do
    NAME=$i
    USERNAME=`username "${i}"`
    EMAIL=`email "${USERNAME}"`
    IMAGE=`image`
    curl -X POST http://localhost:50080/api/players/ \
        -d "{\"name\": \"${NAME}\",\"username\":\"${USERNAME}\",\"email\": \"${EMAIL}\",\"image_url\":\"${IMAGE}\"}"
done

# Seed Actions
for i in "${PLAYERS[@]}"
do
    USERNAME=`username "${i}"`
    COUNT=`echo $RANDOM % 33 + 1 | bc`

    for i in `seq 1 $COUNT`;
    do
        curl -X POST http://localhost:50080/api/progress/ \
            -d "{\"username\":\"${USERNAME}\",\"actions\":[\"demo-action-01\"]}"
    done
done



