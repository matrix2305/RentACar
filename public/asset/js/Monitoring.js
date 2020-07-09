AdminApp.controller('Monitoring', function ($scope, $http, $window, $timeout, $interval, $rootScope) {
    $scope.showrentedcars = function (){
        $http.post('/getrentedcars').then(function (response) {
            $scope.rentedcars = response.data;
            $scope.end_renting_times = [];
            let intevarls = [];
            if (include = 'monitoring' && $scope.rentedcars.length != 0 ){
                for(let i = 0; i<$scope.rentedcars.length; i++) {
                    intevarls[i]=$interval(function () {
                        let date = $scope.rentedcars[i].end_renting_date;
                        let endtime = new Date(date).getTime();
                        $scope.endtime = endtime;
                        let now = new Date().getTime();
                        let timer = endtime - now;
                        if(timer>0){
                            let days = Math.floor(timer / (1000 * 60 * 60 * 24));
                            let hours = Math.floor((timer % (1000 * 60 * 60)) / (1000 * 60 * 60));
                            let minutes = Math.floor((timer % (1000 * 60 * 60)) / (1000 * 60));
                            let seconds = Math.floor((timer % (1000*60) / 1000));
                            let push = days + ' d ' + hours + 'h : ' + minutes + ' m ' + seconds + ' s';
                            $scope.end_renting_times[i] = push;
                        }else{
                            let push  = 'ISTEKAO!'
                            $scope.end_renting_times[i] = push;
                        }
                    }, 1000);
                }
            }
        })
    }

    $scope.showrentedcars();
   $interval(function () {
        $scope.showrentedcars();
    }, 60000);


    $scope.showrentedcarsonhold = function (){
        $http.post('/getrentedcarsonhold').then(function (response) {
            $scope.rentedcarsonhold = response.data;
        })
    }

    $scope.showrentedcarsonhold();
    $interval(function () {
        $scope.showrentedcarsonhold();
    }, 60000);

    $scope.getmesages = function (){
        $http.post('/getcontactmessages').then(function (response) {
            $scope.messages = response.data;

        })
    }

    $scope.getmesages();
    $interval(function () {
        $scope.getmesages();
    }, 60000);


    $scope.showallowratings = function (){
        $http.post('/getratings').then(function (response) {
            $scope.allowratings = response.data;
        })
    }

    $scope.showallowratings();
    $interval(function () {
        $scope.showallowratings();
    }, 60000);


    $scope.getCommentsOnHold = function (){
        $http.post('/getcommentsonhold').then(function (response) {
            $scope.commentsonhold = response.data;
        })
    }

    $scope.getCommentsOnHold();
    $interval(function () {
        $scope.getCommentsOnHold();
    }, 6000)

    $scope.getAllowedComments = function (){
        $http.post('/getallowedcomments').then(function (response) {
            $scope.commentsallowed = response.data;
        })
    }

    $scope.getAllowedComments();


    $scope.showratingsonhold = function (){
        $http.post('/getaratingsonhold').then(function (response) {
            $scope.ratingsonhold = response.data;
        })
    }

    $scope.showratingsonhold();
    $interval(function () {
        $scope.showratingsonhold();
    }, 60000);

    $scope.disallowComment = function (id){
        $http.post('/disallowcomment', {id : id}).then(function (response) {
            $scope.answerDeleteComment = response.data;
            $scope.commentmessaage1 = $scope.answerDeleteComment.message;
            $scope.commentstatus1 = $scope.answerDeleteComment.status;
            $scope.getCommentsOnHold();
            $scope.getAllowedComments();
        })
    }

    $scope.deleteComment1 = function (id){
        $http.post('/deletecomment', {id : id}).then(function (response) {
            $scope.answerDeleteComment = response.data;
            $scope.commentmessaage1 = $scope.answerDeleteComment.message;
            $scope.commentstatus1 = $scope.answerDeleteComment.status;
            $scope.getCommentsOnHold();
            $scope.getAllowedComments();
        })
    }

    $scope.deleteComment2 = function (id){
        $http.post('/deletecomment', {id : id}).then(function (response) {
            $scope.answerDeleteComment = response.data;
            $scope.commentmessaage = $scope.answerDeleteComment.message;
            $scope.commentstatus = $scope.answerDeleteComment.status;
            $scope.getCommentsOnHold();
            $scope.getAllowedComments();
        })
    }

    $scope.showcars = function () {
        $http.post('/getavailablecars').then(function (response) {
            $scope.cars_list = response.data;
        })
    }
    $scope.showcars();
    $interval(function () {
        $scope.showcars();
    }, 60000);



    $scope.AllowComment = function (id){
        $http.post('/allowcomment', {id : id}).then(function (response) {
            $scope.commentanswer = response.data;
            $scope.commentmessaage = $scope.commentanswer.message;
            $scope.commentstatus = $scope.commentanswer.status;
            $scope.getCommentsOnHold();
            $scope.getAllowedComments();
        })
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


    $scope.editRentCar = function(x){
        $rootScope.rent_car_id = x.id
        $rootScope.editname = x.name;
        $rootScope.editlastname = x.lastname;
        $rootScope.editJMBG = x.JMBG;
        $rootScope.editEmail = x.email;
        $rootScope.editPhoneNum = x.phone_number;
        $rootScope.last_rent_car_price = x.price;
        $rootScope.editAdress = x.adress;
        $rootScope.editCarId = x.cars_id;
        $rootScope.include = 'editrentedcar';
        $rootScope.startTime = setInputDate(x.start_renting_date);
        $rootScope.endTime = setInputDate(x.end_renting_date);
    }

    $rootScope.rent_car_price = 0;

    $scope.editsendrentCar = function (rent_car_id, car_id, name, lastname, JMBG, email, mobile_phone, adress, price, rent_time_start, rent_time_end) {
        if(typeof(name) != 'undefined' && typeof(lastname) != 'undefined' && typeof(JMBG) != 'undefined' && typeof(email) != 'undefined' && typeof(mobile_phone) != 'undefined' && typeof(price) != 'undefined' && typeof(rent_time_start) != 'undefined' && typeof(rent_time_end) != 'undefined'){
            $http.post('/updaterentedcar', {rent_car_id: rent_car_id ,car_id: car_id, name: name, lastname: lastname, JMBG: JMBG, email: email, mobile_phone:mobile_phone, adress:adress, price:price, rent_time_start: $scope.rent_time_start, rent_time_end : $scope.rent_time_end},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerrentcar = response.data;
                console.log(response.data)
                $scope.messagerentcaredit = $scope.answerrentcar.message;
                $scope.statusrentcaredid = $scope.answerrentcar.status;
                $scope.showrentedcars();
                $scope.showrentedcarsonhold();
                $timeout(function () {
                    $scope.messagerentcaredit = null;
                    $scope.statusrentcaredid = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed rent car!");
                $scope.messagerentcaredit = 'Neuspešan zahtev!';
                $scope.statusrentcaredid = false;
                $timeout(function () {
                    $scope.messagerentcar = null;
                    $scope.statusrentcaredid = null;
                }, 3000);
            })
        }else {
            $scope.messagerentcaredit = 'Obavezna polja su prazna!';
            $scope.statusrentcaredid = false;
            $timeout(function () {
                $scope.messagerentcaredit = null;
                $scope.statusrentcaredid = null;
            }, 3000);
        }
    }



    $scope.allowcars = function (id) {
        $http.post('/allowcar', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerallowcar = response.data;
                console.log(response.data);
                $scope.table2message = $scope.answerallowcar.message;
                $scope.table2status = $scope.answerallowcar.status;
                $scope.showrentedcarsonhold();
                $scope.showrentedcars();
                $scope.showcars();
                $timeout(function () {
                    $scope.table2message = null;
                    $scope.table2status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed allow car!");
            $scope.table2message = 'Neuspešan zahtev!';
            $scope.table2status = false;
            $timeout(function () {
                $scope.table2message = null;
                $scope.table2status = null;
            }, 3000);
        })
    }

    $scope.disallowcars = function (id) {
        $http.post('/disallowcar', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdisallowcar = response.data;
                console.log(response.data);
                $scope.table1message = $scope.answerdisallowcar.message;
                $scope.table1status = $scope.answerdisallowcar.status;
                $scope.showrentedcarsonhold();
                $scope.showrentedcars();
                $scope.showcars();
                $timeout(function () {
                    $scope.table1message = null;
                    $scope.table1status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed disallow car!");
            $scope.table1message = 'Neuspešan zahtev!';
            $scope.table1status = false;
            $timeout(function () {
                $scope.table1message = null;
                $scope.table1status = null;
            }, 3000);
        })
    }


    $scope.deletercar = function (id) {
        $http.post('/deleterentedcar', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdeletecar1 = response.data;
                console.log(response.data);
                $scope.table1message = $scope.answerdeletecar1.message;
                $scope.table1status = $scope.answerdeletecar1.status;
                $scope.showrentedcarsonhold();
                $scope.showrentedcars();
                $scope.showcars();
                $timeout(function () {
                    $scope.table1message = null;
                    $scope.table1status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed delete rented car!");
            $scope.table1message = 'Neuspešan zahtev!';
            $scope.table1status = false;
            $timeout(function () {
                $scope.table1message = null;
                $scope.table1status = null;
            }, 3000);
        })
    }

    $scope.disallowcars1 = function (id) {
        $http.post('/disallowcar', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdisallowcar = response.data;
                console.log(response.data);
                $scope.table2message = $scope.answerdisallowcar.message;
                $scope.table2status = $scope.answerdisallowcar.status;
                $scope.showrentedcarsonhold();
                $scope.showrentedcars();
                $timeout(function () {
                    $scope.table2message = null;
                    $scope.table2status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed disallow car!");
            $scope.table2message = 'Neuspešan zahtev!';
            $scope.table2status = false;
            $timeout(function () {
                $scope.table2message = null;
                $scope.table2status = null;
            }, 3000);
        })
    }

    $scope.deletercar1 = function (id) {
        $http.post('/deleterentedcar', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdeletecar2 = response.data;
                console.log(response.data);
                $scope.table2message = $scope.answerdeletecar2.message;
                $scope.table2status = $scope.answerdeletecar2.status;
                $scope.showrentedcarsonhold();
                $scope.showrentedcars();
                $timeout(function () {
                    $scope.table2message = null;
                    $scope.table2status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed delete rented car!");
            $scope.table2message = 'Neuspešan zahtev!';
            $scope.table2status = false;
            $timeout(function () {
                $scope.table2message = null;
                $scope.table2status = null;
            }, 3000);
        })
    }

    $scope.prepareReplyMessage = function(x){
        $scope.name = x.name;
        $scope.lastname = x.lastname;
        $scope.email = x.email;
        $scope.message = x.message;
    }

    $scope.repplymessage = 'Nije u funkciji, nije podešen PHP SMTP!';
    $scope.statusreplymessage = false;

    $scope.RepplyMessage = function (message, email) {
        $http.post('/replymessage', {message : message, email: email},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerreplymessage = response.data;
                console.log(response.data);
                $scope.repplymessage = $scope.answerreplymessage.message;
                $scope.statusreplymessage = $scope.answerreplymessage.status;
                $scope.showrentedcarsonhold();
                $scope.showrentedcars();
                $timeout(function () {
                    $scope.repplymessage = null;
                    $scope.statusreplymessage = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed allow car!");
            $scope.repplymessage = 'Neuspešan zahtev!';
            $scope.statusreplymessage = false;
            $timeout(function () {
                $scope.repplymessage = null;
                $scope.statusreplymessage = null;
            }, 3000);
        })
    }

    $scope.rent_car_adress = '';
    $scope.rentCar = function (id, name, lastname, JMBG, email, mobile_phone, adress, price, rent_time_start, rent_time_end) {
        if(typeof(name) != 'undefined' && typeof(lastname) != 'undefined' && typeof(JMBG) != 'undefined' && typeof(email) != 'undefined' && typeof(mobile_phone) != 'undefined' && typeof(price) != 'undefined' && typeof(rent_time_start) != 'undefined' && typeof(rent_time_end) != 'undefined'){
            $http.post('/rentcar', {id: id, name: name, lastname: lastname, JMBG: JMBG, email: email, mobile_phone:mobile_phone, adress:adress, price:price, rent_time_start: rent_time_start, rent_time_end : rent_time_end},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerrentcar = response.data;
                console.log(response.data)
                $scope.messagerentcar = $scope.answerrentcar.message;
                $scope.statusrentcar = $scope.answerrentcar.status;
                $scope.showrentedcars();
                $scope.showrentedcarsonhold();
                $timeout(function () {
                    $scope.messagerentcar = null;
                    $scope.statusrentcar = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed rent car!");
                $scope.messagerentcar = 'Neuspešan zahtev!';
                $scope.statusrentcar = false;
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

    $scope.deleteMessage = function (id){
        $http.post('/deletemessage', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdeletemessage = response.data;
                console.log(response.data);
                $scope.deletemsgmessage = $scope.answerdeletemessage.message;
                $scope.deletemsgstatus = $scope.answerdeletemessage.status;
                $scope.getmesages();
                $timeout(function () {
                    $scope.deletemsgmessage = null;
                    $scope.deletemsgstatus = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed delete rented car!");
            $scope.deletemsgmessage = 'Neuspešan zahtev!';
            $scope.deletemsgstatus = false;
            $timeout(function () {
                $scope.deletemsgmessage = null;
                $scope.deletemsgstatus = null;
            }, 3000);
        })
    }

    $scope.allowRating = function (id) {
        $http.post('/allowrating', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdeletemessage = response.data;
                console.log(response.data);
                $scope.ratings1msg = $scope.answerdeletemessage.message;
                $scope.ratings1status = $scope.answerdeletemessage.status;
                $scope.showratingsonhold()
                $scope.showallowratings()
                $timeout(function () {
                    $scope.ratings1msg = null;
                    $scope.ratings1status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed delete rented car!");
            $scope.ratings1msg = 'Neuspešan zahtev!';
            $scope.ratings1status = false;
            $timeout(function () {
                $scope.ratings1msg = null;
                $scope.ratings1status = null;
            }, 3000);
        })
    }

    $scope.deleteRating = function (id) {
        $http.post('/deleterating', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdeletemessage = response.data;
                console.log(response.data);
                $scope.ratings1msg = $scope.answerdeletemessage.message;
                $scope.ratings1status = $scope.answerdeletemessage.status;
                $scope.showratingsonhold()
                $scope.showallowratings()
                $timeout(function () {
                    $scope.ratings1msg = null;
                    $scope.ratings1status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed delete rented car!");
            $scope.ratings1msg = 'Neuspešan zahtev!';
            $scope.ratings1status = false;
            $timeout(function () {
                $scope.ratings1msg = null;
                $scope.ratings1status = null;
            }, 3000);
        })
    }

    $scope.deleteRating2 = function (id) {
        $http.post('/deleterating', {id : id},
            {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (response) {
                $scope.answerdeletemessage2 = response.data;
                console.log(response.data);
                $scope.ratings2msg = $scope.answerdeletemessage.message;
                $scope.ratings2status = $scope.answerdeletemessage.status;
                $scope.showratingsonhold()
                $scope.showallowratings()
                $timeout(function () {
                    $scope.ratings2msg = null;
                    $scope.ratings2status = null;
                }, 3000);
            }).catch(function () {
            console.log("Failed delete rented car!");
            $scope.ratings2msg = 'Neuspešan zahtev!';
            $scope.ratings2status = false;
            $timeout(function () {
                $scope.ratings2msg = null;
                $scope.ratings2status = null;
            }, 3000);
        })
    }

    $scope.prepareMapCar = function (x) {
        $rootScope.map_car_name = x.car.brand.brand_name + ' ' + x.car.model;
        $rootScope.map_renter_name = x.name + ' ' + x.lastname;
        $rootScope.renter_car_id = x.id;
    }
});