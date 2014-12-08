var RouteProvider = function($routeProvider, $locationProvider, USER_ROLES){

    var origin = document.location.origin;
    var folder = document.location.pathname.split('/')[1];
    var partials = origin + '/' + folder + 'bundles/contactdirectorydemo/partials/';

    window.routes =
    {
        "/admin": {
            templateUrl : partials + 'admin.html',
            controller  : 'adminController',
            authorizedRoles: [USER_ROLES.admin]
        },
        "/home": {
            templateUrl : partials + 'home.html',
            controller  : 'homeController'
        },
        "/about": {
            templateUrl : partials + 'about.html',
            controller  : 'aboutController'
        },
        "/resources": {
            templateUrl : partials + 'resources.html',
            controller  : 'resourcesController'
        },
        "/contact": {
            templateUrl : partials + 'contact.html',
            controller  : 'contactController'
        }
    };

    for(var path in window.routes) {
        $routeProvider.when(path, window.routes[path]);
    }

    $routeProvider.otherwise({redirectTo: '/home'});
};

var InterpolateProvider = function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
};

RouteProvider.$injects = ['$routeProvider', '$locationProvider', 'USER_ROLES'];
InterpolateProvider.$injects = ['$interpolateProvider'];