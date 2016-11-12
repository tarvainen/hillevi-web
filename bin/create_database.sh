#!/usr/bin/env bash

php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load
php bin/console doctrine:fixtures:load --fixtures=src/App/DataFixtures/Scripts --append
php bin/console doctrine:migrations:migrate