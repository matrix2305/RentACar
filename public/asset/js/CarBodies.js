AdminApp.controller('CarBodies', function ($scope, $http, $window, $timeout) {
    $scope.showcarbodies = function () {
        $http.post('/getcarbodies', {}).then(function (response) {
            $scope.carbodies_list = response.data;
        })
    }
    $scope.showcarbodies();

    $scope.deleteCarBody = function (id, carbody) {
        if ($window.confirm('Da li sigurno brišete karoseriju ' + carbody + '?')) {
            $http.post('/deletecarbody', {id: id},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerdeletecarbody = response.data;
                console.log(response.data);
                $scope.messagedelcarbody = $scope.answerdeletecarbody.message;
                $scope.statusdelcarbody = $scope.answerdeletecarbody.status;
                $scope.showcarbodies()
                $timeout(function () {
                    $scope.messagedelcarbody = null;
                    $scope.statusdelcarbody = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed delete Car Body!");
                $scope.messagedelcarbody = 'Neuspešan zahtev!';
                $scope.statusdelcarbody = false;
                $timeout(function () {
                    $scope.messagedelcarbody = null;
                    $scope.statusdelcarbody = null;
                }, 3000);
            })
        }
    }

    $scope.AddCarBody = function (car_body) {
        if(typeof(car_body) != 'undefined'){
            $http.post('/addcarbody', {car_body_name: car_body},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answeraddcarbody = response.data;
                    console.log(response.data);
                    $scope.messageaddcarbody = $scope.answeraddcarbody.message;
                    $scope.statusaddcarbody = $scope.answeraddcarbody.status;
                    $scope.showcarbodies();
                    $timeout(function () {
                        $scope.messageaddcarbody = null;
                        $scope.statusaddcarbody = null;
                    }, 3000);
                    $scope.addcarbody = false;
                }).catch(function () {
                console.log("Failed add car body!");
                $scope.messageaddcarbody = 'Neuspešan zahtev!';
                $scope.statusaddcarbody = false;
                $timeout(function () {
                    $scope.messageaddcarbody = null;
                    $scope.statusaddcarbody = null;
                }, 3000);

            })
        }else {
            $scope.messageaddclass = 'Uneli ste prazno polje!';
            $scope.statusaddclass = false;
            $timeout(function () {
                $scope.messageaddcarbody = null;
                $scope.statusaddcarbody = null;
            }, 3000);
            $scope.addcarbody = true;
        }
    }

    $scope.UpdateCarBody = function (id, carbodyname) {
        if(typeof(id) != 'undefined' && typeof(carbodyname) != 'undefined'){
            $http.post('/updatecarbody', {id: id, car_body_name: carbodyname},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answerupdatecarbody = response.data;
                    $scope.messageupdatecarbody = $scope.answerupdatecarbody.message;
                    $scope.statusupdatecarbody = $scope.answerupdatecarbody.status;
                    $scope.showcarbodies();
                    $timeout(function () {
                        $scope.messageupdatecarbody = null;
                        $scope.statusupdatecarbody = null;
                    }, 3000);
                    $scope.updatecarbody = false;
                }).catch(function () {
                console.log("Failed update car body!");
                $scope.messageupdatecarbody = 'Neuspešan zahtev!';
                $scope.statusupdatecarbody = false;
                $timeout(function () {
                    $scope.messageupdatecarbody = null;
                    $scope.statusupdatecarbody = null;
                }, 3000);
            })
        }else {
            $scope.messageupdatecarbody = 'Uneta polja su prazna!';
            $scope.statusupdatecarbody = false;
            $timeout(function () {
                $scope.messageupdatecarbody = null;
                $scope.statusupdatecarbody = null;
            }, 3000);
            $scope.updatecarbody = true;
        }
    }
});