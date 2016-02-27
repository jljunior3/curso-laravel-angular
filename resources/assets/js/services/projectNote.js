angular.module('app.services')
    .service('ProjectNote', ['$resource', 'appConfig', function ($resource, appConfig) {
        return $resource(
            appConfig.baseUrl + '/project/:id/note/:nodeId',
            {
                id: '@id',
                nodeId: '@nodeId'
            },
            {
                update: {
                    method: 'PUT'
                }
            }
        );
    }]);