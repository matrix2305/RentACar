AdminApp.controller('Fuels', function ($scope, $http, $window, $timeout) {

    $scope.showfuels = function () {
        $http.post('/getfuels').then(function (response) {
            $scope.fuels_list = response.data;
        })
    }
    $scope.showfuels();

    $scope.deleteFuel = function (id, fuel) {
        if ($window.confirm('Da li sigurno brišete pogon ' + fuel + '?')) {
            $http.post('/deletefuel', {id: id},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerdeletefuel = response.data;
                console.log(response.data);
                $scope.messagedelfuel = $scope.answerdeletefuel.message;
                $scope.statusdelfuel = $scope.answerdeletefuel.status;
                $scope.showfuels()
                $timeout(function () {
                    $scope.messagedelfuel = null;
                    $scope.statusdelfuel = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed delete fuel!");
                $scope.messagedelfuel = 'Neuspešan zahtev!';
                $scope.statusdelfuel = false;
                $timeout(function () {
                    $scope.messagedelfuel = null;
                    $scope.statusdelfuel = null;
                }, 3000);
            })
        }
    }


    $scope.AddFuel = function (fuel_name) {
        if(typeof(fuel_name) != 'undefined'){
            $http.post('/addfuel', {fuel: fuel_name},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answeraddfuel = response.data;
                    console.log(response.data);
                    $scope.messageaddfuel = $scope.answeraddfuel.message;
                    $scope.statusaddfuel = $scope.answeraddfuel.status;
                    $scope.showfuels();
                    $timeout(function () {
                        $scope.messageaddfuel = null;
                        $scope.statusaddfuel = null;
                    }, 3000);
                    $scope.addfuel = false;
                }).catch(function () {
                console.log("Failed add fuel!");
                $scope.messageaddfuel = 'Neuspešan zahtev!';
                $scope.statusaddfuel = false;
                $timeout(function () {
                    $scope.messageaddfuel = null;
                    $scope.statusaddfuel = null;
                }, 3000);
            })
        }else {
            $scope.messageaddfuel = 'Uneli ste prazno polje!';
            $scope.statusaddfuel = false;
            $timeout(function () {
                $scope.messageaddfuel = null;
                $scope.statusaddfuel = null;
            }, 3000);
            $scope.addfuel = true;
        }

    }

    $scope.UpdateFuel = function (id, fuel_name) {
        if(typeof(id) != 'undefined' && typeof(fuel_name) != 'undefined'){
            $http.post('/updatefuel', {id: id, fuel_name: fuel_name},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answerupdatefuel = response.data;
                    $scope.messageupdatefuel = $scope.answerupdatefuel.message;
                    $scope.statusupdatefuel = $scope.answerupdatefuel.status;
                    $scope.showfuels();
                    $timeout(function () {
                        $scope.messageupdatefuel = null;
                        $scope.statusupdatefuel = null;
                    }, 3000);
                    $scope.updatefuel = false;
                }).catch(function () {
                console.log("Failed update fuel!");
                $scope.messageupdatefuel = 'Neuspešan zahtev!';
                $scope.statusupdatefuel = false;
                $timeout(function () {
                    $scope.messageupdatefuel = null;
                    $scope.statusupdatefuel = null;
                }, 3000);
            })
        }else {
            $scope.messageupdatefuel = 'Uneta polja su prazna!';
            $scope.statusupdatefuel = false;
            $timeout(function () {
                $scope.messageupdatefuel = null;
                $scope.statusupdatefuel = null;
            }, 3000);
            $scope.updatefuel = true;
        }

    }


});