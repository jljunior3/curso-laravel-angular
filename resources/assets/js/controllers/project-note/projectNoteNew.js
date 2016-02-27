angular.module('app.controllers')
    .controller('ProjectNoteNewController', ['$scope', 'ProjectNote', '$location', function ($scope, ProjectNote, $location) {
        $scope.client = new ProjectNote();

        $scope.save = function () {
            if ($scope.form.$valid) {
                $scope.client.$save().then(function () {
                    $location.path('clients');
                });
            }
        };
    }]);