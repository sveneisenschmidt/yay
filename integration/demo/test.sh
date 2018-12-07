#!/bin/bash
set -e
DIR=$(dirname $0)
. $DIR/../../dist/assert.sh

# Enable demo integration
make enable-demo

# Start application
make start

# Create new player
curl -X POST http://localhost:50080/api/players/ \
    -d "{\"name\": \"Alex Doe\",\"username\":\"alex.doe\",\"email\": \"alex.doe@example.org\",\"image_url\":\"https://avatars.dicebear.com/v2/female/354.svg\"}"

# Perform demo action 5x via api
curl -X POST http://localhost:50080/api/progress/ \
    -d "{\"username\":\"alex.doe\",\"actions\":[\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\"]}"

# Perform demo action 5x via webhook
for i in `seq 1 5`;
do
    curl -X POST "http://localhost:50080/webhook/incoming/demo/" \
        -F "username=third_party.demo_user" \
        -F "action=third_party.demo_action" 
done    

# Assertions

## Activity
URL="http://localhost:50080/api/players/alex.doe/personal-activities/"

### Assert that 1x a player has been created
assert "curl -sS -X GET $URL | grep player_created | wc -l | tr -d '[:space:]'" 1

### Assert that 10x a personal action has been granted
assert "curl -sS -X GET $URL | grep personal_action_granted | wc -l | tr -d '[:space:]'" 10

### Assert that 2x a personal achievement has been granted
assert "curl -sS -X GET $URL | grep personal_achievement_granted | wc -l| tr -d '[:space:]'" 2

# Print assertions
assert_end
