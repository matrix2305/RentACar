let MyApp = angular.module('MyApp', ['ngSanitize']);
MyApp.run( function($anchorScroll) {
    $anchorScroll.yOffset = 110;   // always scroll by 50 extra pixels
})
MyApp.controller('Portal', function ($scope, $interval, $http, $timeout) {
    $scope.sendreview = function(car_id, car_model, car_brand){
        $http.post('/addcarreview', {car_id: car_id, car_model: car_model, car_brand: car_brand},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            console.log(response.data)
        })
    }



    $scope.include = 'portal';
    $scope.showcars = function (){
        $http.post('/getcarsportal').then(function (response) {
            $scope.cars = response.data;
        })
    }
    $scope.showcars();


    $http.post('/getratings').then(function (response) {
        $scope.ratings = response.data;
    })

    $http.post('/getconinfo').then(function (response) {
        $scope.contacts = response.data;
    })

    $http.post('/getinfo').then(function (response) {
        $scope.info = response.data;
        $scope.info = $scope.info.informations.substring(0, $scope.info.informations.length - 1 );
    })

    let star = "<i class='fas fa-star'></i>";
    let i = 0

    $interval(function () {
        $scope.stars = [];
        $scope.rating = $scope.ratings[i];
        for(let k = 0; k<$scope.rating.rating; k++){
            $scope.stars.push(star)
        }
        i++;
        if( i > $scope.ratings.length - 1 ){
            i = 0;
        }
    }, 2000);

    $scope.perv = function (){
        i--;
        if(i < 0){
            i = $scope.ratings.length - 1;
        }
        $scope.stars = [];
        $scope.rating = $scope.ratings[i];
        for(let k = 0; k<$scope.rating.rating; k++){
            $scope.stars.push(star)
        }
    }

    $scope.next = function (){
        i++;
        if(i > $scope.ratings.length - 1){
            i = 0;
        }
        $scope.stars = [];
        $scope.rating = $scope.ratings[i];
        for(let k = 0; k<$scope.rating.rating; k++){
            $scope.stars.push(star)
        }
    }


    $scope.contactAgency = function (name, lastname, email, message) {
        if(typeof(name) != 'undefined' && typeof(lastname) != 'undefined' && typeof(email) != 'undefined' && typeof(message) != 'undefined'){
            $http.post('/contactagency', {name: name, lastname: lastname, email: email, message: message},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerconag = response.data;
                console.log(response.data)
                $scope.messageconag = $scope.answerconag.message;
                $scope.statusconag = $scope.answerconag.status;
                $timeout(function () {
                    $scope.messageconag = null;
                    $scope.statusconag = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed contact agency!");
                $scope.messageconag = 'Neuspešan zahtev!';
                $scope.statusconag = false;
                $timeout(function () {
                    $scope.messageconag = null;
                    $scope.statusconag = null;
                }, 3000);
            })
        }else {
            $scope.messageconag = 'Obavezna polja su prazna!';
            $scope.statusconag = false;
            $timeout(function () {
                $scope.messageconag = null;
                $scope.statusconag = null;
            }, 3000);
        }
    }
    $scope.AddRating = function (name, rating, message) {
        if(typeof(name) != 'undefined' && typeof(rating) != 'undefined' && typeof(message) != 'undefined'){
            $http.post('/addrating', {name: name, rating: rating, message: message},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerratag = response.data;
                console.log(response.data)
                $scope.messageaddrt = $scope.answerratag.message;
                $scope.statusaddrt = $scope.answerratag.status;
                $timeout(function () {
                    $scope.messageaddrt = null;
                    $scope.statusaddrt = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed contact agency!");
                $scope.messageaddrt = 'Neuspešan zahtev!';
                $scope.statusaddrt = false;
                $timeout(function () {
                    $scope.messageaddrt = null;
                    $scope.statusaddrt = null;
                }, 3000);
            })
        }else {
            $scope.messageaddrt = 'Obavezna polja su prazna!';
            $scope.statusaddrt = false;
            $timeout(function () {
                $scope.messageaddrt = null;
                $scope.statusaddrt = null;
            }, 3000);
        }

    }

    $scope.PrepareRentCar = function (x) {
            $scope.car_id = x.id;
            $scope.car_name = x.brand.brand_name + ' ' + x.model;
            $scope.car_image = x.image;
            $scope.car_body_name = x.carBody.car_body_name;
            $scope.car_available = x.available;
            $scope.car_class_color = x.class.class_color;
            $scope.car_reserved = x.rent_reserved
            $scope.car_fuels = x.fuels;
            $scope.car_class = x.class.class_name;
            $scope.price24h = x.price.one_day;
            $scope.price48h = x.price.two_days;
            $scope.price72h = x.price.three_days;
            $scope.price7_days = x.price.seven_days;
            $scope.price14_days = x.price.fourteen_days;
            if ($scope.car_available == 0) {
                $scope.messagerentcar = 'Nije moguće iznajmiti automobil - ovaj Model nije na stanju!';
                $scope.statusrentcar = false;
            }
            if ($scope.car_available == $scope.car_reserved) {
                $scope.messagerentcar = 'Nije moguće iznajmiti automobil - svi automobili pod ovim modelom su rezervisani!';
                $scope.statusrentcar = false;
            }
            $scope.showcars();
            $scope.getCommentsCar = function (id) {
                $http.post('/getcommentscar',{id:id}).then(function (response) {
                    $scope.commentsCar = response.data;
                })
            }

            $scope.getCommentsCar($scope.car_id);
            $scope.include = 'rentcar';
            $scope.sendreview(x.id, x.model, x.brand.brand_name);
    }

    $scope.rentCar = function (id, name, lastname, JMBG, email, mobile_phone, adress, price, rent_time_start, rent_time_end) {
        if(typeof(name) != 'undefined' && typeof(lastname) != 'undefined' && typeof(JMBG) != 'undefined' && typeof(email) != 'undefined' && typeof(mobile_phone) != 'undefined' && typeof(price) != 'undefined' && typeof(rent_time_start) != 'undefined' && typeof(rent_time_end) != 'undefined'){
            $http.post('/rentcar', {id: id, name: name, lastname: lastname, JMBG: JMBG, email: email, mobile_phone:mobile_phone, adress:adress, price:price, rent_time_start: rent_time_start, rent_time_end : rent_time_end},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerrentcar = response.data;
                console.log(response.data)
                $scope.messagerentcar = $scope.answerrentcar.message;
                $scope.statusrentcar = $scope.answerrentcar.status;
                $timeout(function () {
                    $scope.messagerentcar = null;
                    $scope.statusrentcar = null;
                }, 3000);
            })
        }else {
            $scope.messagerentcar = 'Obavezna polja su prazna!';
            $scope.statusrentcar = false;
            $timeout(function () {
                $scope.messagerentcar = null;
                $scope.statusrentcar = null;
            }, 3000);
        }
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
            for (let i = 0; i<$scope.cars.length; i++){
                if (id == $scope.cars[i].id){
                    one_day = $scope.cars[i].price.one_day;
                    two_days = $scope.cars[i].price.two_days;
                    three_days = $scope.cars[i].price.three_days;
                    seven_days = $scope.cars[i].price.seven_days;
                    fourteen_days = $scope.cars[i].price.fourteen_days;
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

    $scope.formatDate = function(date){
        let newDate = new Date(date),
            d = newDate.getDate(),
            m = newDate.getMonth()+1,
            y = newDate.getFullYear(),
            retDate;

        retDate = d+'/'+m+'/'+y;
        return retDate;
    }

    $scope.getprofileinfo = function(){
        $http.post('/getyourprofile').then(function (response) {
            $scope.yourprofile = response.data;
            if (typeof($scope.yourprofile) != false) {
                $scope.rent_car_name = $scope.yourprofile.name;
                $scope.rent_car_lastname = $scope.yourprofile.lastname;
                $scope.rent_car_email = $scope.yourprofile.email;

                if (typeof($scope.yourprofile.adress) == 'undefined'){
                    $scope.rent_car_adress = '';
                }else {
                    $scope.rent_car_adress = $scope.yourprofile.adress;
                }

                if ($scope.yourprofile.phone_number == null) {
                    $scope.rent_car_mobile_phone = '';
                } else {
                    $scope.rent_car_mobile_phone = $scope.yourprofile.phone_number;
                }
            }
        })
    }

    $scope.getprofileinfo();

    $scope.addComment = function (car_id, name, email, comment) {
        if (typeof (car_id) != 'undefined' && typeof (name) != 'undefined' && typeof (email) != 'undefined' && typeof (comment) != 'undefined'){
            $http.post('/addcomment', {car_id : car_id, name : name, email: email, comment: comment},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answeraddcomment = response.data;
                console.log(response.data)
                $scope.messageaddcomment = $scope.answeraddcomment.message;
                $scope.statusaddcomment = $scope.answeraddcomment.status;
                $timeout(function () {
                    $scope.messageaddcomment = null;
                    $scope.statusaddcomment = null;
                }, 3000);
            })
        }else {
            $scope.messageaddcomment = 'Obavezna polja su prazna!';
            $scope.statusaddcomment = false;
            $timeout(function () {
                $scope.messageaddcomment = null;
                $scope.statusaddcomment = null;
            }, 3000);
        }
    }

});