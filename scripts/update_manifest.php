<?php

require_once 'conf.php';

$contents = file_get_contents(ROOT_DIR . 'hillevi.appcache.content') . "\n# " . date('d.m.Y H:m:s');

$command = sprintf(
    "echo '%1\$s' > %2\$s",
    $contents,
    ROOT_DIR . 'hillevi.appcache'
);

exec($command);