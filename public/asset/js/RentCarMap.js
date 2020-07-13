
AdminApp.controller('RentCarMap', function ($scope, $http, $rootScope) {
    var positions = [];
        $http.post('/getpositionofcar', {id :  $rootScope.renter_car_id}).then(function (response) {
            $scope.positions = response.data;
            console.log(response.data);
            for (let i = 0; i<$scope.positions.length; i++){
                positions.push(
                    {
                        title: 'Vreme:',
                        desc: $scope.positions[i].time,
                        lat: $scope.positions[i].x_position,
                        long: $scope.positions[i].y_position
                    }
                );
            }



            let mapOptions = {
                zoom: 11,
                center: new google.maps.LatLng(43.857354, 21.411862),
                mapTypeId: google.maps.MapTypeId.TERRAIN
            }

            $scope.map = new google.maps.Map(document.getElementById('map'), mapOptions);

            $scope.markers = [];

            let infoWindow = new google.maps.InfoWindow();

            let createMarker = function (info){

                var marker = new google.maps.Marker({
                    map: $scope.map,
                    position: new google.maps.LatLng(info.lat, info.long),
                    title: info.title
                });
                marker.content = '<div class="infoWindowContent">' + info.desc + '</div>';

                google.maps.event.addListener(marker, 'click', function(){
                    infoWindow.setContent('<h2>' + marker.title + '</h2>' + marker.content);
                    infoWindow.open($scope.map, marker);
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




