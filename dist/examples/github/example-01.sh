#!/bin/bash
set -e

# Enable demo integration
make github-import

# Start application
make start