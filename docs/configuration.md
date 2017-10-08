# Demo #

The application is configured through environment variables, these will be provided through the `docker-compose` file during development and need to be passed as environment variables to an docker docker container that runs the an container of the applicaiton.

| Variable | Default Value | Description |
|---|---|---|
| APP_ENV | | Application environment, used inside Docker. |
| DATABASE_HOST | | IP or hostname of database. |
| DATABASE_PORT | 3306 | Port of the host where the database is running on. |
| DATABASE_NAME | | Name of database. |
| DATABASE_USER | | Username of database user. |
| DATABASE_PASSWORD | | Password of database user |
| REDIS_HOST | | IP or hostname of redis server. |
| REDIS_PORT | | Port of the host where the redis server is running on. |
