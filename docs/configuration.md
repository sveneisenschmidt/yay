# Demo #

The application is configured through environment variables, these will be provided through the `docker-compose` file during development. In product they need to be passed to either a docker container that includes the application or e.g. provided via `SetEnv` by a Apache2 web server. The application itself reads then the parameters through the [parameters.yml](../config/parameters.yml) file.

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
