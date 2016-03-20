angular.module('app.controllers')
    .controller('ClientRemoveController', [
        '$scope', '$location', '$routeParams', 'Client',
        function ($scope, $location, $routeParams, Client) {
            Client.get({id: $routeParams.id}, function (res) {
                $scope.client = res.data;
            });

            $scope.remove = function () {
                Client.delete({id: $routeParams.id}, function () {
                    $location.path('/clients');
                });
            };
        }
    ]);