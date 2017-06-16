# M2IF - Magento 2 Console Extension

[![Latest Stable Version](https://img.shields.io/packagist/v/techdivision/import-cli-magento.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-cli-magento) 
 [![Total Downloads](https://img.shields.io/packagist/dt/techdivision/import-cli-magento.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-cli-magento)
 [![License](https://img.shields.io/packagist/l/techdivision/import-cli-magento.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-cli-magento)
 [![Build Status](https://img.shields.io/travis/techdivision/import-cli-magento/master.svg?style=flat-square)](http://travis-ci.org/techdivision/import-cli-magento)
 [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/techdivision/import-cli-magento/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-cli-magento/?branch=master) 
 [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/techdivision/import-cli-magento/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-cli-magento/?branch=master)

## Development

To make the development process as easy as possible, we've added some basic [Robo](http://robo.li) commands.

### General Configuration

Robo supports a configuration file `robo.yml`, that has to be located in the root directory of the extension.
By default, the file contains the following default configurations, that probably have to be customized to
your needs

```yml
dirs:
    deploy: /opt/appserver/webapps/magento2-ce-2.1.7

docker:
    target-container: appserver-1.1.4-magento
    dirs:
        deploy: /opt/appserver/webapps/magento2-ce-2.1.7
        src:    /root/Workspace/import-cli-magento/src
        dest:   webapps/magento2-ce-2.1.7
```

### Sync Sources

To synchronize your local sources with the Magento 2 instance inside the Docker container, simply execute

```sh
$ vendor/bin/robo docker:sync
```

### Execute Magento Commands

Another command allows you to invoke the magento script inside the Magento root directory of the docker container. Simply pass
the Magento command, prefixed with a `--`, e. g.

```sh
$ vendor/bin/robo docker:magento -- setup:upgrade
```

### Execute Composer Commands

Same works for composer. Simply pass the command and the arguments/options, prefixed with a `--` e. g.

```sh
$ vendor/bin/robo docker:composer -- update --no-dev
```

## Installation

The general development process is optimized to work with Docker. Therefore we'll assume a running Docker container with 
a working Magento 2 instance inside. If not available or you're not sure how to setup one, have a look at the appserver.io 
[tutorial](http://appserver.io/get-started/tutorials/running-magento2-in-an-appserver-io-docker-container.html).

### Option 1 - Using the Robo command

```sh
$ vendor/bin/robo docker:composer require techdivision/import-cli-magento:dev-master
```

This is the preferred option.

### Option 2 - Direct Installation from inside the container

Open a shell in your Docker container

```sh
$ docker exec -ti appserver-1.1.4-magento bash
```

Then use your favorite editor, e. g. vim, to open the composer.json of your Magento 2 installation and add the extension to 
the required dependencies like

```json
{
    ...
    "require": {
        ...
        "techdivision/import-cli-magento": "dev-master"
    }
    ...
}
```

and finish the installation by updating composer on the commandline with

```sh
$ composer update
```

The extension should now be installed ready for development.