#!/bin/sh

docker-compose up --build -d

yel=$'\e[1;33m'
blu=$'\e[1;34m'
end=$'\e[0m'

printf "\n${blu}*** Running test against Redis 4 docker service ***${end}\n\n"
./vendor/bin/phpunit --configuration phpunit_redis_4x0.xml

printf "\n${yel}*** Running test against Redis 5 docker service ***${end}\n\n"
./vendor/bin/phpunit --configuration phpunit_redis_5x0.xml

docker stop phpredis-json4
docker stop phpredis-json5
