angular.module('app.controllers')
    .controller('ClientNewController', ['$scope', 'Client', '$location', function ($scope, Client, $location) {
        $scope.client = new Client();

        $scope.save = function () {
            if ($scope.form.$valid) {
                $scope.client.$save().then(function () {
                    $location.path('clients');
                });
            }
        };
    }]);