angular.module('app.controllers')
    .controller('ProjectNoteEditController', [
        '$scope', '$location', '$routeParams', 'ProjectNote',
        function ($scope, $location, $routeParams, ProjectNote) {
            var _projectId = $routeParams.projectId;

            $scope.projectId = _projectId;

            $scope.projectNote = ProjectNote.get({
                projectId: _projectId,
                id: $routeParams.id
            });

            $scope.save = function () {
                if ($scope.form.$valid) {
                    ProjectNote.update(
                        {
                            projectId: _projectId,
                            id: $routeParams.id
                        },
                        $scope.projectNote,
                        function () {
                            $location.path('/project/' + _projectId + '/notes');
                        }
                    );
                }
            };
        }
    ]);