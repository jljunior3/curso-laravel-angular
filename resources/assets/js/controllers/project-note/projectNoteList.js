angular.module('app.controllers')
    .controller('ProjectNoteListController', ['$scope', 'ProjectNote', function ($scope, ProjectNote) {
        $scope.clients = ProjectNote.query();
        console.log($scope.clients);
    }]);