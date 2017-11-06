<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script type="text/javascript">

        google.maps.event.addDomListener(window, 'load', new function() {
            setTimeout(function () {
                var flag = 0;
                var triangleInnerCoords = [];
                var triangleOuterCoords = [];

                //when user click move marker & check polygon
                google.maps.event.addListener(maps[0].map, 'click', function(event) {
                    maps[0].markers[0].setPosition(event.latLng);

                    $.ajax({
                        type: "GET",
                        url: '/api/ajax',
                        data: {
                            'lat' : event.latLng.lat(),
                            'lng' : event.latLng.lng()
                        },

                        dataType : 'json',

                        success: function(result){
                            var lat = parseFloat(result.data.lat);
                            var lng = parseFloat(result.data.lng);

                            var location = {lat: lat, lng: lng};

                            //set marker
                            var marker = new google.maps.Marker({
                                position: location,
                                setMap: maps[0]
                            });

                            console.log(result);
                            $.each(result.check , function (key,value) {
                                if(value === true){
                                    maps[0].map.setCenter({lat: event.latLng.lat(), lng: event.latLng.lng()});
                                    maps[0].map.setZoom(18);

                                }else{
                                    triangleInnerCoords = [];
                                    //triangleOuterCoords = [];
                                    $.each(result.innercoordinates, function (key, value) {
                                        var data = [];
                                        $.each(value , function (k,val) {
                                            data.push({
                                                lat: val[0],
                                                lng: val[1]
                                            });
                                        });
                                        triangleInnerCoords.push(data);
                                    });

                                    $.each(result.outercoordinates, function (key, value) {
                                        triangleOuterCoords.push({
                                            lat: value[0],
                                            lng: value[1]
                                        });
                                    });
                                    // Styling & Controls
                                    if(flag == 0) {
                                        $.each(triangleInnerCoords , function (key,value) {
                                            myPolygon = new google.maps.Polygon({
                                                paths: [value,triangleOuterCoords],
                                                draggable: false,
                                                strokeColor: '#ff0000',
                                                strokeOpacity: 0.8,
                                                strokeWeight: 2,
                                                fillColor: '#ff0000',
                                                fillOpacity: 0.35,
                                                visible: true
                                            });
                                            myPolygon.setMap(maps[0].map);
                                        });

                                        flag = 1;
                                    }
                                }
                            });
//                            if(result.check){
//                                maps[0].map.setCenter({lat: event.latLng.lat(), lng: event.latLng.lng()});
//                                maps[0].map.setZoom(18);
//
//                                if(flag == 1){
//                                    myPolygon = new google.maps.Polygon({
//                                        paths: [triangleInnerCoords,triangleOuterCoords],
//                                        draggable: false,
//                                        strokeColor: '#ff0000',
//                                        strokeOpacity: 0.8,
//                                        strokeWeight: 2,
//                                        fillColor: '#ff0000',
//                                        fillOpacity: 0.35,
//                                        visible: false
//                                    });
//                                    myPolygon.setMap(maps[0].map);
//
//                                    flag = 0;
//                                }
//                            }else{
//                                triangleInnerCoords = [];
//                                //triangleOuterCoords = [];
//
//                                $.each(result.innercoordinates, function (key, value) {
//                                    triangleInnerCoords.push({
//                                        lat: value[0],
//                                        lng: value[1]
//                                    });
//                                });
//
//                                $.each(result.outercoordinates, function (key, value) {
//                                    triangleOuterCoords.push({
//                                        lat: value[0],
//                                        lng: value[1]
//                                    });
//                                });
//                                // Styling & Controls
//                                if(flag == 0) {
//                                    myPolygon = new google.maps.Polygon({
//                                        paths: [triangleInnerCoords,triangleOuterCoords],
//                                        draggable: false,
//                                        strokeColor: '#ff0000',
//                                        strokeOpacity: 0.8,
//                                        strokeWeight: 2,
//                                        fillColor: '#ff0000',
//                                        fillOpacity: 0.35,
//                                        visible: true
//                                    });
//                                    myPolygon.setMap(maps[0].map);
//
//                                    flag = 1;
//                                }
//                            }

                        }
                    });
                });
            }, 500);
        });

    </script>

</body>
</html>
