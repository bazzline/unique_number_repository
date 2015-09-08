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
        * GET: returns list of all numbers orderd by number ascending
        * PUT: creates a new number (mandatory parameters: "applicant_name" and "number")
* future work
    * implement authentication
    * multiple repositories (databases) are supported (right now, [file storage](https://github.com/bazzline/php_component_database_file_storage) is supported)

## Command Line Based Frontend (Client)

* commands
    * delete-unique-number <applicant name> <repository name> <number>
    * fetch-unique-number <applicant name> <repository name>
    * list-unique-number <applicant name> <repository name>
* you can use this as base to implement your own logic 
    * a basic example with "pre_execution_hook" and "post_execution_hook" will be shipped
* cli will use curl

## To Do's

* add hydrator and dehydrator
* remove hard dependency from "Infrastructure\Storage" (add layer)
