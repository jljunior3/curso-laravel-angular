angular.module('app.controllers')
    .controller('ProjectNoteNewController', [
        '$scope', '$location', '$routeParams', 'ProjectNote',
        function ($scope, $location, $routeParams, ProjectNote) {
            var _projectId = $routeParams.projectId;

            $scope.projectId = _projectId;

            $scope.projectNote = new ProjectNote();
            $scope.projectNote.project_id = _projectId;

            $scope.save = function () {
                if ($scope.form.$valid) {
                    $scope.projectNote.$save({projectId: _projectId}).then(function () {
                        $location.path('/project/' + _projectId + '/notes');
                    });
                }
            };
        }
    ]);