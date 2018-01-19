#!/bin/bash
set -e
DIR=$(dirname $0)
. $DIR/../../dist/assert.sh

# Enable demo integration
make enable-github

# Start application
make start

# Create new player
curl -X POST http://localhost:50080/api/players/ \
    -d "{\"name\": \"Jane Doe\",\"username\":\"jane.doe\",\"email\": \"jane.doe@example.org\",\"image_url\":\"https://api.adorable.io/avatars/128/354\"}"

# Perform  pullrequest-opened action 3x via webhook
for i in `seq 1 10`;
do
    curl -X POST http://localhost:50080/webhook/incoming/github/ \
        -H "X-GitHub-Event: pull_request" \
        -d @$DIR/webhook/pullrequest-opened.json
done    

# Assertions

## Activity
URL="http://localhost:50080/api/players/jane.doe/personal-activities/"

### Assert that 1x a player has been created
assert "curl -sS -X GET $URL | grep player_created | wc -l | tr -d '[:space:]'" 1

### Assert that 10x a personal action has been granted
assert "curl -sS -X GET $URL | grep personal_action_granted | wc -l | tr -d '[:space:]'" 10

### Assert that 2x a personal achievement has been granted
assert "curl -sS -X GET $URL | grep personal_achievement_granted | wc -l| tr -d '[:space:]'" 3

## Player profile
URL="http://localhost:50080/api/players/"

### Assert that player is awarded 150 points
assert "curl -sS -X GET $URL | grep '\"score\": 150' | wc -l| tr -d '[:space:]'" 1

### Assert that player is on level 3
assert "curl -sS -X GET $URL | grep '\"level\": 3' | wc -l| tr -d '[:space:]'" 1

# Print assertions
assert_end
