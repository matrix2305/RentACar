AdminApp.controller('Brands', function ($scope, $http, $window, $timeout) {
    $scope.showbrands = function () {
        $http.post('/getbrands', {}).then(function (response) {
            $scope.brands_list = response.data;
        })
    }
    $scope.showbrands();

    $scope.deleteBrand = function (id, brand) {
        if ($window.confirm('Da li sigurno brišete brend ' + brand + '?')) {
            $http.post('/deletebrand', {id: id},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerdeletebrand = response.data;
                $scope.messagedelbrand = $scope.answerdeletebrand.message;
                $scope.statusdelbrand = $scope.answerdeletebrand.status;
                $scope.showbrands()
                $timeout(function () {
                    $scope.messagedelbrand = null;
                    $scope.statusdelbrand = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed delete brand!");
                $scope.messagedelbrand = 'Neuspešan zahtev!';
                $scope.statusdelbrand = false;
                $timeout(function () {
                    $scope.messagedelbrand = null;
                    $scope.statusdelbrand = null;
                }, 3000);
            })
        }
    }

    $scope.AddBrand = function (brand_name) {
        if(typeof(brand_name) != 'undefined'){
            $http.post('/addbrand', {brand_name: brand_name},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answeraddbrand = response.data;
                    console.log(response.data);
                    $scope.messageaddbrand = $scope.answeraddbrand.message;
                    $scope.statusaddbrand = $scope.answeraddbrand.status;
                    $scope.showbrands();
                    $timeout(function () {
                        $scope.messageaddbrand = null;
                        $scope.statusaddbrand = null;
                    }, 3000);
                    $scope.addbrand = false;
                }).catch(function () {
                console.log("Failed add brand!");
                $scope.messageaddbrand = 'Neuspešan zahtev!';
                $scope.statusaddbrand = false;
            })
        }else {
            $scope.messageaddclass = 'Uneli ste prazno polje!';
            $scope.statusaddclass = false;
            $timeout(function () {
                $scope.messageaddbrand = null;
                $scope.statusaddbrand = null;
            }, 3000);
            $scope.addbrand = true;
        }

    }

    $scope.UpdateBrand = function (id, brand) {
        if(typeof(id) != 'undefined' && typeof(brand) != 'undefined'){
            $http.post('/updatebrand', {id: id, brand_name: brand},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answerupdatebrand = response.data;
                    $scope.messageupdatebrand = $scope.answerupdatebrand.message;
                    $scope.statusupdatebrand = $scope.answerupdatebrand.status;
                    $scope.showbrands();
                    $timeout(function () {
                        $scope.messageupdatebrand = null;
                        $scope.statusupdatebrand = null;
                    }, 3000);
                    $scope.updatebrand = false;
                }).catch(function () {
                console.log("Failed update brand!");
                $scope.messageupdatebrand = 'Neuspešan zahtev!';
                $scope.statusupdatebrand = false;
                $timeout(function () {
                    $scope.messageupdatebrand = null;
                    $scope.statusupdatebrand = null;
                }, 3000);
            })
        }else {
            $scope.messageupdatebrand = 'Uneta polja su prazna!';
            $scope.statusupdatebrand = false;
            $timeout(function () {
                $scope.messageupdatebrand = null;
                $scope.statusupdatebrand = null;
            }, 3000);
            $scope.updatebrand = true;
        }
    }
});