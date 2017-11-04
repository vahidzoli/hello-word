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
                    center: {lat: 32.427908, lng: 53.688046},
                    mapTypeId: 'terrain'
                });

                var triangleCoords = [
                    {lat: 34.7777158, lng: 44.45068359},
                    {lat: 38.47939467, lng: 51.87744141},
                    {lat: 32.80574473, lng: 61.76513672},
                    {lat: 25.99754992, lng: 54.47021484}
                ];

                // Construct the polygon.
                var bermudaTriangle = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35,
                    editable: true,
                    draggable: true
                });
                bermudaTriangle.setMap(map);


                function showArrays() {

                    var vertices =  bermudaTriangle.getPath();

                    var coordinates = [];

                    for (var i = 0; i < vertices.getLength(); i++) {
                        var xy = vertices.getAt(i);
                        coordinates[i] = [xy.lat(),xy.lng()];

                    }

                    return coordinates;
                }

                $('#set').on('click',function () {
                    $.ajax({
                        type: "GET",
                        url: '/api/set',
                        data: {
                            result: JSON.stringify(showArrays())
                        },

                        dataType: 'json',

                        success: function (data) {
                            $('#show-data').empty();

                            data = $.parseJSON(data);

                            $('#show-data').append('<hr><table id=\'tab\'><tr><th>Latitude</th><th>Longtitude</th>');
                            $.each(data , function (key,value) {
                                $('#tab').append('<tr><td>'+value[0]+'</td><td>'+value[1]+'</td></tr>');
                            });
                            $('#show-data').append('</table>');
                        }
                    });
                });

            }

        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtqWsq5Ai3GYv6dSa6311tZiYKlbYT4mw&callback=initMap">
        </script>

    </body>
</html>
