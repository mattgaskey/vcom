# Monolith / Fractal

The Monolith / Fractal pattern library is part of NewCity's _Foundation First_ approach. It is a front-end framework designed to bootstrap new projects. It is based on [Fractal v1](https://github.com/frctl/fractal).

## Requirements

You will need to know how to use docker and git. Likewise, you will need docker and git.

## Starting a new project

To start a new project you should copy this repository as it currently exists. Replace the `.gitlab-ci.yml` file with `pages-gitlab-ci.yml` to deploy to a Gitlab Pages instance. You may then also delete the `.*` targets in the Makefile.

## Local development

To get started, type `make` in a terminal window. This will fire up the Bluestone docker container and run it through http://localhost:3000/. To access the Browser-sync control panel, visit http://localhost:3002.

### Make documentation

We use make instead of docker-compose directly because we have a different compose file for development and deployment and the command line can get a little hairy.

* `make` will start the docker container running in detached mode. (So will `make up` and `make dev`).
* `make logs` will attach to the docker container's logs. If you get a prompt back from running this it means the container has died -- usually because of an error somewhere in the build stack.
* `make down` will stop the container.
* `make demo` will start the container, run the build script used at deployment, then shut down the container.
* `make track` will start the container in detached mode, then start showing the logs.
* `make restart` will shut down the container as though you had used `make down` and then start up the container as though you had used `make track`.

This README file is added to the Fractal documentation directory when development and build scripts run but is not updated on change the way everything else is. If you really want to see your changes, restart your dev instance with `make dev` or `make restart`.

### Extending Twig (Filters, Functions, etc)

Twig extensions are auto-loaded from `/monolith/twig_extensions/`. You can probably find an example there. If you need to add node libraries, update the `package.json` file in the project root. This file is *merged* with the `package.json` file in the docker container. I don't know what happens in case of a conflict, I am sure we will find out.

You don't need to install the module for any reason other than convenience; likewise you do not need to keep the node_modules directory around if you do install it.

To load twig extensions or install npm modules you will need to restart the docker container. NPM modules will be installed on a Docker volume so they persist between instances, saving a bit of startup time.

## Committing

When you commit to the master branch, a new version of the pattern library will be built and deployed to https://newcity.github.io/monolith-fractal/.

## Linters

There are several lint configuration files provided. These will work with your  text editor if you have the proper plugins to read them. Javascript and SCSS linters also run as part of the CI pipeline. These linters are:

### eslint `.eslint.yml`

This is the Javascript linter. It lints against ES6 syntax.

These rules are disabled:

* `no-undef`: disabled because we often assume the presence of libraries without explicitly including them as a dependency. (We probably shouldn't do that.)
* `no-unused-vars`: disabled because we sometimes define variables that will be used in other files that have the current one as an implicit dependency. (We probably also shouldn't do that.)

### sass-lint `.sass-lint.yml`

These rules are disabled:

* `force-element-nesting`: We do want to provide some defaults for base elements.
* `force-attribute-nesting`: In some cases we want to target specific attributes regardless of class.
* `mixins-before-declarations`: Some of our mix-ins are for responsive design and we like to have that later.
* `nesting-depth`: We are not worrying too much about nesting depth, although we perhaps should.

This rule will cause a build to fail if it is violated:

* `no-extends`: Use a mix-in instead.

### markdownlint `.markdownlint.yaml`

The editor plugin requires the long form of the file name extension.

These rules are disabled:

`first-line-h1`: Fractal generally uses the file name as the first header, so `providing an `h1` is actually redundant.
`first-heading-h1`: Given the above, we probably want to start our headings with `h2`
`line-length`: I (John) hate hard line wraps. Fight me.
`no-bare-urls`: Sometimes it is more clean and informative in documentation to just provide the bare URL.

## Configuring Fractal

You can pass default project configuration to fractal by editing the `fractal.config.js` file in the root directory. For more information on what you can configure, see [Fractal|Project Settings](https://fractal.build/guide/project-settings). Because this configuration file is loaded and executed by gulp (instead of loaded the traditional way), configuration is wrapped in a module. Don't monkey with the module syntax.
