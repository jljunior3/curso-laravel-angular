angular.module('app.controllers')
    .controller('ProjectNoteListController', [
        '$scope', '$routeParams', 'ProjectNote',
        function ($scope, $routeParams, ProjectNote) {
            var projectId = $routeParams.projectId;

            $scope.projectId = projectId;
            $scope.projectNotesQuery = ProjectNote.query({projectId: projectId}, function (res) {
                $scope.projectNotes = res.data;
            });
        }
    ]);