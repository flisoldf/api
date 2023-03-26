# Slim Framework 3 Skeleton Application

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

PHP Version 7.4.33
Composer version 2.3.3 2022-04-01 22:15:35
MariaDB version 10.5.17

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can run these commands 

	cd [my-app-name]
	php composer.phar start

Run this command in the application directory to run the test suite

	php composer.phar test

That's it! Now go build something cool.

## Install the Application (Docker)

Run these commands in the directory where you download the code.

1. Start the docker-compose services (PHP, Nginx) in the background (detached):
  ```
$ docker-compose up -d -p flisoldf
  ```

2. Run the Composer installer in the PHP container to install the PHP dependencies:
  ```
$ docker-compose exec php composer install
  ```

The application should now be available on [http://localhost:8080](http://localhost:8080).

To down server, run the following command:
  ```
$ docker-compose down
  ```
