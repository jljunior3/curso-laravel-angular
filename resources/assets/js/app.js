var app = angular.module('app', ['ngRoute', 'angular-oauth2', 'app.controllers', 'app.services']);

angular.module('app.controllers', ['ngMessages', 'angular-oauth2']);
angular.module('app.services', ['ngResource']);

app.provider('appConfig', function () {
    var config = {
        baseUrl: 'http://localhost:8000'
    };

    return {
        config: config,
        $get: function () {
            return config;
        }
    }
});

app.config([
    '$routeProvider', '$httpProvider', 'OAuthProvider',
    'OAuthTokenProvider', 'appConfigProvider',
    function ($routeProvider, $httpProvider, OAuthProvider, OAuthTokenProvider, appConfigProvider) {
        $routeProvider
            .when('/login', {
                templateUrl: 'build/views/login.html',
                controller: 'LoginController'
            })
            .when('/home', {
                templateUrl: 'build/views/home.html',
                controller: 'HomeController',
                requireLogin: true
            })

            .when('/clients', {
                templateUrl: 'build/views/client/list.html',
                controller: 'ClientListController',
                requireLogin: true
            })
            .when('/clients/new', {
                templateUrl: 'build/views/client/new.html',
                controller: 'ClientNewController',
                requireLogin: true
            })
            .when('/clients/:id/edit', {
                templateUrl: 'build/views/client/edit.html',
                controller: 'ClientEditController',
                requireLogin: true
            })
            .when('/clients/:id/remove', {
                templateUrl: 'build/views/client/remove.html',
                controller: 'ClientRemoveController',
                requireLogin: true
            })

            .when('/project/:projectId/notes', {
                templateUrl: 'build/views/project-note/list.html',
                controller: 'ProjectNoteListController',
                requireLogin: true
            })
            .when('/project/:projectId/notes/:id/show', {
                templateUrl: 'build/views/project-note/show.html',
                controller: 'ProjectNoteShowController',
                requireLogin: true
            })
            .when('/project/:projectId/notes/new', {
                templateUrl: 'build/views/project-note/new.html',
                controller: 'ProjectNoteNewController',
                requireLogin: true
            })
            .when('/project/:projectId/notes/:id/edit', {
                templateUrl: 'build/views/project-note/edit.html',
                controller: 'ProjectNoteEditController',
                requireLogin: true
            })
            .when('/project/:projectId/notes/:id/remove', {
                templateUrl: 'build/views/project-note/remove.html',
                controller: 'ProjectNoteRemoveController',
                requireLogin: true
            });

        /*$httpProvider.defaults.transformResponse = function (data, headers) {
         var headersGetter = headers();

         if (headersGetter['content-type'] == 'application/json' ||
         headersGetter['content-type'] == 'text/json') {
         var dataJson = JSON.parse(data);

         if (dataJson.hasOwnProperty('data')) {
         return dataJson.data;
         }
         }

         return data;
         };*/

        OAuthProvider.configure({
            baseUrl: appConfigProvider.config.baseUrl,
            clientId: 'appid1',
            clientSecret: 'secret', // optional
            grantPath: 'oauth/access_token'
        });

        OAuthTokenProvider.configure({
            name: 'token',
            options: {
                secure: false
            }
        });
    }
]);

app.run([
    '$rootScope', '$window', '$location', 'OAuth',
    function ($rootScope, $window, $location, OAuth) {

        $rootScope.$on('$routeChangeStart', function (event, next) {
            if (next.controller == 'LoginController' && OAuth.isAuthenticated()) {
                $location.path('home');
            }

            if (next.requireLogin !== undefined && !OAuth.isAuthenticated()) {
                $location.path('login');
            }
        });

        $rootScope.$on('oauth:error', function (event, rejection) {
            // Ignore 'invalid_grant' error - should be catched on 'LoginController'.
            if ('invalid_grant' === rejection.data.error) {
                return;
            }

            // Refresh token when a 'invalid_token' error occurs.
            if ('invalid_token' === rejection.data.error) {
                return OAuth.getRefreshToken();
            }

            // Redirect to '/login' with the 'error_reason'.
            return $window.location.href = '/#/login?error_reason=' + rejection.data.error;
        });
    }
]);