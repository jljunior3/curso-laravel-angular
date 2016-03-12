angular.module('app.services')
    .service('ProjectNote', [
        '$resource', 'appConfig',
        function ($resource, appConfig) {
            return $resource(
                appConfig.baseUrl + '/project/:projectId/note/:id',
                {
                    projectId: '@projectId',
                    id: '@id'
                },
                {
                    update: {
                        method: 'PUT'
                    }
                }
            );
        }
    ]);