'use strict';

/**
 * @ngdoc overview
 * @name cuantoQuedaApp
 * @description
 * # cuantoQuedaApp
 *
 * Main module of the application.
 */
angular
  .module('cuantoQuedaApp', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'AboutCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
