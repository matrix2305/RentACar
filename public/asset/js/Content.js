AdminApp.controller('Content', function ($scope, $http, $timeout) {
    $scope.showinfo = function (){
        $http.post('/getinfo').then(function (response) {
            $scope.informations = response.data;
            $scope.informations = $scope.informations.informations.substring(0, $scope.informations.informations.length - 1 );
            $scope.update_info = $scope.informations;

        })
    }
    $scope.showinfo();


    $scope.showconinfo = function (){
        $http.post('/getconinfo').then(function (response) {
            $scope.coninfo = response.data[0];
            $scope.companyname = $scope.coninfo.name;
            $scope.companyemail = $scope.coninfo.email;
            $scope.companymphone = $scope.coninfo.mobile_number;
            $scope.companyfphone = $scope.coninfo.fix_number;
            $scope.companyadress = $scope.coninfo.adress;
            $scope.companyfburl = $scope.coninfo.facebook_url;
            $scope.companyinsturl = $scope.coninfo.instagram_url;
        })
    }
    $scope.showconinfo();

    $scope.updateInfo = function (info) {
        if(typeof(info) != 'undefined'){
            $http.post('/updateinfo', {info: info},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answerupinfo = response.data;
                    console.log(response.data);
                    $scope.messageupinfo = $scope.answerupinfo.message;
                    $scope.statusupinfo = $scope.answerupinfo.status;
                    $scope.showinfo();
                    $timeout(function () {
                        $scope.messageupinfo = null;
                        $scope.statusupinfo = null;
                    }, 3000);
                    $scope.updateinfotxt = false;
                }).catch(function () {
                console.log("Failed to update informations!");
                $scope.messageupinfo = 'Neuspešan zahtev!';
                $scope.statusupinfo = false;
                $timeout(function () {
                    $scope.messageupinfo = null;
                    $scope.statusupinfo = null;
                }, 3000);
            })
        }else {
            $scope.messageupinfo = 'Uneli ste prazno polje!'
            $scope.statusupinfo = false;
            $timeout(function () {
                $scope.messageupinfo = null;
                $scope.statusupinfo = null;
            }, 3000);
            $scope.updateinfotxt = true;
        }
    }

    $scope.updateConInfo = function (name, email, mob_phone, fix_phone, adress, fburl, insturl) {
        if(typeof(name) != 'undefined' && typeof(email) != 'undefined' && typeof(mob_phone) != 'undefined' && typeof(fix_phone) != 'undefined' && typeof(adress) != 'undefined'){
            $http.post('/updateconinfo', {name: name, email: email, mobile_phone: mob_phone, fix_phone: fix_phone, adress: adress, fburl: fburl, insturl: insturl},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answerupdateconinfo = response.data;
                    console.log(response.data);
                    $scope.messageupconinfo = $scope.answerupdateconinfo.message;
                    $scope.statusupconinfo = $scope.answerupdateconinfo.status;
                    $scope.showconinfo();
                    $timeout(function () {
                        $scope.messageupconinfo = null;
                        $scope.statusupconinfo = null;
                    }, 3000);
                    $scope.updateconinfo = false;
                }).catch(function () {
                console.log("Failed update brand!");
                $scope.messageupconinfo = 'Neuspešan zahtev!';
                $scope.statusupconinfo = false;
                $timeout(function () {
                    $scope.messageupconinfo = null;
                    $scope.statusupconinfo = null;
                }, 3000);
            })
        }else {
            $scope.messageupconinfo = 'Neka obavezna polja su prazna!';
            $scope.statusupconinfo = false;
            $timeout(function () {
                $scope.messageupconinfo = null;
                $scope.statusupconinfo = null;
            }, 3000);
            $scope.updateconinfo = true;
        }
    }


});