<?php

/**
 * Wrapper file for doing tests.
 *
 * Available arguments:
 *
 * -p | --purge: Reload the database before running the phpunit.
 */

$argv = isset($argv) ? $argv : [];

if (in_array('-p', $argv) || in_array('--purge', $argv)) {
    require_once 'app/phpunit-bootstrap.php';
}

system('phpunit');