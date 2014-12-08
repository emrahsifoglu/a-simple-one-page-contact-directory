var AuthServiceFactory =  function ($http, Session, USER_ROLES, AUTH_EVENTS, $rootScope) {
    var authService = {};

    authService.login = function (credentials) {
        $.ajax({
            url: '/demo/secured/login_check',
            data:  credentials,
            type: 'POST',
            dataType: 'html',
         success: function(data) {
             var response = $.parseJSON(data);
             if (response.success == true){
                 Session.create(1, 1, 'Emrah', USER_ROLES.admin);
                 $rootScope.$broadcast(AUTH_EVENTS.loginSuccess, { user: Session.read() });
             } else {
                 $rootScope.$broadcast(AUTH_EVENTS.loginFailed, { responseText: response.message });
             }
          },
         error:function(data){
             $rootScope.$broadcast(AUTH_EVENTS.authError, { data: data });
           }
       });
    };

    authService.logout = function(){
        $.ajax({
            url: '/demo/secured/logout',
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var response = $.parseJSON(data);
                if (response.success == true){
                    Session.destroy();
                    $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess, { responseText: response.message });
                } else {
                    $rootScope.$broadcast(AUTH_EVENTS.logoutFailed, { responseText: response.message });
                }
            },
            error:function(data){
                $rootScope.$broadcast(AUTH_EVENTS.authError, { data: data });
            }
        });
    };

    authService.isAuthenticated = function () {
        return !!Session.userId;
    };

    authService.isAuthorized = function (authorizedRoles) {
        if (!angular.isArray(authorizedRoles)) authorizedRoles = [authorizedRoles];
        return (authService.isAuthenticated() && authorizedRoles.indexOf(Session.userRole) !== -1);
    };

    return authService;
};
AuthServiceFactory.$injects = ['$http', 'Session', 'USER_ROLES', 'AUTH_EVENTS', '$rootScope'];