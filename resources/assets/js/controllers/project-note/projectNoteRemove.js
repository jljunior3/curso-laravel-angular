angular.module('app.controllers')
    .controller('ProjectNoteRemoveController',
        ['$scope', '$location', '$routeParams', 'ProjectNote',
            function ($scope, $location, $routeParams, ProjectNote) {
                $scope.client = ProjectNote.get({
                    id: $routeParams.id
                });

                $scope.remove = function () {alert(1);
                    $scope.client.$delete().then(function () {
                        $location.path('clients');
                    });
                };
            }]);