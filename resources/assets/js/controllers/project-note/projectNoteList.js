angular.module('app.controllers')
    .controller('ProjectNoteListController', [
        '$scope', '$routeParams', 'ProjectNote',
        function ($scope, $routeParams, ProjectNote) {
            var _projectId = $routeParams.projectId;

            $scope.projectId = _projectId;
            $scope.projectNotes = ProjectNote.query({projectId: _projectId});
        }
    ]);