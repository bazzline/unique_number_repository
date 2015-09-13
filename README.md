# Unique Number Repository Webservice

Sometimes, you simple need to make sure you can fetch a unique number for a given problem scope. 
To easy things up, this project will create a rest based webservice as backend and a command line frontend to support you solving this problem.

## [REST Based Backend (Server)](https://github.com/bazzline/unique_number_repository/blob/master/bootstrap/server.php)

* endpoint s
    * "/unique-number-repository"
        * DELETE: deleted a repository and all its unique numbers
        * GET: returns list of all repository names
        * PUT: creates a new repository
    * "/unique-number-repository/{name}"
        * DELETE: deletes a number from this repository
        * GET: returns list of all numbers from this repository
        * PUT: creates a new number for this repository

## [Command Line Based Frontend (Client)](https://github.com/bazzline/unique_number_repository/blob/master/client)

* commands
    * [create-repository](https://github.com/bazzline/unique_number_repository/blob/master/client/create-repository) <host> <applicant name> <repository name>
    * [list-repositories](https://github.com/bazzline/unique_number_repository/blob/master/client/list-repositories) <host>
    * [delete-repository](https://github.com/bazzline/unique_number_repository/blob/master/client/delete-repository) <host> <repository name>
    * [create-unique_number](https://github.com/bazzline/unique_number_repository/blob/master/client/create-unique_number) <host> <applicant name> <repository name>
    * [list-unique_number](https://github.com/bazzline/unique_number_repository/blob/master/client/list-unique_number) <host> <repository name>
    * [delete-unique_number](https://github.com/bazzline/unique_number_repository/blob/master/client/delete-unique_number) <host> <repository name> <number>
* you can use this as base to implement your own logic 
    * a [basic example](https://github.com/bazzline/unique_number_repository/blob/master/example/basic_example) with "pre_execution_hook" and "post_execution_hook" will be shipped

# Install

## By Hand

    mkdir -p vendor/net_bazzline/unique_number_repository
    cd vendor/net_bazzline/unique_number_repository
    git clone https://github.com/bazzline/unique_number_repository .

## With [Packagist](https://packagist.org/packages/net_bazzline/unique_number_repository)

    composer require net_bazzline/unique_number_repository:dev-master

## Configure

    cd <project root>/configuration
    cp client.local.php.dist client.local.php
    #adapt client.local.php
    cp server.local.php.dist server.local.php
    #adapt server.local.php

# Benefits

* 
* implement token authentication (you can and should override the existing token by creating your own *client.local.php* and *server.local.php* in *configuration*)

# Thanks

* [the silex startup](http://sleep-er.co.uk/blog/2013/Creating-a-simple-REST-application-with-Silex/) - thank you man, this guide is super cool
* [silex simple rest](https://github.com/vesparny/silex-simple-rest/blob/master/src/app.php)
* [designing rest api with silex](https://speakerdeck.com/hhamon/designing-rest-api-with-silex)
* [silex documentation](http://silex.sensiolabs.org/documentation)

# History

* upcomming
    * @todo
        * multiple storage (databases) are supported (right now, [file storage](https://github.com/bazzline/php_component_database_file_storage) is supported)
* [0.10.1](https://github.com/bazzline/unique_number_repository/tree/0.10.1) - released at 13.09.2015
    * fixed authentication problem
* [0.10.0](https://github.com/bazzline/unique_number_repository/tree/0.10.0) - released at 13.09.2015
    * downgraded to silex 1.2 to support php 5.3.3
    * fixed broken links
    * increased compatibility by using '/usr/bin/env' for client code
    * removed unused dependencie
* [0.9.0](https://github.com/bazzline/unique_number_repository/tree/0.9.0) - released at 12.09.2015
    * silix based server/backend
