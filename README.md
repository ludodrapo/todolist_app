
# My Lil' 2Do List app

Project number 8 from the OpenClassRooms cursus on PHP/Symfony developpement.

Created by Sarah0h and modified by Ludo Drapo with Symfony 5.3, php 8.0 and MySql 5.7.

Feel free to [contribute](CONTRIBUTING.md) !

Bootswatch used for visual enhancements.

To "try it at home", you can download these files, or clone this repository

```
# Clone the repository
% git clone https://github.com/ludodrapo/todolist_app.git

# Go to the repository
% cd todolist_app
```

After you opened it with your favorite IDE, you'll just have to create and configure your .env.local file with the access to your own database server like this
```
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:1234/db_name?serverVersion=5.7"
###> doctrine/doctrine-bundle ###
```
then run the following composer scripts
```
% composer install
```
To install all composer dependencies.
Then, assuming you have npm already installed (or yarn)
```
% npm install
```
And finaly
```
% composer prepare
```
To execute the script that creates database, updates schema and loads fixtures.

You can easily use the app in your browser in a dev environment with the symfony server by running
```
% symfony serve
```
And don't forget to run
```
% npm run watch
```
If you want to make changes in the assets (js and css/scss files) and see those changes while you do it (rebuild the assests after each change).

