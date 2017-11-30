#!/bin/bash
set -e

# Enable demo integration
make enable-github

# Start application
make start

# Get available actions
curl -X "GET" http://localhost:50080/api/actions/

# Create new player
curl -X "POST" http://localhost:50080/api/players/ \
    -d "{\"name\": \"Jane Doe\",\"username\":\"jane.doe\",\"email\": \"jane.doe@example.org\",\"image_url\":\"https://api.adorable.io/avatars/128/354\"}"

# Perform demo action 10x
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"actions\":[\"pullrequest-opened\",\"pullrequest-opened\",\"pullrequest-opened\",\"pullrequest-opened\",\"pullrequest-opened\",\"pullrequest-opened\"]}"

# Get player activity
curl -X "GET" http://localhost:50080/api/players/jane.doe/personal-activities/