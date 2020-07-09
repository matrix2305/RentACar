AdminApp.controller('Users', function ($scope, $http, $window, $timeout) {
    $scope.showusers = function () {
        $http.post('/getusers', {}).then(function (response) {
            $scope.users_list = response.data;
            console.log(response.data);
        })
    }
    $scope.showusers();

    $scope.showroles = function () {
        $http.post('/getroles', {}).then(function (response) {
            $scope.role_list = response.data;
            console.log(response.data)
        })
    }
    $scope.showroles();

    $scope.imageAvatar = function (e) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $scope.updateimgav = e.target.result;
            $scope.$apply();
        };

        reader.readAsDataURL(e.target.files[0]);
    };

    $scope.deleteUser = function (id, username){
        if ($window.confirm('Da li sigurno brišete korisnika ' + username + '?')) {
            $http.post('/deleteuser', {id: id},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerdeleteuser = response.data;
                console.log(response.data);
                $scope.messagedeluser = $scope.answerdeleteuser.message;
                $scope.statusdeluser = $scope.answerdeleteuser.status;
                $scope.showusers();
                $timeout(function () {
                    $scope.messagedeluser = null;
                    $scope.statusdeluser = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed delete user!");
                $scope.messagedeluser = 'Neuspešan zahtev!';
                $scope.statusdeluser = false;
                $timeout(function () {
                    $scope.messagedeluser = null;
                    $scope.statusdeluser = null;
                }, 3000);
            })
        }
    }

    $scope.prepareUserEdit = function (x) {
        $scope.updateUserId = x.id;
        $scope.updateUsername = x.username;
        $scope.updateEmail = x.email;
        $scope.updateName = x.name;
        $scope.updateLastname = x.lastname;
        $scope.updateUserRole = x.roles_id;
        if(x.phone_number == 'null' ){
            $scope.updatePhoneNum = '';
        }else {
            $scope.updatePhoneNum = x.phone_number;
        }
        $scope.updateimgav = '/asset/img/avatars/' + x.avatar_name;
    }

    $scope.AddUser = function (username, name, lastname, email, role_id, password, cpassword, adress) {
        if(typeof(username) != 'undefined' && typeof(name) != 'undefined' && typeof(lastname) != 'undefined' && typeof(email) != 'undefined' && typeof(role_id) != 'undefined' && typeof(password) != 'undefined' && typeof(cpassword) != 'undefined'){
            if(password == cpassword){
                $http.post('/adduser', {username: username, name: name, lastname: lastname, email: email, role_id: role_id, password: password, cpassword: cpassword, adress: adress},
                    {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                    .then(function (response) {
                        $scope.answeradduser = response.data;
                        console.log(response.data);
                        $scope.messageadduser = $scope.answeradduser.message;
                        $scope.statusadduser = $scope.answeradduser.status;
                        $scope.passfocus = false;
                        $scope.showusers();
                        $timeout(function () {
                            $scope.messageadduser = null;
                            $scope.statusadduser = null;
                        }, 3000);
                    }).catch(function () {
                    console.log("Failed add user!");
                    $scope.messageadduser = 'Neuspešan zahtev!';
                    $scope.statusadduser = false;
                    $timeout(function () {
                        $scope.messageadduser = null;
                        $scope.statusadduser = null;
                    }, 3000);
                })
            }else {
                $scope.messageadduser = 'Lozinke nisu poklapajuce!';
                $scope.statusadduser = false;
                $scope.passfocus = true;
                $timeout(function () {
                    $scope.messageadduser = null;
                    $scope.statusadduser = null;
                }, 3000);
            }
        }else{
            $scope.messageadduser = 'Niste uneli sva polja!';
            $scope.statusadduser = false;
            $scope.passfocus = true;
            $timeout(function () {
                $scope.messageadduser = null;
                $scope.statusadduser = null;
            }, 3000);
        }
    }

    $scope.UpdateUser = function (id, username, email, name, lastname, password, cpassword, phone_number, roles_id, adress) {
        if(typeof(username) != 'undefined'){
            if(typeof(email) != 'undefined'){
                let fd = new FormData();
                fd.append('update_user_id', id);
                fd.append('update_user_username', username);
                fd.append('update_user_email', email);
                fd.append('update_user_name', name);
                fd.append('update_user_lastname', lastname);
                fd.append('update_user_password', password);
                fd.append('update_user_cpassword', cpassword);
                fd.append('update_user_phone_num', phone_number);
                fd.append('update_user_role_id', roles_id);
                fd.append('update_user_adress', adress)
                let file = document.getElementById('update_avatar_user').files[0];
                fd.append('update_user_avatar', file);
                $http.post('/updateuser', fd, {
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined, 'Process-Data': false}
                })
                    .then(function (response) {
                        $scope.answerupdateuser = response.data;
                        console.log(response.data);
                        $scope.messageupdateuser = $scope.answerupdateuser.message;
                        $scope.statusupdateuser = $scope.answerupdateuser.status;
                        $scope.showusers();
                        $timeout(function () {
                            $scope.messageupdateuser = null;
                            $scope.statusupdateuser = null;
                        }, 3000);
                    }).catch(function () {
                    console.log("Failed add user!");
                    $scope.messageupdateuser = 'Neuspešan zahtev!';
                    $scope.statusupdateuser = false;
                    $timeout(function () {
                        $scope.messageupdateuser = null;
                        $scope.statusupdateuser = null;
                    }, 3000);
                })
            }else {
                $scope.messageupdateuser = 'Email je prazan!';
                $scope.statusupdateuser = false;
                $timeout(function () {
                    $scope.messageupdateuser = null;
                    $scope.statusupdateuser = null;
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

});