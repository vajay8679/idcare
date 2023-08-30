#!/bin/sh

# Set compose files
FILES="-f docker-compose.yml -f docker-compose.db.yml -f docker-compose.pma.yml"

# Build images
if [ -n "$BUILD" ] && [ "$BUILD" = true ]
then
    docker-compose build idcare-server
fi

# Try to stop / down previous containers
./stop.sh

# Start containers
docker-compose ${FILES} up -d

# Show running containers
docker ps