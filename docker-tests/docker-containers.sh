#!/bin/sh

docker-compose up --build -d
docker exec -i phpredis-json bash < ./docker-tests/docker-tests.sh
docker stop redislab-rejson
docker stop phpredis-json