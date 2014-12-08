var AppController = function($scope, $location, Session, USER_ROLES, AuthService,  AUTH_EVENTS) {

    var showLoginModal = false;

    $scope.showLoginModal = false;
    $scope.currentUser = null;
    $scope.userRoles = USER_ROLES;
    $scope.isAuthorized = AuthService.isAuthorized;

    $scope.setCurrentUser = function (user) {
        $scope.currentUser = user;
    };

    $scope.toggleLoginModal = function(){
        $scope.showLoginModal = !$scope.showLoginModal;
    };

    $scope.logout = function(){
        AuthService.logout();
    };

    $scope.$on('routeChangeSucceed', function(){
        if (showLoginModal){
            showLoginModal = false;
            $scope.toggleLoginModal();
        }
    });

    $scope.$on(AUTH_EVENTS.notAuthorized, function(event) {
        console.log('notAuthorized');
    });

    $scope.$on(AUTH_EVENTS.loginSuccess, function(event, params){
        console.log('Login is succeed.');
        $scope.$apply(function() {
            $scope.setCurrentUser(params.user);
            $scope.showLoginModal = false;
            $location.path("/admin");
        });
    });

    $scope.$on(AUTH_EVENTS.loginFailed, function(event, params){
        console.log(params.responseText);
    });

    $scope.$on(AUTH_EVENTS.logoutSuccess, function(event, params){
        console.log(params.responseText);
        $scope.$apply(function() {
            $scope.setCurrentUser(null);
            $location.path("/home");
        });
    });

    $scope.$on(AUTH_EVENTS.logoutFailed, function(event, params){
        console.log(params.responseText);
    });

    $scope.$on(AUTH_EVENTS.notAuthenticated, function(event, params) {
        if (!params.current){
            showLoginModal = true;
            $location.path('/home');
        } else{
            $scope.toggleLoginModal();
        }
    });
};

var LoginController =  function ($scope, AUTH_EVENTS, AuthService) {
    $scope.credentials = {
        _username: '',
        _password: ''
    };
    $scope.login = function (credentials) {
        AuthService.login(credentials);
    };
};

var HomeController =  function($scope) {
    $scope.message = '';
};

var AboutController =  function($scope) {
    $scope.message = ' Creating a simple one page contact directory';
};

var ContactController =  function($scope) {
    $scope.message = '@emrahsifoglu';
};

var ResourcesController =  function($scope) {
    $scope.resources =  [];
    $scope.message = '';

    function addResource (title, url){
        $scope.resources.push({title:title ,href:url});
    }

    addResource('AngularJs & Symfony Site Template', 'http://goo.gl/ZKzk85');
    addResource('AngularJS Access Control and Authentication', 'http://goo.gl/71t2H8');
    addResource('Modal Example','http://goo.gl/U8NUFG');
    addResource('href overrides ng-click in Angular.js','http://goo.gl/FgORGW');
    addResource('Techniques for authentication in AngularJS applications','http://goo.gl/OOgQrn');
    addResource('Angular $location.path not working','http://goo.gl/uZiIfP');
    addResource('Is it valid to inject the $rootScope into Controller in order use or override models/method defined under $rootScope in AngularJS?','http://goo.gl/6eykC6');
    addResource('Generating Entity Getters and Setters in Symfony / Doctrine ORM','http://goo.gl/iVHJB4');
    addResource('Validation','http://goo.gl/E4uFbt');
    addResource('REST APIs with Symfony2: The Right Way ','http://goo.gl/VNZFXn');
    addResource('Find object by id in array of javascript objects','http://goo.gl/NqOiSf');
    addResource('AngularJS How to remove an Item from scope','http://goo.gl/JFc8QX');

};

var AdminController =  function($scope, PersonREST) {

    $scope.people = [];
    $scope.message = 'Admin!';
    $scope.loading = true;
    $scope.editMode = 0; // it is actually an id.
    $scope.personId = 0; // to delete.
    $scope.addMode = false;
    $scope.showDeleteConfirmation = false;

    var currentPerson = { id:0, firstName:'', lastName:'' };
    var indexer = {};

    function setIndexer(people) {
        for (var i = 0; i < people.length; i++) {
            indexer[people[i].id] = parseInt(i);
        }
    }

    PersonREST.fetchPeople().success(function (people) {
        setIndexer(people);
        $scope.people = people;
        $scope.loading = false;
    }).error(function (error) {
        $scope.loading = false;
    });

    $scope.toggleEditMode = function(person){
        if ($scope.editMode == person.id){
            person.firstName = currentPerson.firstName;
            person.lastName = currentPerson.lastName;
            currentPerson.id = 0;
            $scope.editMode = currentPerson.id;
        } else {
            if ($scope.editMode > 0 && currentPerson.id > 0){
                $scope.people[indexer[currentPerson.id]].firstName = currentPerson.firstName;
                $scope.people[indexer[currentPerson.id]].lastName = currentPerson.lastName;
            }
            currentPerson.firstName = person.firstName;
            currentPerson.lastName = person.lastName;
            currentPerson.id = person.id;
            $scope.editMode = currentPerson.id;
        }
    };

    $scope.toggleAddMode = function(){
        $scope.newPerson = null;
        $scope.addMode = !$scope.addMode;
    };

    $scope.toggleDeleteModal = function(personId){
        $scope.personId = personId;
        $scope.showDeleteConfirmation = !$scope.showDeleteConfirmation;
    };

    $scope.deletePerson = function(){
        PersonREST.deletePerson($scope.personId).success(function (data) {
            if (data == 1) {
                $scope.people.splice($scope.people.indexOf($scope.people[indexer[$scope.personId]]), 1 );
                setIndexer($scope.people);
                $scope.personId = 0;
            }
            $scope.showDeleteConfirmation = false;
        }).error(function (error) {
            console.log(error.message);
        });
    };

    $scope.createPerson = function(person) {
        if ($scope.new_person_form.$valid){
            PersonREST.createPerson(person).success(function (data) {
                if (data.id > 0) {
                    $scope.newPerson = null;
                    $scope.people.push({ id: data.id, firstName: person.firstName, lastName: person.lastName });
                    setIndexer($scope.people);
                } else {
                    console.log(data.errors);
                }
            }).error(function (error) {
                console.log(error.message);
            });
        }
    };

    $scope.updatePerson = function(person){
        PersonREST.updatePerson(person).success(function (data) {
            if (data.return == 1){
                currentPerson.id = 0;
                $scope.editMode = 0;
                $scope.people[indexer[person.id]].firstName = person.firstName;
                $scope.people[indexer[person.id]].lastName = person.lastName;
            } else {
                console.log(data.return);
            }
        }).error(function (error) {
            console.log(error.message);
        });
    };
};

ResourcesController.$injects = ['$scope'];
LoginController.$injects = ['$scope'];
AdminController.$injects = ['$scope', 'PersonREST'];
HomeController.$injects = ['$scope'];
AppController.$injects = ['$scope', '$location','Session', 'USER_ROLES', 'AuthService', 'AUTH_EVENTS'];
ContactController.$injects = ['$scope'];