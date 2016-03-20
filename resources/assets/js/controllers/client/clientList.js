angular.module('app.controllers')
    .controller('ClientListController', [
        '$scope', 'Client', function ($scope, Client) {
            $scope.clientsQuery = Client.query({}, function (res) {
                $scope.clients = res.data;
            });
        }
    ]);