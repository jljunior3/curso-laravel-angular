angular.module('app.controllers')
    .controller('ProjectNoteEditController', [
        '$scope', '$location', '$routeParams', 'ProjectNote',
        function ($scope, $location, $routeParams, ProjectNote) {
            var projectId = $routeParams.projectId;

            $scope.projectId = projectId;

            ProjectNote.get(
                {
                    projectId: projectId,
                    id: $routeParams.id
                },
                function (res) {
                    $scope.projectNote = res.data;
                }
            );

            $scope.save = function () {
                if ($scope.form.$valid) {
                    ProjectNote.update(
                        {
                            projectId: projectId,
                            id: $routeParams.id
                        },
                        $scope.projectNote,
                        function () {
                            $location.path('/project/' + projectId + '/notes');
                        }
                    );
                }
            };
        }
    ]);