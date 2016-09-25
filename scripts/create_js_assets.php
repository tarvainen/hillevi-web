<?php

require_once 'conf.php';

/**
 * Files to be compressed.
 */
$jsFiles = array(
    'jquery/dist/jquery.js',
    'angular/angular.js',
    'angular-route/angular-route.js',
    'angular-animate/angular-animate.js',
    'angular-aria/angular-aria.js',
    'angular-material/angular-material.js',
);

$angularFiles = array(
    'app.js',
    'config.js'
);

$addNodeModule = function (&$name) {
    $name = ROOT_DIR . 'node_modules/' . $name;
};

$addJsDir = function (&$name) {
    $name = ROOT_DIR . 'app/Resources/js/' . $name;
};

array_walk($jsFiles, $addNodeModule);
array_walk($angularFiles, $addJsDir);

$out = ROOT_DIR . 'web/js/vendor.js';
$outAngular = ROOT_DIR . 'web/js/app-files.js';

if (!in_array('-dev', $argv)) {
    echo sprintf("\nGoing to compress the following list of files to the %1\$s:\n%2\$s\n\n", $out, implode("\n", $jsFiles));

    $command = sprintf(
        'uglifyjs %1$s -o %2$s',
        implode(' ', $jsFiles),
        $out
    );

    exec($command);

    echo sprintf("\nGoing to compress the following list of files to the %1\$s:\n%2\$s\n\n", $out, implode("\n", $angularFiles));

    $command = sprintf(
        'uglifyjs %1$s -o %2$s',
        implode(' ', $angularFiles),
        $outAngular
    );

    exec($command);
} else {
    echo "\nDevelopment bit is set to on. We are just copying everything in the destination file.\n";

    $outJs = '';

    foreach ($jsFiles as $file) {
        $outJs .= file_get_contents($file);
    }

    file_put_contents($out, $outJs);

    $outAppJs = '';

    foreach ($angularFiles as $file) {
        $outAppJs .= file_get_contents($file);
    }

    file_put_contents($outAngular, $outAppJs);
}
