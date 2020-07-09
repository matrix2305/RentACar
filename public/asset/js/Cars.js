AdminApp.controller('Cars', function ($scope, $http, $window, $timeout) {
    $scope.showcars = function () {
        $http.post('/getcars').then(function (response) {
            $scope.cars_list = response.data;
            console.log($scope.cars_list)
        })
    }
    $scope.showcars();

    $scope.showfuels = function () {
        $http.post('/getfuels').then(function (response) {
            $scope.fuels_list = response.data;
        })
    }
    $scope.showfuels();

    $scope.showbrands = function () {
        $http.post('/getbrands').then(function (response) {
            $scope.brands_list = response.data;
        })
    }
    $scope.showbrands();

    $scope.showcarbodies = function () {
        $http.post('/getcarbodies').then(function (response) {
            $scope.carbodies_list = response.data;
        })
    }
    $scope.showcarbodies();

    $scope.showclasses = function () {
        $http.post('/getclasses').then(function (response) {
            $scope.classes_list = response.data;
        })
    }
    $scope.showclasses();

    $scope.AddCar = function (model, brand, carbody, carclass, available, price24, price48, price72, price7days, price14days) {
        if(typeof(model) != 'undefined' && typeof(brand) != 'undefined' && typeof(carbody) != 'undefined' && typeof(carclass) != 'undefined' && typeof(available) != 'undefined' && typeof(price24) != 'undefined' && typeof(price48) != 'undefined' && typeof(price72) != 'undefined' && typeof(price7days) != 'undefined' && typeof(price14days) != 'undefined'){
            let element = document.querySelectorAll('.addcarfuel');
            let fuels = [];
            for(var k = 0; k<element.length; k++) {
                if(element[k].checked == true){
                    fuels.push(element[k].value);
                }
            }
            if(fuels.length>0) {
                let fd = new FormData();
                fd.append('add_car_model', model);
                fd.append('add_car_brand_id', brand);
                fd.append('add_car_body_id', carbody);
                fd.append('add_car_class_id', carclass);
                fd.append('add_car_fuels', fuels);
                fd.append('add_car_available', available);
                fd.append('add_price_24h', price24);
                fd.append('add_price_48h', price48);
                fd.append('add_price_72h', price72);
                fd.append('add_price_7_days', price7days);
                fd.append('add_price_14_days', price14days);
                let file = document.getElementById('add_car_image').files[0];
                fd.append('car_image', file);
                $http.post('/addcar', fd, {
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined, 'Process-Data': false}
                })
                    .then(function (response) {
                        $scope.answeraddcar = response.data;
                        $scope.messageaddcar = $scope.answeraddcar.message;
                        $scope.statusaddcar = $scope.answeraddcar.status;
                        console.log(response.data);
                        $scope.showcars();
                        $timeout(function () {
                            $scope.messageaddcar = null;
                            $scope.statusaddcar = null;
                        }, 3000);
                        $scope.addcar = false;

                    })
                    .catch(function () {
                        console.log("Failed add car!");
                        $scope.messageaddcar = 'Neuspešan zahtev!';
                        $scope.statusaddcar = false;
                        $timeout(function () {
                            $scope.messageaddcar = null;
                            $scope.statusaddcar = null;
                        }, 3000);
                    });
            }else {
                $scope.messageaddcar = 'Niste uneli nijedan pogon!';
                $scope.statusaddcar = false;
                $timeout(function () {
                    $scope.messageaddcar = null;
                    $scope.statusaddcar = null;
                }, 3000);
            }
        }else {
            $scope.messageaddcar = 'Neka polja su prazna!';
            $scope.statusaddcar = false;
            $timeout(function () {
                $scope.messageaddcar = null;
                $scope.statusaddcar = null;
            }, 3000);
            $scope.addcar = true;
        }
    }

    $scope.deleteCar = function (id, model, brand) {
        if ($window.confirm('Da li sigurno brišete automobil ' + model + ' ' + brand + '?')) {
            $http.post('/deletecar', {id: id},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerdeletecar = response.data;
                $scope.messagedelcar = $scope.answerdeletecar.message;
                $scope.statusdelcar = $scope.answerdeletecar.status;
                $scope.showcars()
                $timeout(function () {
                    $scope.messagedelcar = null;
                    $scope.statusdelcar = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed delete car!");
                $scope.messagedelcar = 'Neuspešan zahtev!';
                $scope.statusdelcar = false;
                $timeout(function () {
                    $scope.messagedelcar = null;
                    $scope.statusdelcar = null;
                }, 3000);
            })
        }
    }

    $scope.UpdateCar = function (id, model, brand_id, car_body_id, class_id, price24h, price48h, price72h, price7days, price14days, available, lastimage) {
        if(typeof(id) == 'undefined' && typeof(model) == 'undefined' && typeof(brand_id) == 'undefined' && typeof(car_body_id) == 'undefined' && typeof(class_id) == 'undefined' && typeof(price24h) == 'undefined' && typeof(price48h) == 'undefined' && typeof(price72h) == 'undefined' && typeof(price7days) == 'undefined' && typeof(price14days) == 'undefined' && typeof(available) == 'undefined'){
            $scope.messageupdatecar = 'Obavezna uneta polja su prazna!';
            $scope.statusupdatecar = false;
            $scope.updatecar = true;
            return false;
        }
        let element = document.querySelectorAll('.updatecarfuel');
        let fuels = [];
        for(var k = 0; k<element.length; k++) {
            if(element[k].checked == true){
                fuels.push(element[k].value);
            }
        }

        if (fuels.length == 0 ){
            $scope.messageupdatecar = 'Niste uneli ni jedan pogon!';
            $scope.statusupdatecar = false;
            $scope.updatecar = true;
            return false;
        }

        let fd = new FormData();
        fd.append('update_car_id', id);
        fd.append('update_car_model', model);
        fd.append('update_car_brand_id', brand_id);
        fd.append('update_car_body_id', car_body_id);
        fd.append('update_car_class_id', class_id);
        fd.append('last_image', lastimage)
        fd.append('update_car_fuels', fuels);
        fd.append('update_car_available', available);
        fd.append('update_price_24h', price24h);
        fd.append('update_price_48h', price48h);
        fd.append('update_price_72h', price72h);
        fd.append('update_price_7_days', price7days);
        fd.append('update_price_14_days', price14days);
        let file = document.getElementById('update_car_image').files[0]
        fd.append('update_car_image', file);
        $http.post('/updatecar', fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false}
        })
            .then(function (response) {
                $scope.answerupdatecar = response.data;
                console.log(response.data);
                $scope.messageupdatecar = $scope.answerupdatecar.message;
                $scope.statusupdatecar = $scope.answerupdatecar.status;
                $scope.showcars();
                $timeout(function () {
                    $scope.messageupdatecar = null;
                    $scope.statusupdatecar = null;
                }, 3000);
                $scope.updatecar = false;
            })
            .catch(function () {
                console.log("Failed update car!");
                $scope.messageupdatecar = 'Neuspešan zahtev!';
                $scope.statusupdatecar = false;
                $timeout(function () {
                    $scope.messageupdatecar = null;
                    $scope.statusupdatecar = null;
                }, 3000);
            });
    }

    $scope.prepareUpdateCar = function (x) {
        $scope.update_car_id = x.id;
        $scope.update_last_image = x.image;
        $scope.update_car_model = x.model;
        $scope.update_car_brand_id = x.brand.id;
        $scope.update_car_brand_name = x.brand.brand_name;
        $scope.update_car_body_id = x.carBody.id;
        $scope.update_car_body_name = x.carBody.car_body_name;
        $scope.update_car_class_id = x.class.id;
        $scope.update_car_class_name = x.class.class_name;
        $scope.update_car_available = parseInt(x.available);
        $scope.update_car_price_24 = parseInt(x.price.one_day);
        $scope.update_car_price_48 = parseInt(x.price.two_days);
        $scope.update_car_price_72 = parseInt(x.price.three_days);
        $scope.update_car_price_7_days = parseInt(x.price.seven_days);
        $scope.update_car_price_14_days = parseInt(x.price.fourteen_days);
        let countel = $scope.fuels_list.length;
        let countfuels = x.fuels.length;
        let element = document.getElementsByClassName('updatecarfuel');
        for(var j = 0; j < countel; j++){
            element[j].checked = false;
        }

        for (var i = 0; i < countel; i++) {
            for (var k = 0; k < countfuels; k++) {
                if (element[i].value == x.fuels[k].id) {
                    element[i].checked = true;
                }
            }
        }
        $scope.imgupdatecar = '/asset/img/cars/'+x.image;
    }

    $scope.imageUpdateCar = function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $scope.imgupdatecar = e.target.result;
            $scope.$apply();
        };

        reader.readAsDataURL(e.target.files[0]);
    };

    $scope.imageAddCar = function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $scope.imgaddcar = e.target.result;
            $scope.$apply();
        };

        reader.readAsDataURL(e.target.files[0]);
    };
});