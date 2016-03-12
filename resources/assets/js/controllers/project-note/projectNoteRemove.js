angular.module('app.controllers')
    .controller('ProjectNoteRemoveController', [
        '$scope', '$location', '$routeParams', 'ProjectNote',
        function ($scope, $location, $routeParams, ProjectNote) {
            var _projectId = $routeParams.projectId;

            $scope.projectId = _projectId;

            $scope.projectNote = ProjectNote.get({
                projectId: _projectId,
                id: $routeParams.id
            });

            $scope.remove = function () {
                $scope.projectNote.$delete({
                        projectId: _projectId,
                        id: $routeParams.id
                    })
                    .then(function () {
                        $location.path('/project/' + _projectId + '/notes');
                    });
            };
        }
    ]);