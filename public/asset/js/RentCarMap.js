
AdminApp.controller('RentCarMap', function ($scope, $http, $rootScope) {
    var positions = [];
        $http.post('/getpositionofcar', {id :  $rootScope.renter_car_id}).then(function (response) {
            $scope.positions = response.data;
            console.log(response.data);
            for (let i = 0; i<$scope.positions.length; i++){
                positions.push(
                    {
                        time: 'Vreme: ' + $scope.positions[i].time,
                        lat: $scope.positions[i].x_position,
                        long: $scope.positions[i].y_position
                    }
                );
            }



            var mapOptions = {
                zoom: 11,
                center: new google.maps.LatLng(43.857354, 21.411862),
                mapTypeId: google.maps.MapTypeId.TERRAIN
            }

            $scope.map = new google.maps.Map(document.getElementById('map'), mapOptions);

            $scope.markers = [];

            var infoWindow = new google.maps.InfoWindow();

            var createMarker = function (info){

                var marker = new google.maps.Marker({
                    map: $scope.map,
                    position: new google.maps.LatLng(info.lat, info.long),
                    title: info.title
                });

                $scope.markers.push(marker);

            }

            for (i = 0; i < positions.length; i++){
                createMarker(positions[i]);
            }

            $scope.openInfoWindow = function(e, selectedMarker){
                e.preventDefault();
                google.maps.event.trigger(selectedMarker, 'click');
            }
        });


});




