# Bodas.net TEST - Jordi Gil de Bernabé

The project is bundled into the following docker containers:

- **php-fpm**: to run the php-fpm service

## System requirements

You will need to install docker to run this project

## How to run the project

From the project root directory run:

`docker build -t jgil -f docker/php-fpm/Dockerfile . `

`docker run jgil bin/console lift:simulator:run`
