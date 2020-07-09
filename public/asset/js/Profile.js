AdminApp.controller('Profile', function ($scope, $http, $timeout) {
    $scope.getYourProfile = function () {
        $http.post('/getyourprofile').then(function (response) {
            $scope.yourprofile = response.data;
            $scope.username = $scope.yourprofile.username;
            $scope.email = $scope.yourprofile.email;
            $scope.name = $scope.yourprofile.name;
            $scope.lastname = $scope.yourprofile.lastname;
            $scope.adress = $scope.yourprofile.adress

            if($scope.phone_number == null){
                $scope.phone_number = '';
            }else {
                $scope.phone_number = $scope.yourprofile.phone_number;
            }
            let dir = '/asset/img/avatars/';
            if($scope.yourprofile.avatar_name == null || $scope.yourprofile.avatar_name == ''){
                $scope.avatar_name = dir+'noavatar.jpg';
            }else {
                $scope.avatar_name = dir+$scope.yourprofile.avatar_name;
            }

        })
    }
    $scope.getYourProfile();

    $scope.UpdateUser = function (username, email, name, lastname, password, cpassword, phone_number, adress) {
        if(typeof(username) != 'undefined'){
            if(typeof(email) != 'undefined'){
                let fd = new FormData();
                fd.append('update_user_username', username);
                fd.append('update_user_email', email);
                fd.append('update_user_name', name);
                fd.append('update_user_lastname', lastname);
                fd.append('update_user_password', password);
                fd.append('update_user_cpassword', cpassword);
                fd.append('update_user_phone_num', phone_number);
                fd.append('update_user_adress', adress)
                let file = document.getElementById('updateavatar').files[0];
                fd.append('update_user_avatar', file);
                $http.post('/updateyourprofile', fd, {
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined, 'Process-Data': false}
                })
                    .then(function (response) {
                        $scope.answerupdateuser = response.data;
                        console.log(response.data);
                        $scope.messageupdateuser = $scope.answerupdateuser.message;
                        $scope.statusupdateuser = $scope.answerupdateuser.status;
                        $scope.getYourProfile();
                        $timeout(function () {
                            $scope.messageupdatecar = null;
                            $scope.statusupdatecar = null;
                        }, 3000);
                    }).catch(function () {
                    console.log("Failed add user!");
                    $scope.messageupdatecar = 'Neuspešan zahtev!';
                    $scope.statusupdatecar = false;
                    $timeout(function () {
                        $scope.messageupdatecar = null;
                        $scope.statusupdatecar = null;
                    }, 3000);
                })
            }else {
                $scope.messageupdatecar = 'Email je prazan!';
                $scope.statusupdatecar = false;
                $timeout(function () {
                    $scope.messageupdatecar = null;
                    $scope.statusupdatecar = null;
                }, 3000);
            }
        }else {
            $scope.messageupdatecar = 'Korisničko ime je prazno!';
            $scope.statusupdatecar = false;
            $timeout(function () {
                $scope.messageupdatecar = null;
                $scope.statusupdatecar = null;
            }, 3000);
        }

    }

    $scope.imageAvatar = function (e) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $scope.avatar_name = e.target.result;
            $scope.$apply();
        };

        reader.readAsDataURL(e.target.files[0]);
    };

})