<html>
<head>
    <title>Real Time Map</title>
    <style>
        #iw-container {
            margin-bottom: 10px;
        }
        #iw-container .iw-title {
            font-family: 'Open Sans Condensed', sans-serif;
            font-size: 22px;
            font-weight: 500;
            padding: 10px;
            background-color: #2a88bd;
            color: white;
            margin: 0;
            border-radius: 2px 2px 0 0;
        }
        #iw-container .iw-content {
            font-size: 13px;
            line-height: 18px;
            font-weight: 400;
            margin-right: 1px;
            padding: 15px 5px 20px 15px;
            max-height: 150px;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-scrollbar-thumb:green;
        }
        .iw-content img {
            float: left;
            margin: 0 10px 5px 5px;
        }
        .iw-subTitle {
            font-size: 19px;
            font-weight: 700;
            padding: 5px 0;
        }
        /*.iw-bottom-gradient {*/
            /*position: absolute;*/
            /*width: 326px;*/
            /*height: 25px;*/
            /*bottom: 10px;*/
            /*right: 18px;*/
            /*background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);*/
            /*background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);*/
            /*background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);*/
            /*background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);*/
        /*}*/
    </style>
</head>
<body dir="rtl">
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    <div id="map" style="width: 1000px; height: 600px;margin: 10px auto"></div>
                    <br>
                    {{--<input id='set' type="submit" value="Set Coordinate">--}}
                    {{--<div id="show-data"></div>--}}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var marker = null;

        function autoUpdate() {

            navigator.geolocation.getCurrentPosition(function(position) {
                var newPoint = new google.maps.LatLng(position.coords.latitude,
                    position.coords.longitude);

                if (marker) {
                    marker.setPosition(newPoint);
                }
                else {
                    var contentString = '<div id="iw-container">' +
                        '<div class="iw-title">شرکت شبکه نسل نوین</div>' +
                        '<div class="iw-content">' +
                        '<div class="iw-subTitle">توضیحات</div>' +
                        '<img src="http://maps.marnoto.com/en/5wayscustomizeinfowindow/images/vistalegre.jpg" alt="Porcelain Factory of Vista Alegre" height="115" width="85">' +
                        '<p>Founded in 1824, the Porcelain Factory of Vista Alegre was the first industrial unit dedicated to porcelain production in Portugal. For the foundation and success of this risky industrial development was crucial the spirit of persistence of its founder, José Ferreira Pinto Basto. Leading figure in Portuguese society of the nineteenth century farm owner, daring dealer, wisely incorporated the liberal ideas of the century, having become "the first example of free enterprise" in Portugal.</p>' +
                        '<div class="iw-subTitle">Contacts</div>' +
                        '<p>VISTA ALEGRE ATLANTIS, SA<br>3830-292 Ílhavo - Portugal<br>'+
                        '<br>Phone. +351 234 320 600<br>e-mail: geral@vaa.pt<br>www: www.myvistaalegre.com</p>'+
                        '</div>' +
                        '<div class="iw-bottom-gradient"></div>' +
                        '<form method="get" action="/tt"><input type="submit" value="Send Request"></form>'+
                        '</div>';

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString,
                        maxWidth: 400
                    });
                    // Marker does not exist - Create it
                    marker = new google.maps.Marker({
                        position: newPoint,
                        map: map
                    });
                }

                // Center the map on the new position
                map.setCenter(newPoint);

                marker.addListener('click', function() {
                    infowindow.open(map,marker);
                });

                var trafficLayer = new google.maps.TrafficLayer();
                trafficLayer.setMap(map);

            });

            // Call the autoUpdate() function every 5 seconds
            setTimeout(autoUpdate, 60000);
        }

        autoUpdate();
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
