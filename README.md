ATK14 Catalog
============

_ATK14 Catalog_ is an skeleton suitable for applications of kind like _Products introduction_, _E-shop_, etc. _ATK14 Catalog_ is built on top of _ATK14 Skelet_ — another great skeleton.

Check out <http://catalog.atk14.net/> to see the catalog running.

The Catalog contains mainly
--------------------------

* List of categories
* List of brands
* List of collections
* Static pages with a hierarchical structure
* Contact page with fast contact form
* News section
* User registration (with strong blowfish passwords hashing)
* Basic administration
* RESTful API
* Sitemap (HTML, XML)
* Localization (English, Czech)
* Front-end tooling including [Bower](http://bower.io/), [Gulp](https://github.com/gulpjs/gulp) and [BrowserSync](https://github.com/BrowserSync/browser-sync)

Installation
------------

```bash
git clone https://github.com/atk14/Atk14Catalog.git
cd Atk14Skelet
git submodule init
git submodule update
./scripts/create_database
./scripts/migrate
```
If you are experiencing a trouble make sure that all requirements are met: <http://book.atk14.net/czech/installation%3Arequirements/>

Installing optional 3rd party libraries
---------------------------------------

```bash
composer update
```

If you don't have the Composer installed, visit http://www.getcomposer.org/

Starting the catalog
---------------------

Start the development server

```bash
./scripts/server
```

and you may find the running catalog on http://localhost:8000/

Installing the catalog as a virtual host on Apache web server
--------------------------------------------------------------

This is optional step. If you have Apache installed, you may want to install the application to a virtual server.

```bash
./scripts/virtual_host_configuration -f
sudo service apache2 reload
chmod 777 tmp log
```

Visit <http://atk14catalog.localhost/>. Is it running? Great!

If you have a trouble run the following command and follow instructions.

```bash
./scripts/virtual_host_configuration
```

Front-end Assets Installation
-----------------------------
#### Install dependencies.
With [Node.js](http://nodejs.org) and npm installed, run the following one liner from the root of your Skelet application:
```bash
$ npm install -g gulp && npm install -g bower && npm install && bower install
```

This will install all the tools you will need to serve and build your front-end assets.

### Serve / watch
```bash
$ gulp serve
```

This outputs an IP address you can use to locally test and another that can be used on devices connected to your network.

### You're done! Happy cataloging!

Don't forget to list your new project on http://www.atk14sites.net/