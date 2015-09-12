# Unique Number Repository Webservice

Sometimes, you simple need to make sure you can fetch a unique number for a given problem scope. 
To easy things up, this project will create a rest based webservice as backend and a command line frontend to support you solving this problem.

## REST Based Backend (Server)

* endpoint s
    * "/unique-number-repository"
        * DELETE: deletes/releases a number (mandatory parameter: "repository_name")
        * GET: returns list of all repository names
        * PUT: creates a new repository (mandatory parameters: "repository_name", "applicant_name")
    * "/unique-number-repository/{name}"
        * DELETE: deletes/releases a number (mandatory parameter: "applicant_name")
        * GET: returns list of all numbers ordered by number ascending
        * PUT: creates a new number (mandatory parameters: "applicant_name" and "number")
* future work
    * implement authentication

## Command Line Based Frontend (Client)

* commands
    * create-repository <host> <applicant name> <repository name>
    * list-repositories <host>
    * delete-repository <host> <repository name>
    * create-unique_number <host> <applicant name> <repository name>
    * list-unique_number <host> <repository name>
    * delete-unique_number <host> <repository name> <number>
* you can use this as base to implement your own logic 
    * a basic example with "pre_execution_hook" and "post_execution_hook" will be shipped
* cli will use curl

# Install

## By Hand

    mkdir -p vendor/net_bazzline/unique_number_repository
    cd vendor/net_bazzline/unique_number_repository
    git clone https://github.com/bazzline/unique_number_repository .

## With [Packagist](https://packagist.org/packages/net_bazzline/unique_number_repository)

    composer require net_bazzline/unique_number_repository:dev-master

# Benefits

# Thanks

* [the silex startup](http://sleep-er.co.uk/blog/2013/Creating-a-simple-REST-application-with-Silex/) - thank you man, this guide is super cool
* [silex simple rest](https://github.com/vesparny/silex-simple-rest/blob/master/src/app.php)
* [designing rest api with silex](https://speakerdeck.com/hhamon/designing-rest-api-with-silex)
* [silex documentation](http://silex.sensiolabs.org/documentation)

# History

* upcomming
    * @todo
        * multiple storage (databases) are supported (right now, [file storage](https://github.com/bazzline/php_component_database_file_storage) is supported)
* [0.9.0](
