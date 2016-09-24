<?php

require_once 'conf.php';

/**
 * Files to be compressed.
 */
$jsFiles = array(
    'angular/angular.js',
    'angular-animate/angular-animate.js',
    'angular-aria/angular-aria.js',
    'angular-material/angular-material.js',
);

$addNodeModule = function(&$name) {
    $name = ROOT_DIR . 'node_modules/' . $name;
};

array_walk($jsFiles, $addNodeModule);

$out = ROOT_DIR . 'web/js/vendor.js';

echo sprintf("\nGoing to compress the following list of files to the %1\$s:\n%2\$s\n\n", $out, implode("\n", $jsFiles));

$command = sprintf(
    'uglifyjs %1$s -o %2$s',
    implode(' ', $jsFiles),
    $out
);

exec($command);

