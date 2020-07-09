let AdminApp = angular.module('AdminApp', []);
AdminApp.run(function ($rootScope) {
    $rootScope.include = 'monitoringUser';
})
AdminApp.controller('User', function($scope, $rootScope, $http, $interval, $timeout) {
    $scope.rent_car_price = 0;
    $scope.getRentedCars = function () {
        $http.post('/getrentedcarsofuser').then(function (response) {
            $scope.rentedcars = response.data;
            console.log(response.data);
        })
    }

    $scope.getRentedCars();
    $interval(function () {
        $scope.getRentedCars();
    }, 60000);

    $scope.formatDate = function(date){
        let newDate = new Date(date),
            d = newDate.getDate(),
            m = newDate.getMonth()+1,
            y = newDate.getFullYear(),
            retDate;

        retDate = d+'/'+m+'/'+y;
        return retDate;
    }



    $scope.editRentCar = function(x){
        $scope.showcars();
        $scope.rent_car_id = x.id
        $scope.editname = x.name;
        $scope.editlastname = x.lastname;
        $scope.editJMBG = x.JMBG;
        $scope.editEmail = x.email;
        $scope.editPhoneNum = x.phone_number;
        $scope.last_rent_car_price = x.price;
        $scope.editAdress = x.adress;
        $scope.editCarId = x.cars_id;
        $scope.startTime = setInputDate(x.start_renting_date);
        $scope.endTime = setInputDate(x.end_renting_date);
    }

    function setInputDate(date){
        let newdate = new Date(date),
            d = newdate.getDate(),
            m = newdate.getMonth()+1,
            y = newdate.getFullYear(),
            data;

        if (d < 10){
            d = '0'+d;
        }

        if (m < 10){
            m = '0'+m;
        }

        data = y+'-'+m+'-'+d;
        return data;
    }

    $scope.rent_car_price = 0;
    $scope.countPrice = function(rent_time_start, rent_time_end, id){
        let startTime = new Date(rent_time_start).getTime();
        $scope.rent_time_start = startTime;
        let endTime = new Date(rent_time_end).getTime();
        $scope.rent_time_end = endTime;
        let oneDay = 24 * 60 * 60 * 100;
        if (endTime > startTime){
            let countDays = Math.round(Math.abs(((endTime - startTime) / oneDay) / 10));
            let one_day, two_days, three_days, seven_days, fourteen_days;
            for (let i = 0; i<$scope.cars_list.length; i++){
                if (id == $scope.cars_list[i].id){
                    one_day = $scope.cars_list[i].price.one_day;
                    two_days = $scope.cars_list[i].price.two_days;
                    three_days = $scope.cars_list[i].price.three_days;
                    seven_days = $scope.cars_list[i].price.seven_days;
                    fourteen_days = $scope.cars_list[i].price.fourteen_days;
                    break;
                }
            }

            if (countDays == 1){
                $scope.total = one_day;
            }else if (countDays == 2){
                $scope.total = two_days;
            }else if (countDays == 3){
                $scope.total = three_days;
            }else if(countDays == 7){
                $scope.total = seven_days;
            }else if(countDays == 14){
                $scope.total = fourteen_days;
            }else{
                $scope.total = (countDays * one_day) / 1.5;
            }
            $scope.rent_car_price = $scope.total;
            $rootScope.rent_car_price = $scope.total;
        }
    }

    $scope.editsendrentCar = function (rent_car_id, car_id, name, lastname, JMBG, email, mobile_phone, adress, price, rent_time_start, rent_time_end) {
        if(typeof(name) != 'undefined' && typeof(lastname) != 'undefined' && typeof(JMBG) != 'undefined' && typeof(email) != 'undefined' && typeof(mobile_phone) != 'undefined' && typeof(price) != 'undefined' && typeof(rent_time_start) != 'undefined' && typeof(rent_time_end) != 'undefined'){
            $http.post('/updaterentedcar', {rent_car_id: rent_car_id ,car_id: car_id, name: name, lastname: lastname, JMBG: JMBG, email: email, mobile_phone:mobile_phone, adress:adress, price:price, rent_time_start: $scope.rent_time_start, rent_time_end : $scope.rent_time_end},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerrentcar = response.data;
                console.log(response.data)
                $scope.messagerentcaredit = $scope.answerrentcar.message;
                $scope.statusrentcaredit = $scope.answerrentcar.status;
                $timeout(function () {
                    $scope.messagerentcaredit = null;
                    $scope.statusrentcaredit = null;
                }, 3000);
            })
        }else {
            $scope.messagerentcaredit = 'Obavezna polja su prazna!';
            $scope.statusrentcaredit = false;
            $timeout(function () {
                $scope.messagerentcaredit = null;
                $scope.statusrentcaredit = null;
            }, 3000);
        }
    }

    $scope.showcars = function () {
        $http.post('/getavailablecars').then(function (response) {
            $scope.cars_list = response.data;
            console.log(response.data)
        })
    }

});