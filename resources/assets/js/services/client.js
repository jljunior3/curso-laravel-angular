angular.module('app.services')
    .service('Client', [
        '$resource', 'appConfig',
        function ($resource, appConfig) {
            return $resource(
                appConfig.baseUrl + '/client/:id',
                {
                    id: '@id'
                },
                {
                    query: {
                        method: 'GET',
                        isArray: false
                    },
                    update: {
                        method: 'PUT'
                    }
                }
            );
        }
    ]);