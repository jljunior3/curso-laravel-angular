angular.module('app.controllers')
    .controller('ProjectNoteRemoveController', [
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

            $scope.remove = function () {
                ProjectNote.delete(
                    {
                        projectId: projectId,
                        id: $routeParams.id
                    },
                    function () {
                        $location.path('/project/' + projectId + '/notes');
                    }
                );
            };
        }
    ]);