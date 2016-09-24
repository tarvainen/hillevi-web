#!/usr/bin/env bash

# Does the javascript cache file by compressing all the vendor libraries to the same library
php ./scripts/create_js_assets.php
php ./scripts/update_manifest.php