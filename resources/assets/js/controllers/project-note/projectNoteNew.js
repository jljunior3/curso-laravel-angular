angular.module('app.controllers')
    .controller('ProjectNoteNewController', [
        '$scope', '$location', '$routeParams', 'ProjectNote',
        function ($scope, $location, $routeParams, ProjectNote) {
            var projectId = $routeParams.projectId;

            $scope.projectId = projectId;

            $scope.projectNote = new ProjectNote();
            $scope.projectNote.project_id = projectId;

            $scope.save = function () {
                if ($scope.form.$valid) {
                    $scope.projectNote.$save({projectId: projectId}).then(function () {
                        $location.path('/project/' + projectId + '/notes');
                    });
                }
            };
        }
    ]);