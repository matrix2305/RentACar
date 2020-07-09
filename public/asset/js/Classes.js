AdminApp.controller ('Classes', function ($scope, $http, $window, $timeout) {
    $scope.showclasses = function () {
        $http.post('/getclasses').then(function (response) {
            $scope.classes_list = response.data;
        })
    }
    $scope.showclasses();

    $scope.AddClass = function (class_name, class_color) {
        if(typeof(class_name) != 'undefined' && typeof(class_color) != 'undefined'){
            $http.post('/addclass', {class_name: class_name, class_color: class_color},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answeraddclass = response.data;
                    console.log(response.data);
                    $scope.messageaddclass = $scope.answeraddclass.message;
                    $scope.statusaddclass = $scope.answeraddclass.status;
                    $scope.classfocus = false;
                    $scope.showclasses();
                    $timeout(function () {
                        $scope.messageaddclass = null;
                        $scope.statusaddclass = null;
                    }, 3000);
                }).catch(function () {
                console.log("Failed add class!");
                $scope.messageaddclass = 'Neuspešan zahtev!';
                $scope.statusaddclass = false;
                $timeout(function () {
                    $scope.messageaddclass = null;
                    $scope.statusaddclass = null;
                }, 3000);
            })
        }else {
            $scope.messageaddclass = 'Uneli ste prazno polje!';
            $scope.statusaddclass = false;
            $scope.classfocus = true;
            $timeout(function () {
                $scope.messageaddclass = null;
                $scope.statusaddclass = null;
            }, 3000);
        }

    }

    $scope.UpdateClass = function (id, class_name, class_color) {
        if(typeof(id) != 'undefined' && typeof(class_name) != 'undefined' && typeof(class_color) != 'undefined'){
            $http.post('/updateclass', {id: id, class_name: class_name, class_color: class_color},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                .then(function (response) {
                    $scope.answerupdateclass = response.data;
                    console.log(response.data);
                    $scope.messageupdateclass = $scope.answerupdateclass.message;
                    $scope.statusupdateclss = $scope.answerupdateclass.status
                    $scope.classufocus = false;
                    $scope.showclasses();
                    $timeout(function () {
                        $scope.messageupdateclass = null;
                        $scope.statusupdateclss = null;
                    }, 3000);
                }).catch(function () {
                console.log("Failed update class!");
                $scope.messageupdateclass = 'Neuspešan zahtev!';
                $scope.statusupdateclss = false;
                $timeout(function () {
                    $scope.messageupdateclass = null;
                    $scope.statusupdateclss = null;
                }, 3000);
            })
        }else {
            $scope.messageupdateclass = 'Uneta polja su prazna!';
            $scope.statusupdateclss = false;
            $scope.classufocus = true;
            $timeout(function () {
                $scope.messageupdateclass = null;
                $scope.statusupdateclss = null;
            }, 3000);
        }

    }

    $scope.deleteClass = function (id, classs) {
        if ($window.confirm('Da li sigurno brišete klasu ' + classs + '?')) {
            $http.post('/deleteclass', {id: id},
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                $scope.answerdeleteclass = response.data;
                $scope.messagedelclass = $scope.answerdeleteclass.message;
                $scope.statusdelclass = $scope.answerdeleteclass.status;
                $scope.showclasses();
                $timeout(function () {
                    $scope.messagedelclass = null;
                    $scope.statusdelclass = null;
                }, 3000);
            }).catch(function () {
                console.log("Failed delete class!");
                $scope.messagedelclass = 'Neuspešan zahtev!';
                $scope.statusdelclass = false;
                $timeout(function () {
                    $scope.messagedelclass = null;
                    $scope.statusdelclass = null;
                }, 3000);
            })
        }
    }
});