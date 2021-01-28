# Roomonitor Programmers test

This repo contains a test for a new programmers por Roomonitor

## The challenge

We a CSV file in our test server in http://laravel_test.lndo.site/assets/bookings.csv

We need to create a Laravel Command for fetching that CSV file and parse it.

We need import in a table bookings (you need to create the schema, models, migrations,...) all the information, taking in account a few things:

* Save in redis cache the phone number associated to a guest if they are repeated in next bookings and use the redis cache for getting this phone number if is available with a expiration time of 1 day.

* Save in database the phone number adding the US prefix +1 (stored in a config file variable) and remove all characters dash - or parentesis () and spaces

* Check if the checkin date is before of checkout date (generates a warning log and no save it).

* Check if the same room is booked in the overlapped dated by other Guest.

## The result code of the exercise

Please send us a git repository from your side with the code of the exercise.

## Tooling

The repo contains a configuration of software environment with [Lando](https://docs.lando.dev/), please refer to [Lando Laravel recipe documentation](https://docs.lando.dev/config/laravel.html#tooling) for running the environment.

The environment contains PHP 7.0, Laravel 5.2, Redis, MySQL and MailHog services.
