#!/bin/sh

yel=$'\e[1;33m'
blu=$'\e[1;34m'
end=$'\e[0m'

printf "\n${blu}*** Running test against Redis 4 docker service ***${end}\n\n"
./vendor/bin/phpunit --configuration ./docker-tests/phpunit_docker.xml
