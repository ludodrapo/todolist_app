
# My Lil' 2DO app

Project number 8 from the OpenClassRooms cursus on PHP/Symfony developpement.

Created by Sarah0h and modified by Ludo Drapo with Symfony 5.3, php 8.0 and MySql 5.7.

Bootswatch used for visual enhancements.

To "try it at home", you can download these files, or clone this repository.

You'll just have to configure your .env.local with the access to your own database server like this
```
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:1234/db_name?serverVersion=5.7"
###> doctrine/doctrine-bundle ###
```
then run the following composer scripts (just check that you're still in APP_ENV=dev)
```
% composer install
```
Then, assuming you have npm (or yarn) already installed
```
% npm install
```
And finaly
```
% composer prepare
```
To execute the script that creates database, updates schema and load sfixtures.


