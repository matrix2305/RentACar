let AdminApp = angular.module('AdminApp', []);
AdminApp.run(function ($rootScope) {
    $rootScope.include = 'monitoring';
})
AdminApp.controller('Admin', function($scope, $rootScope) {

});