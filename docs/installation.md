# Installation #

## From source

Local development is supported through `make` and powered by Docker. To see all provided commands run `make`. For installing the local development environment, clone from source and install the application first, then start it.

```bash
git clone git@github.com:sveneisenschmidt/yay.git yay
cd yay

make install
make start
```

The application responds now to API requests from `http://localhost:50800`. To see the api documentation browse to `http://localhost:50800/api/docs`.

## Own distribution (via Docker)

It is encouraged to create your own distribution based on a docker image which includes your own or third-party integrations. As a first step use the application sources and Dockerfile to create a docker image and use it via `FROM` as basis for your own `Dockerfile`.

First build a docker image from the official sources please note that this uses the stub `Dockerfile` and is not running standalone.

1\) Build a docker image based on the stub for further extension.
```bash
docker build -t sveneisenschmidt/yay .
```

2\) Create a new folder, or preferably clone a repository that contains your own Dockerfile and integrations to extend yay.

Let's assume you have a folder called 'mycompany-yay', now here a new Dockerfile that will have a custom entrypoint.
```Dockerfile
FROM sveneisenschmidt/yay

COPY ./entrypoint.sh /data/entrypoint.sh

WORKDIR /data

ENTRYPOINT ["entrypoint.sh"]
```

By providing your own entrypoint.sh it is possible to install custom integrations at startup and customize the web server used.
```bash
#!/bin/bash

php bin/console yay:integration:install integration/default --env=${APP_ENV}
php bin/console yay:integration:install integration/demo --env=${APP_ENV}

php bin/console server:run 0.0.0.0:80 --env=${APP_ENV}
```

This entrypoint file will install the `default` integration, the `demo` integration and run the php development web server on port `80`.

Now you are prepared to build your own docker image.
```bash
docker build -t mycompany/yay .
```

After the image has been build, run it with the needed configuration options. For further information ona bout how to configure the docker image please see the documentation of all [configuration](configuration.md) options.
```bash
$ docker run \
    -p 50800:80 \
    mycompany/yay \
    -e DATABASE_HOST=10.0.0.1 \
    -e DATABASE_PORT=3306 \
    -e DATABASE_NAME=yay \
    -e DATABASE_USER=db_username \
    -e DATABASE_PASSWORD=db_password \
    -e REDIS_HOST=10.0.0.2 \
    -e REDIS_PORT=6379
```
