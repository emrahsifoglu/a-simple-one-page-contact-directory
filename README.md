## A Simple One Page Contact Directory ##

This is a SPA built with AngularJS + Symfony2. It allows users to store contact data and perform crud operations on the data.

### Main Technologies Used ###

- XAMPP Version: 1.8.3
- PhpStorm 7.1
- Bower
- PHP 5.5
- AngularJS 1.3.5

### How It Works ###

When a user attempts to enter a route, first it will be checked whether authentication is required. Without role, user wouldn't be allowed and will be redirected to home route.

Log in and log out credentials are submitted with AJAX. CRUD operations are also performed thought RESTFUL API using AJAX.

There are 3 bundles in ContactDirectory.

#### DemoBundle ####

This is main bundle consists front end codes.

#### PersonBundle ####

RESTFUL API is here with its PHPUnit test which checks all API functions. API controller doesn't involved with database operations directly. Instead, it uses a custom service named PersonManager.

#### UserBundle ####

User entity, authentication handlers are here.