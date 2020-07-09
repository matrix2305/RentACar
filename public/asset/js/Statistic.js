AdminApp.controller('Statistic', function ($scope, $http, $interval) {
    $scope.showlogs = function (){
        $http.post('/getlastlogs').then(function (response) {
            $scope.logs = response.data;
            console.log(response.data);
        })
    }

    $scope.showlogs();

    $interval(function () {
        $scope.showlogs();
    }, 60000);

    $scope.showactivity = function (){
        $http.post('/getactivity', {}).then(function (response) {
            $scope.activity = response.data;
        })
    }

    $scope.showactivity();
    $interval(function () {
        $scope.showactivity();
    }, 60000);

    $scope.showhistoryrcars = function (){
        $http.post('/gethistoryrentedcars').then(function (response) {
            $scope.historyrcars = response.data;
            console.log(response.data);
        })
    }

    $scope.showhistoryrcars();
    $interval(function () {
        $scope.showhistoryrcars();
    }, 60000);

    $scope.showreviewspages = function (){
        $http.post('/getreviewspages').then(function (response) {
            $scope.reviewpages = response.data;
            console.log(response.data);
        })
    }
    $scope.showreviewspages();
    $interval(function () {
        $scope.showreviewspages();
    }, 60000);


    $scope.showreviewscars = function (){
        $http.post('/getreviewscars').then(function (response) {
            $scope.reviewcars = response.data;
            console.log(response.data);
        })
    }

    $scope.showreviewscars();
    $interval(function () {
        $scope.showreviewscars();
    }, 60000);
});