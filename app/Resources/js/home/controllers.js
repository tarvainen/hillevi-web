(function () {
    'use strict';

    /**
     * Main module initialization.
     */
    angular.module('HomeApp.Controllers', []);

    /**
     * Enable controller lazy load.
     */
    angular.module('App.Config').registerController('HomeApp.Controllers');

    /**
     * Initialize controllers.
     */
    angular.module('HomeApp.Controllers')
        .controller('MainController', MainController)
    ;

    ////////////////////

    /**
     * Main controller. Just for testing.
     *
     * @constructor
     */
    function MainController () {
        var vm = this;

        vm.test = 'hi i am a cat!';
    }

})();