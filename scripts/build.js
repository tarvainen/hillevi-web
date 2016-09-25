(function () {
    var watch = require('watch');
    var exec = require('child_process').exec;

    watch.watchTree('app/Resources/js/', function () {
        exec('php scripts/create_js_assets.php -dev');
    });
})();