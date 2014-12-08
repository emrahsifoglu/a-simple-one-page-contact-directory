var SessionService = function(){

    this.create = function (sessionId, userId, userName, userRole) {
        this.sessionId = sessionId;
        this.userId = userId;
        this.userName = userName;
        this.userRole = userRole;
    };
    this.destroy = function () {
        this.sessionId = null;
        this.userId = null;
        this.userName = null;
        this.userRole = null;
    };
    this.read = function(){
        return { sessionId: this.sessionId, userId: this.userId, userName: this.userName, userRole: this.userRole };
    };
    return this;

};

PersonRESTService = function($http) {

    var urlBase = '/api/v1/person/';
    var personREST = {};

    personREST.fetchPeople = function () {
        return $http.get(urlBase);
    };

    personREST.fetchPerson = function (id) {
        return $http.get(urlBase + id);
    };

    personREST.createPerson = function (p) {
        return $http.post(urlBase, p);
    };

    personREST.updatePerson = function (p) {
        return $http.put(urlBase + p.id, p);
    };

    personREST.deletePerson = function (id) {
        return $http.delete(urlBase + id);
    };

    return personREST;
};

PersonRESTService.$injects = ['$http'];