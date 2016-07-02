# Magento Module Repository Creator

## What is it?

This repository holds the source code for the PHP command line application `modrepo`. `modrepo` is a tool to help cut down the amount of time spent organising the code repository for Magento modules which should be used with composer. 

## What does it do?

The tool will create a repository for the Magento module using the configured authentication details for a hosted vcs service, currently only Bitbucket is supported but Github support is forthcoming. Once the repository is created, it will clone/checkout the repo to your computer and in the current working directory, it will do the following:
 
 - Add all the files in the working directory to version control
 - Generate a modman file from those files and folders
 - Generate a composer.json file for the module

## Installation

## Quick

```sh
wget https://github.com/StudioForty9/modrepo/releases/download/1.0.0/modrepo.phar
```

Once you've downloaded the file, you need to make sure to add it to your path and grant write permission.

```sh
mv modrepo.phar /usr/local/bin/modrepo
chmod 755 /usr/local/bin/modrepo
```


## Usage


### Configuration

Before you can use the tool, you must configure it. The `install` command will start an interactive installation process, you just need to answer the questions and it will install itself into the correct directory. (In your user directory as .modrepo.yml)

```sh
modrepo install
```

### Create

To create a repository, make sure you have changed into the directory where your Magento module files are located and run the `create` command.

```sh
modrepo create module-name --private=true --description=Fancy Magento module
```

### Just modman

```sh
modrepo install
```

### Just composer

```sh
modrepo composer vendor/module --description=Lorem ipsum dolor --private=true
```


## Updating the tool

You can update the `modrepo` tool by running the `self-update` command.

```sh
modrepo self-update
```
