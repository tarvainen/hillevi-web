(function () {
    'use strict';

    /**
     * Main module initialization.
     */
    angular.module('App', [
        'ngMaterial',
        'App.Config'
    ]);

    angular.module('App')
        .run(run);

    /**
     * Runs some of the configurations.
     *
     * @param {*}   $rootScope
     * @param {*}   $location
     * @param {*}   $http
     */
    function run ($rootScope, $location, $http) {
        // TODO: check authentication
    }

})();