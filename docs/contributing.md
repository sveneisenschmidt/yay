[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customization](customization.md) | [Examples](examples.md) | [Under The Hood](docs/under-the-hood.md) | **Contributing**


---

# Contributing

* [Setup](contributing.md#setup)
* [Test](contributing.md#test)
* [Commit](contributing.md#commit)
* [Pull Request](contributing.md#pull-request)

Hint: Inspired by the amazing [`goreleaser`](https://github.com/goreleaser/goreleaser/tree/master/docs) documentation.

---


## Setup your machine

`yay` is written in [PHP](https://php.net/).

Prerequisites:

* `make`
* [Docker](https://www.docker.com/) (v17.03+)

Clone `yay` from source into any directory:

```bash
$ git clone git@github.com:sveneisenschmidt/yay.git yay
$ cd yay
```

Install the build and application dependencies:

```console
$ make install
```

A good way of making sure everything is all right is running the test suite:

```console
$ make test
```
Run the application with `make start`. The application will then respond to request at `http://localhost:50080`.

```console
$ make start
# ...
# http://localhost:50080
```

To see all provided commands run `make` and a list of available target is rendered.

```bash
$ make
#    start                Start the application
#    stop                 Stop the application
#    restart              Restart the application
#    build                Build the application including development environment
#    clean                Cleans cache files, logs
#    cleanall             Cleans containers, images and project files including caches, logs
#    install              Install and build the application including development environment
#    test                 Run application tests
#    test-coverage        Run application tests and generate code coverage
#    shell                Start an interactive shell session
#    default-publish      Publish demo docker image to sveneisenschmidt/yay
#    enable-demo          Import demo data
#    disable-demo         Remove demo data
#    enable-github        Import demo data
#    disable-github       Remove demo data
#    demo-publish         Publish demo docker image to sveneisenschmidt/yay-demo
#    watch-logs           Watch all log files
#    watch-redis          Watch all redis queries
```

## Test your change

You can create a branch for your changes and try to build from the source as you go:

```console
$ make build
```

When you are satisfied with the changes, we suggest you run:

```console
$ make test
```

Which runs all the tests.

## Create a commit

Commit messages should be well formatted.
Start your commit message with the type. Choose one of the following:
`feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `chore`, `revert`, `add`, `remove`, `move`, `bump`, `update`, `release`

After a colon, you should give the message a title, starting with uppercase and ending without a dot.
Keep the width of the text at 72 chars.
The title must be followed with a newline, then a more detailed description.

Please reference any GitHub issues on the last line of the commit message (e.g. `See #123`, `Closes #123`, `Fixes #123`).

An example:

```
docs: Improve documentation

[x] Restructure documentation
[x] Add developer documentation #26
[x] Add command documentation #104
[ ] Add custom documentation for API endpoints, filters, pagination and ordering

Closes #133
```

## Submit a pull request

Push your branch to your `yay` fork and open a pull request against the
master branch.
