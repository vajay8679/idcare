#!/bin/sh

# Set compose files
FILES="-f docker-compose.yml -f docker-compose.db.yml -f docker-compose.pma.yml"

docker-compose ${FILES} stop