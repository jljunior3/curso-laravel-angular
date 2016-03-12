angular.module('app.controllers')
    .controller('HomeController', ['$scope', function ($scope) {
        window.location.href = 'https://www.getpostman.com/oauth2/callback';
    }]);