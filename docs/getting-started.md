[Table of Contents](README.md) | **Getting Started** | [Customisation](customisation.md) | [Examples](examples.md) | [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)



---

# Getting Started

* [Requirements](getting-started.md#requirements)
* [Installation](getting-started.md#installation)
* [Configuration](getting-started.md#configuration)
* [Usage / API](getting-started.md#usage--api)

---

## Requirements

- Docker 17.04+
- GNU make compatible environment (Linux, macOS, Windows \w CygWIN, Windows Subsystem for Linux)

---

## Installation

### Local installation (Recommended for trying Yay! out)

Local development is supported through `make` and powered by Docker. Please see the [Contributing](contributing.md) documentation to start developing locally.

### Own distribution (via Docker)

__NOTE__: Ready-to-use Docker images `sveneisenschmidt/yay` and `sveneisenschmidt/yay-demo` are built automatically via Travis CI ([.travis.yml](../.travis.yml)).

It is encouraged to create your own distribution based on a docker image which includes your own or third-party integrations. As a first step use the application sources and Dockerfile to create a docker image and use it via `FROM` as basis for your own `Dockerfile`.

Create a new folder, or preferably clone a repository that contains your own Dockerfile and integrations to extend Yay.

Let's assume you have a folder called 'mycompany-yay', it will hold our Dockerfile that will have a custom command. The default docker image has a default `CMD` that will load a `docker-run.sh` file from the `root` folder if present.

```Dockerfile
FROM sveneisenschmidt/yay:stable

# Bake your custom integration into the image
COPY ./integration/mycompany.yml ./data/integration/mycompany.yml

# Bake your custom run script into the image
# Example: dist/docker-run.demo.sh
#
COPY ./mycompany-docker-run.sh docker-run.sh
```

By providing your own `docker-run.sh` it is possible to install custom integrations at startup and customise the web server used. With this approach even more sophisticated installation routines are possible. Try it out!

```bash
#!/bin/bash

php bin/console yay:integration:enable default integration/default --env=${APP_ENV}
php bin/console yay:integration:enable demo integration/demo --env=${APP_ENV}

apache2-foreground
```

The script will install the `default` integration, the `demo` integration and run the web server on port `80`.

Now you are prepared to build your own docker image.
```bash
docker build -t mycompany/yay .
```

After the image has been build, run it with the needed configuration options. For further information about how to configure the docker image please see the documentation of all [configuration](configuration.md) options.
```bash
$ docker run \
    -p 50800:80 \
    mycompany/yay \
    -e APP_ENV=prod \
    -e DATABASE_HOST=10.0.0.1 \
    -e DATABASE_PORT=3306 \
    -e DATABASE_NAME=yay \
    -e DATABASE_USER=db_username \
    -e DATABASE_PASSWORD=db_password \
    -e REDIS_HOST=10.0.0.2 \
    -e REDIS_PORT=6379 \
    -e MAILER_URL=smtp://localhost:25
```

---

## Configuration

The application is configured through environment variables, these will be provided through the `docker-compose.yml` file during development. In production they need to be passed to the running container that includes the application. e.g. via SetEnv by an Apache2 web server. The application itself reads then the parameters through the [parameters.yml](../config/parameters.yml) file.

| Variable | Default Value | Description |
|---|---|---|
| APP_ENV | dev | Application environment, used inside Docker. |
| DATABASE_HOST | | IP or hostname of database. |
| DATABASE_PORT | 3306 | Port of the host where the database is running on. |
| DATABASE_NAME | | Name of database. |
| DATABASE_USER | | Username of database user. |
| DATABASE_PASSWORD | | Password of database user |
| REDIS_HOST | | IP or hostname of redis server. |
| REDIS_PORT | | Port of the host where the redis server is running on. |
| MAILER_URL | | The dsn of the mail service to use. (e.g. smtp://localhost:25) |

---

## Usage / API

```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"action\":\"example-action\"}"

[
    {
        "name": "example-achievement-01",
        "label": "example-achievement-01",
        "description": "example-achievement-01",
        "points": 50,
        "achieved_at": "2017-10-08T13:22:08+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/example-achievement-01/"
        }
    }
]
```

Automatically-generated API documentation can be found at [`http://localhost:50080/api/doc`](http://localhost:50080/api/doc), run it with `make start`. The latest stable version [`https://yay-demo.sloppy.zone/api/doc`](https://yay-demo.sloppy.zone/api/doc) is available via `sloppy.io`.

Additionally Yay supports integrating third party applications via webhooks. Yay ships a basic Github webhook integration, more platforms will follow soon. Until then you can follow the webhook guide "[Under The Hood: Webhooks](under-the-hood.md#webhooks)" or write and contribute your own webhooks/processors, yay!

```yml
integration:
    webhooks:
        incoming_processors:
            example-processor:
                type: chain
                arguments:
                    - [ example-github, example-users ]
            example-github:
                type: class
                class: Yay\ThirdParty\Github\Webhook\Incoming\Processor\GithubProcessor
            example-users:
                type: static-map
                arguments:
                    - username
                    -
                        octocat: jane.doe
```
