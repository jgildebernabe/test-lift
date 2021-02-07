# Bodas.net TEST - Jordi Gil de Bernabé

The project is bundled into the following docker containers:

- **php-fpm**: to run the php-fpm service

## System requirements

You will need to install docker to run this project

## How to run the project

From the project root directory run:

To build the docker image:
`docker build -t jgil -f docker/php-fpm/Dockerfile . `

To run the simulator
`docker run jgil bin/console lift:simulator:run`

## Run tests

It will run phpcs and phpunit

`docker run jgil composer test`
