(function () {
    'use strict';

    /**
     * Main module initialization.
     */
    angular.module('App.Config', ['ngRoute']);

    /**
     * Run configuration function.
     */
    angular.module('App.Config')
        .config(config);

    ////////////////////////////

    /**
     * Configure the application.
     *
     * @param {*}   $routeProvider
     * @param {*}   $controllerProvider
     * @param {*}   $compileProvider
     */
    function config ($routeProvider, $controllerProvider, $compileProvider) {
        angular.module('App.Config').registerController = registerController;

        /**
         * Makes the controllers lazy load possible. Remember to call this in the app.js for every application.
         *
         * @param {string}  module
         */
        function registerController (module) {
            angular.module(module).controller = $controllerProvider.register;
        }

        angular.module('App.Config').compiler = $compileProvider;

        $routeProvider
            .when('/', get('home'))
            .when('/login', get('login'))
        ;

        /**
         * Function to fetch the right route configuration.
         *
         * @param   {string}    path
         * @returns {*}
         */
        function get (path) {
            return {
                templateUrl: getTemplateUrl(path),
                resolve: resolve(path)
            };
        }

        /**
         * The resolver.
         *
         * @param   {string}    path
         * @returns {*}
         */
        function resolve (path) {
            return {
                load: getScripts(path)
            };
        }

        /**
         * Function to fetch the template url.
         *
         * @param   {string}    template
         *
         * @returns {string}
         */
        function getTemplateUrl (template) {
            return 'template/' + template + '/index';
        }

        /**
         * Fetches the scripts from the server.
         *
         * @param   {string}    path
         *
         * @returns {*}
         */
        function getScripts (path) {
            getAngularScripts.$inject = ['$q', '$rootScope'];

            function getAngularScripts ($q, $rootScope) {
                var deferred = $q.defer();

                $.when(
                    $.getScript('ng/' + path),
                    $.ajax('css/' + path)
                ).done(onSuccess);

                function onSuccess (scripts, css) {
                    $rootScope.$apply(function apply () {
                        $('#styles').empty().html(css[0]);

                        deferred.resolve();
                    });
                }

                return deferred.promise;
            }

            return getAngularScripts;
        }
    }

})();