# Next Event

## Description

This is a simple web application similar to a social network. It allows users to create events and invite other users to attend. 
Users can also create groups and invite other users to join. According to the visibility of the event, users can see the event in the event list
 and can join the event.
This application is built principally with PHP and MySQL. Its architecture is based on the MVC pattern. The front-end is built with HTML, CSS and JavaScript.


### Development

The development of this application is based on the MVCR pattern. This pattern is a variation of the MVC pattern. 
The main difference is that the controller is replaced by a router. The router is responsible for receiving the request and redirecting it to the appropriate controller. 
The router also receives the response from the controller and redirects it to the appropriate view. 
The router is also responsible for the redirection of the user to the appropriate page in case of an error.

#### Models

The models are responsible for the interaction with the database. They are responsible for the creation of the tables 
in the database and for the insertion, update and deletion of data in the database. For this application, we two(2) big
groups of models: the user models and the event models. The user models permits to represent the users from their 
name, login, password. It also manages the interaction with the database. The event models represent the events. An
event is composed of a title, a description, a date, a place and an optional image. The event models also manage the
interaction with the database. 

#### Controllers

We have three(3) principal controllers: the user controller, the event controller and the error controller. The user controller
is responsible for the management of the users. It is responsible for the creation of the users, the connection of the users
and the disconnection of the users. The event controller is responsible for the management of the events. It is responsible
for the creation, update, deletion and display of the events. We can also sort and filter the events. The error controller
is responsible for the redirection of the user to the appropriate page in case of an error.

#### Views

The views are responsible for the display of the data. They are responsible for the display of the data in the browser.
We have three(3) principal views: the user view, the event view and the error view. We separate the views in three(3)
principaly for the separation of the concerns, readability and maintainability. The user view is responsible for the display
of the user data. The event view is responsible for the display of the event data. The error view is responsible for the
display of the error data.

#### Routers

The routers are responsible for the redirection of the request to the appropriate controller and for the redirection of the
response to the appropriate view.

### Database

We implements differents versions of the database. But two version work well with the final version of the application.
The first version is based on file. The second version is based on MySQL. The first version is used for the development
of the application. The second version is used for the production of the application. The database is composed of two(2)
tables: the user table and the event table. The user table is composed of the following fields: id, name, login, password.
The event table is composed of the following fields: id, title, description, date, place, image, visibility, user_id.

### Installation

#### Requirements

* PHP 7.2.5 or higher
* MySQL 5.7.24 or higher
* Git 2.20.1 or higher
* PHPMyAdmin 4.8.3 or higher
* A web browser

#### Installation

* Clone the repository
* Create a database
* Import the database
* Configure the database connection
* Run the application
* Enjoy
* If you want to use the application in production, you must configure the database connection and run the dump.sql file
* If you want to use the application in development, you can use a storage based on file or a storage based on MySQL

### Usage

#### User

* Create an account
* Connect to your account
* Create an event
* Update your event
* Delete your event
* Disconnect from your account
* Enjoy

#### Admin

* Connect to your account with the login "admin" and the password "@6fqKQO^58nI!D!&"(This password provisoire before the last version)
* Create an event
* Update your event
* Delete your event
* Disconnect from your account
* Enjoy

### License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

### Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc

## Authors

* **KITSOUKOU Manne Emile** - *Initial work* - [ManneEmile](22013393@unicaen.fr)



