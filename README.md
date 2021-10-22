# PHP MVC Example

This repository contains an example implementation of a set of simple tools that allow to apply the MVC pattern, and also contains a web application built using these tools.

The project contains a `Dockerfile` with instructions for building an image with `Apache` in conjunction with `PHP` and with pre-installed `Composer`, so using Docker is enough to run the application.

## Installation

To specify the values of the required environment variables, create a file `.env` in the root of the project based on the existing file `.env.example` containing a list of variables and their default values:

```sh
cp .env.example .env
```

The default ports of Apache and MySQL containers are `80` and `3306`, so to use others, you should specify the values of the `FORWARD_APP_PORT` and `FORWARD_DB_PORT` variables, for example:

```
FORWARD_APP_PORT=8080
FORWARD_DB_PORT=33060
```

For an application running in the `web` container to connect to the database from the `db` container, you must specify the `db` value for the `DB_HOST` variable.
Also, the `DB_PASSWORD` variable is **mandatory** and specifies the password that will be set for the MySQL `root` account:

```
DB_HOST=db
DB_PORT=3306
DB_DATABASE=mysql
DB_USERNAME=root
DB_PASSWORD=example
```

Build and run an application using Docker Compose:

```sh
docker-compose up -d
```

Once the containers are started, `Composer` will resolve the dependencies and install them into `vendor` automatically.
You may access the project in your web browser at http://localhost. Specify the port if a value is set for the `FORWARD_APP_PORT` variable.
