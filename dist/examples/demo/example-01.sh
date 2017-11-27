#!/bin/bash
set -e

# Enable demo integration
make enable-demo

# Start application
make start

# Create new player
curl -X "POST" http://localhost:50080/api/players/ \
    -d "{\"name\": \"Jane Doe\",\"username\":\"jane.doe\",\"email\": \"jane.doe@example.org\",\"image_url\":\"https://api.adorable.io/avatars/128/354\"}"

# Perform demo action 5x
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"actions\":[\"demo-action\",\"demo-action\",\"demo-action\",\"demo-action\",\"demo-action\"]}"

# Get player activity
curl -X "GET" http://localhost:50080/api/players/jane.doe/personal-activities/