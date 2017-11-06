<html>
    <head>
        <title>Draw</title>
        <style>
            table {
                border: 1px solid black;
                border-collapse: collapse;
                width: 700px;
            }
            th,td {
                border: 1px solid black;
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Favorite locations API</div>
                        <div class="panel-body">
                            <div id="draw-map" style="width: 900px; height: 600px;"></div>
                            <br>
                            <input id='set' type="submit" value="Set Coordinate">
                            <div id="show-data"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <script>

            function initMap() {
                var map = new google.maps.Map(document.getElementById('draw-map'), {
                    zoom: 5,
                    center: {lat: 35.585852, lng: 52.272949}
                });

                var myCoordinates = [
                    new google.maps.LatLng(35.227672,50.844727),
                    new google.maps.LatLng(35.209722,51.767578),
                    new google.maps.LatLng(35.317366,52.272949),
                    new google.maps.LatLng(35.585852,52.272949),
                    new google.maps.LatLng(35.764343,52.163086),
                    new google.maps.LatLng(36.208823,51.394043),
                    new google.maps.LatLng(35.335293,50.339355),
                    new google.maps.LatLng(35.871247,49.833984),
                    new google.maps.LatLng(36.403600,50.053711),
                    new google.maps.LatLng(36.403600,50.141602),
                    new google.maps.LatLng(36.403600,50.493164)
                ];
                var polyOptions = {
                    path: myCoordinates,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#0000FF",
                    fillOpacity: 0.6
                }
                var it = new google.maps.Polygon(polyOptions);
                it.setMap(map);
            }

        </script>

        <script>

//            function initMap() {
//                var map = new google.maps.Map(document.getElementById('draw-map'), {
//                    zoom: 5,
//                    center: {lat: 32.427908, lng: 53.688046},
//                    mapTypeId: 'terrain'
//                });
//
//                var triangleCoords = [
//                    {lat: 34.7777158, lng: 44.45068359},
//                    {lat: 38.47939467, lng: 51.87744141},
//                    {lat: 32.80574473, lng: 61.76513672},
//                    {lat: 25.99754992, lng: 54.47021484}
//                ];
//
//                // Construct the polygon.
//                var bermudaTriangle = new google.maps.Polygon({
//                    paths: triangleCoords,
//                    strokeColor: '#FF0000',
//                    strokeOpacity: 0.8,
//                    strokeWeight: 2,
//                    fillColor: '#FF0000',
//                    fillOpacity: 0.35,
//                    editable: true,
//                    draggable: true
//                });
//                bermudaTriangle.setMap(map);
//
//
//                function showArrays() {
//
//                    var vertices =  bermudaTriangle.getPath();
//
//                    var coordinates = [];
//
//                    for (var i = 0; i < vertices.getLength(); i++) {
//                        var xy = vertices.getAt(i);
//                        coordinates[i] = [xy.lat(),xy.lng()];
//
//                    }
//
//                    return coordinates;
//                }
//
//                $('#set').on('click',function () {
//                    $.ajax({
//                        type: "GET",
//                        url: '/api/set',
//                        data: {
//                            result: JSON.stringify(showArrays())
//                        },
//
//                        dataType: 'json',
//
//                        success: function (data) {
//                            $('#show-data').empty();
//
//                            data = $.parseJSON(data);
//
//                            console.log(data);
//                            $.each(data , function (key,value) {
//                                $('#show-data').append('['+value[1]+','+value[0]+'],');
//                            });
////                            $('#show-data').append('<hr><table id=\'tab\'><tr><th>Latitude</th><th>Longtitude</th>');
////                            $.each(data , function (key,value) {
////                                $('#tab').append('<tr><td>'+value[0]+'</td><td>'+value[1]+'</td></tr>');
////                            });
////                            $('#show-data').append('</table>');
//                        }
//                    });
//                });
//
//            }

        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtqWsq5Ai3GYv6dSa6311tZiYKlbYT4mw&callback=initMap">
        </script>

    </body>
</html>
