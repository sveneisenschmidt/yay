#!/bin/bash
set -e
DIR=$(dirname $0)

# Enable demo integration
make enable-github

# Start application
make start

# Create new player
curl -X "POST" http://localhost:50080/api/players/ \
    -d "{\"name\": \"Jane Doe\",\"username\":\"jane.doe\",\"email\": \"jane.doe@example.org\",\"image_url\":\"https://api.adorable.io/avatars/128/354\"}"

# Perform 1x pullrequest-opened action 
curl -X "POST" http://localhost:50080/webhook/incoming/github/ \
    -H "X-GitHub-Event: pull_request" \
    -d @$DIR/webhook/pullrequest-opened.json

# Get player activity
curl -X "GET" http://localhost:50080/api/players/jane.doe/personal-activities/
