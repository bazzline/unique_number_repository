# Full Stop

I still like the idea but there is currently no use case to develop it anymore.

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

* commands (working only on curl 7.44 since "--ssl-no-revok" is required)
    * [create-repository](https://github.com/bazzline/unique_number_repository/blob/master/client/create-repository) <host> <applicant name> <repository name>
    * [list-repositories](https://github.com/bazzline/unique_number_repository/blob/master/client/list-repositories) <host>
    * [delete-repository](https://github.com/bazzline/unique_number_repository/blob/master/client/delete-repository) <host> <repository name>
    * [create-unique_number](https://github.com/bazzline/unique_number_repository/blob/master/client/create-unique_number) <host> <applicant name> <repository name>
    * [list-unique_number](https://github.com/bazzline/unique_number_repository/blob/master/client/list-unique_number) <host> <repository name>
    * [delete-unique_number](https://github.com/bazzline/unique_number_repository/blob/master/client/delete-unique_number) <host> <repository name> <number>
* you can use this as base to implement your own logic 
    * a [basic example](https://github.com/bazzline/unique_number_repository/blob/master/example/basic_example) with "pre_execution_hook" and "post_execution_hook" is shipped within

# Install

## By Hand

    mkdir -p vendor/net_bazzline/unique_number_repository
    cd vendor/net_bazzline/unique_number_repository
    git clone https://github.com/bazzline/unique_number_repository .

## With [Packagist](https://packagist.org/packages/net_bazzline/unique_number_repository)

    composer require net_bazzline/unique_number_repository:dev-master

## Configure

## By Hand

    cd <project root>/configuration
    cp client.local.php.dist client.local.php
    #adapt client.local.php
    cp server.local.php.dist server.local.php
    #adapt server.local.php

### With [setup](https://github.com/bazzline/unique_number_repository/blob/master/configuration/setup)

    cd <project root>
    ./configuration/setup

# Benefits

* implement token authentication (you can and should override the existing token by creating your own *client.local.php* and *server.local.php* in *configuration*)

# API

[API](http://www.bazzline.net/53b266b0a2911b7404e1f6bf4cc20b673e04b5bb/index.html) is available at [bazzline.net](http://www.bazzline.net).

# Thanks

* [the silex startup](http://sleep-er.co.uk/blog/2013/Creating-a-simple-REST-application-with-Silex/) - thank you man, this guide is super cool
* [silex simple rest](https://github.com/vesparny/silex-simple-rest/blob/master/src/app.php)
* [designing rest api with silex](https://speakerdeck.com/hhamon/designing-rest-api-with-silex)
* [silex documentation](http://silex.sensiolabs.org/documentation)

# History

* upcomming
    * @todo
        * multiple storage (databases) are supported (right now, [file storage](https://github.com/bazzline/php_component_database_file_storage) is supported)
        * return the right status code (403 instead of 404) if a user tries to delete a number he does not own
* [0.13.0](https://github.com/bazzline/unique_number_repository/tree/0.13.0) - released at 06.03.2016
    * moved to psr-4 autoloading
* [0.12.3](https://github.com/bazzline/unique_number_repository/tree/0.12.3) - released at 18.12.2015
    * updated dependency
* [0.12.2](https://github.com/bazzline/unique_number_repository/tree/0.12.2) - released at 19.11.2015
    * updated dependency
* [0.12.1](https://github.com/bazzline/unique_number_repository/tree/0.12.1) - released at 18.11.2015
    * updated dependency
* [0.12.0](https://github.com/bazzline/unique_number_repository/tree/0.12.0) - released at 30.09.2015
    * changed "not authorized" http status code from wrong 403 to right 401
    * introduced right usage of 403
* [0.11.0](https://github.com/bazzline/unique_number_repository/tree/0.11.0) - released at 20.09.2015
    * added restriction that only the creator (repository or number) can delete the resource
* [0.10.4](https://github.com/bazzline/unique_number_repository/tree/0.10.4) - released at 16.09.2015
    * fixed issue in authorization request
    * made https an optional requirement
    * updated README.md
    * updated dependency
* [0.10.3](https://github.com/bazzline/unique_number_repository/tree/0.10.3) - released at 14.09.2015
    * added *API* section
    * updated dependencies
* [0.10.2](https://github.com/bazzline/unique_number_repository/tree/0.10.2) - released at 13.09.2015
    * added *setup* script to easy up after installation configuration
* [0.10.1](https://github.com/bazzline/unique_number_repository/tree/0.10.1) - released at 13.09.2015
    * fixed authentication problem
* [0.10.0](https://github.com/bazzline/unique_number_repository/tree/0.10.0) - released at 13.09.2015
    * downgraded to silex 1.2 to support php 5.3.3
    * fixed broken links
    * increased compatibility by using '/usr/bin/env' for client code
    * removed unused dependencie
* [0.9.0](https://github.com/bazzline/unique_number_repository/tree/0.9.0) - released at 12.09.2015
    * silix based server/backend

# Final Words

Star it if you like it :-). Add issues if you need it. Pull patches if you enjoy it. Write a blog entry if you use it. [Donate something](https://gratipay.com/~stevleibelt) if you love it :-].
