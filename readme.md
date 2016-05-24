# tk2mvc :boom: 

__Project:__ tk2mvc  
__Published:__ 16 May 2016  
__Web:__ <http://www.tropotek.com/>  
__Authors:__ Michael Mifsud <http://www.tropotek.com/>  

A base site using the Tk2 framework MVC on its own.

## Contents

- [Installation](#installation)
- [Introduction](#introduction)

## Installation

Start by getting the dependant libs:

~~~bash
# git clone https://github.com/tropotek/tk2mvc.git
# cd tk2mvc
# composer install
~~~

Then edit the /src/App/config/config.php file to your required settings.

Next check the /src/App/sql folder for any .sql files you need to install to your database

## Introduction




## DEV NOTES

- __Front Controller__ - A common entry point for all requests. A rewrite engine (e.g. mod_rewrite) 
  redirects traffic to index.php, from which all requests are handled.
- __Request__ - Encapsulates a HTTP request. The interface is useful because it simplifies 
  testing and also allow you to make sub-requests, which you are going to need if you're interested in HMVC.
- __Response__ - Encapsulates a HTTP response. Same as with Request. It's also possible to
  create different concretions for different response content, and have the concretion 
  treat the resource before returning it as a response.
- __Router__ - Responsible for matching an URL to an appropriate branch of execution, such as a controller.
- __Dispatcher__ - Dispatches a route to its designated branch of execution. In other words, 
  it invokes the controller.
- __Bootstrap__ - Configuring and loading required resources. Usually takes place in 
  modules to allow them to load settings required for their operation.
- __Events__ - An event dispatcher triggers events to which listeners respond to. 
  Besides the event name, a data object is also usually passed along. An event 
  system allows you to hook in at any point in your application's flow of control.
