(function (window, angular) {
    'use strict';

    var cdDemoApp = angular.module('cdDemoApp', ['ngRoute']);

    cdDemoApp.constant('AUTH_EVENTS', {
        loginSuccess: 'auth-login-success',
        loginFailed: 'auth-login-failed',
        logoutSuccess: 'auth-logout-success',
        logoutFailed: 'auth-logout-failed',
        sessionTimeout: 'auth-session-timeout',
        notAuthenticated: 'auth-not-authenticated',
        notAuthorized: 'auth-not-authorized',
        authError: 'auth-error'
    });

    cdDemoApp.constant('USER_ROLES', {
            all: '*',
            admin: 'admin',
            guest: 'guest'
        });

    cdDemoApp.config(RouteProvider);
    cdDemoApp.config(InterpolateProvider);
    cdDemoApp.service('Session', SessionService);
    cdDemoApp.service('PersonREST', PersonRESTService)
    cdDemoApp.factory('AuthService', AuthServiceFactory);
    cdDemoApp.directive('modal', ModalDirective);
    cdDemoApp.controller('loginController', LoginController);
    cdDemoApp.controller('adminController', AdminController);
    cdDemoApp.controller('homeController', HomeController);
    cdDemoApp.controller('aboutController', AboutController);
    cdDemoApp.controller('contactController', ContactController);
    cdDemoApp.controller('resourcesController', ResourcesController);
    cdDemoApp.controller('appController', AppController);


    cdDemoApp.run(function ($rootScope, AUTH_EVENTS, AuthService) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            if (next.authorizedRoles){
                var authorizedRoles = next.authorizedRoles;
                if (!AuthService.isAuthorized(authorizedRoles)) {
                    event.preventDefault();
                    if (AuthService.isAuthenticated()) {
                        $rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
                    } else {
                        $rootScope.$broadcast(AUTH_EVENTS.notAuthenticated, { current: !!current });
                    }
                }
            }
        });

        $rootScope.$on("$routeChangeSuccess", function () {
            $rootScope.$broadcast('routeChangeSucceed');
        });
    })


})(window, angular);