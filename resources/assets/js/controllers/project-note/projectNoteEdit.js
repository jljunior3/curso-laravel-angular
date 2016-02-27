angular.module('app.controllers')
    .controller('ProjectNoteEditController',
        ['$scope', '$location', '$routeParams', 'ProjectNote',
            function ($scope, $location, $routeParams, ProjectNote) {
                $scope.client = ProjectNote.get({
                    id: $routeParams.id
                });

                $scope.save = function () {
                    if ($scope.form.$valid) {
                        ProjectNote.update(
                            {
                                id: $scope.client.id
                            },
                            $scope.client,
                            function () {
                                $location.path('clients');
                            });
                    }
                };
            }]);