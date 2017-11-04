<?php

namespace App\Http\Controllers;

use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Location\Coordinate;
use Location\Polygon;
use Location\Formatter\Polygon\GeoJSON;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();

        $data[] = array(
            "href" => '/loc',
            "rel" => 'location list',
            "method" => 'GET');

        $data[] = array(
            "href" => '/add',
            "rel" => 'add location',
            "method" => 'GET');

        $data[] = array(
            "href" => '/loc',
            "rel" => 'create location and save',
            "method" => 'POST');

        $data[] = array(
            "href" => '/loc/{id}',
            "rel" => 'show one location',
            "method" => 'GET');

        $data[] = array(
            "href" => '/edit/{id}',
            "rel" => 'edit location',
            "method" => 'GET');

        $data[] = array(
            "href" => '/loc/near',
            "rel" => 'show car available',
            "method" => 'GET');

        $response = [
            'Success' => true,
            'version' => '1.0',
            'Data' => $data
        ];

        return Response::json($response);
    }

    //show add location's form
    public function add()
    {
        return view('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /*
         * set validator on form's input
         */
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'lat' => 'required|regex:/[+-]?([0-9]*[.])?[0-9]+/',
            'ing' => 'required|regex:/[+-]?([0-9]*[.])?[0-9]+/'
        ]);

        /*
         * check validate
         */
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }else{

            //get info from user
            $title = $request->input('title');
            $lat = $request->input('lat');
            $ing = $request->input('ing');

            $locations = Location::where('title', $title)
                ->where('lat', $lat)
                ->where('ing', $ing)
                ->first();

            //check if location is exist
            if($locations) {

                $data[] = array("title" => "You are already verified",
                    "detail" => "You are verified, there is no need for verify again.",
                    "code" => 500);

                $response = [
                    'Success' => false,
                    'Data' => $data
                ];
            }
            else{

                $data[] = array('content' => "SMS code re-sent successfully");

                $response = [
                    'Success' => true,
                    'Data' => $data
                ];

                //insert new location to database
                $location = new Location();
                $location->title = $request->input('title');
                $location->lat = $request->input('lat');
                $location->ing = $request->input('ing');

                $location->save();
            }

            return Response::json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_list()
    {
        $locations = Location::all();
        $data = [];

        if(count($locations) > 0) {
            foreach ($locations as $key => $value) {
                $data[$key] = $value;
            }
        }else{
            $response = [
              'content' => 'there is no location!'
            ];
        }

        $response = [
            'Success' => true,
            'Data' => $data
        ];

        return Response::json($response);
    }

    public function edit($id)
    {
        $loc = Location::find($id);

        if($loc){
            $location = $loc;
        }else{
            $response = [
                'Success' => false,
                'content' => 'there is no location!'
            ];

            return Response::json($response);
        }

        return view('edit',compact('location'));
    }
    /**
     * Show the form for Update the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $loc = Location::find($id);

        $data = [];

        if(count($loc) > 0) {

            if($loc->title == 'Home' && $loc->lat =='23.32') {

                $loc->title = 'Street';

                $loc->update();

                $data[] = array(
                    'id'=>$loc->id,
                    'title'=>$loc->title,
                    'lat'=>$loc->lat,
                    'ing'=>$loc->ing
                );

                $response = [
                    'Success' => true,
                    'message' => 'Successfully update',
                    'Data' => $data
                ];

            }else{
                $response = [
                    'Success' => false,
                    'content' => 'you can\'t update this!'
                ];
            }

        }else{
            $response = [
                'Success' => false,
                'content' => 'there is no location!'
            ];
        }

        return Response::json($response);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_one($id)
    {
        $loc = Location::find($id);

        $data = [];

        if($loc) {
            $data[] = array(
                'id'=>$loc->id,
                'title'=>$loc->title,
                'lat'=>$loc->lat,
                'ing'=>$loc->ing
            );

            $response = [
                'Success' => true,
                'Data' => $data
            ];

        }else{
            $data = 'Not Found!';

            $response = [
                'Success' => false,
                'Data' => $data
            ];
        }


        return Response::json($response);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loc = Location::find($id);

        $loc->delete();

        $response = [
            'Success' => true,
            'message' => 'Successfully Deleted...',
        ];

        return Response::json($response);
    }


    public function nearby()
    {

        $client = new Client();

        $url = 'https://flipapp.org/api/v5/client/nearby';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjhkNWYyNjM4ZDBmYWRkZTlmN2MyZjhmZDIxODQyYjIxZDA2ZTE2MDBlY2ViYjQ5M2QzYzQ4M2ExNTA2YmU2YTBhMDRhN2Y5YTY0YTQ0ZmRhIn0.eyJhdWQiOiIxNjg3IiwianRpIjoiOGQ1ZjI2MzhkMGZhZGRlOWY3YzJmOGZkMjE4NDJiMjFkMDZlMTYwMGVjZWJiNDkzZDNjNDgzYTE1MDZiZTZhMGEwNGE3ZjlhNjRhNDRmZGEiLCJpYXQiOjE1MDgyMjg4ODcsIm5iZiI6MTUwODIyODg4NywiZXhwIjoxNTM5NzY0ODg3LCJzdWIiOiIxMjE5Iiwic2NvcGVzIjpbXX0.bjhBinlyyEwD5g5mhxA9Qs2bzc-AROGEKHRlaTACLQ_0Fjl15RgrzWsxnXxrIIdbdXP8sHGSYY5jY7pOmB_lglX9VBOKKQlTZ6EqLsrNriu_-avTKuPq_rBr9z1Wzxaey74B4ism_3pBdjP5FOf_97nkR88dqKQsfpsYRWJlzIQamLEl9JXt-QB4TIjY8RlmJ_LuaLwQcfElRzdqpxU6Gi9Og174QwiYBjpCXSnubb8XwbkSrrY-rHP-wDDtSEHvH9enwzHnOuEFr80u77hl5jV5eAaAeY9m0A-s4EXJX67Evm1tdvCXQ-h37VE0bEPU2OImFMA6qLnzM4sIwrRMfsbaVqWhA1BXVa_0KsTMvb6SHvT16nVFNmU819ugo5JG-VtiuoCt4aCRlhTJdsjTejQbNpE34uCYwAMUmRgaQue2qgm-JAtYni37cG4fw133Afx2G73IkzfSjjkYWiHkvCCmm1hdg1_WxIqBhQoUCy5XwNmneS0oR3d2O_nCNL1EvjbM6Ht0KQZOzHM1heW1mAh7HHB0cIcm40_6DVaxIc4kZVFxwY36Fux6YbIAQKsP6SjF40J_lk4m9TFBX18u6gOF9vnbs6VfrtTePHOkB9ofOvRXu3mdAoKo-jbdXmY0A-Fp_3wQzioRyOezLia4UuN3b78zWy6tM2qKiO_OUPU';

        $data = [
            'lat' => 35.7600092958542,
            'lng' => 51.409870348870754
        ];

        $header = array('Authorization' => 'Bearer ' . $token);

        $response = $client->post($url, array('query' => $data,'headers' => $header));

        $res = json_decode($response->getBody()->getContents());

        Mapper::map(35.75986646,51.40951362,['zoom' => 18, 'marker' => true]);

        foreach ($res->data as $key=>$value){
            foreach ($value->nearby as $k=>$val){
                Mapper::informationWindow($val->latitude,$val->longitude, 'Hi', ['icon' => $val->parent_icon, 'scale' => 1000]);
            }
        }

        return view('near');
    }

    public function ajax()
    {
        $lat = Input::get('lat');
        $lng = Input::get('lng');

        $data = [
            'lat' => $lat,
            'lng' => $lng
        ];

        $client = new Client();

        $url = 'https://flipapp.org/api/v5/client/nearby';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjhkNWYyNjM4ZDBmYWRkZTlmN2MyZjhmZDIxODQyYjIxZDA2ZTE2MDBlY2ViYjQ5M2QzYzQ4M2ExNTA2YmU2YTBhMDRhN2Y5YTY0YTQ0ZmRhIn0.eyJhdWQiOiIxNjg3IiwianRpIjoiOGQ1ZjI2MzhkMGZhZGRlOWY3YzJmOGZkMjE4NDJiMjFkMDZlMTYwMGVjZWJiNDkzZDNjNDgzYTE1MDZiZTZhMGEwNGE3ZjlhNjRhNDRmZGEiLCJpYXQiOjE1MDgyMjg4ODcsIm5iZiI6MTUwODIyODg4NywiZXhwIjoxNTM5NzY0ODg3LCJzdWIiOiIxMjE5Iiwic2NvcGVzIjpbXX0.bjhBinlyyEwD5g5mhxA9Qs2bzc-AROGEKHRlaTACLQ_0Fjl15RgrzWsxnXxrIIdbdXP8sHGSYY5jY7pOmB_lglX9VBOKKQlTZ6EqLsrNriu_-avTKuPq_rBr9z1Wzxaey74B4ism_3pBdjP5FOf_97nkR88dqKQsfpsYRWJlzIQamLEl9JXt-QB4TIjY8RlmJ_LuaLwQcfElRzdqpxU6Gi9Og174QwiYBjpCXSnubb8XwbkSrrY-rHP-wDDtSEHvH9enwzHnOuEFr80u77hl5jV5eAaAeY9m0A-s4EXJX67Evm1tdvCXQ-h37VE0bEPU2OImFMA6qLnzM4sIwrRMfsbaVqWhA1BXVa_0KsTMvb6SHvT16nVFNmU819ugo5JG-VtiuoCt4aCRlhTJdsjTejQbNpE34uCYwAMUmRgaQue2qgm-JAtYni37cG4fw133Afx2G73IkzfSjjkYWiHkvCCmm1hdg1_WxIqBhQoUCy5XwNmneS0oR3d2O_nCNL1EvjbM6Ht0KQZOzHM1heW1mAh7HHB0cIcm40_6DVaxIc4kZVFxwY36Fux6YbIAQKsP6SjF40J_lk4m9TFBX18u6gOF9vnbs6VfrtTePHOkB9ofOvRXu3mdAoKo-jbdXmY0A-Fp_3wQzioRyOezLia4UuN3b78zWy6tM2qKiO_OUPU';

        $cars = [
            'lat' => 35.7600092958542,
            'lng' => 51.409870348870754
        ];

        $header = array('Authorization' => 'Bearer ' . $token);

        $response = $client->post($url, array('query' => $cars,'headers' => $header));

        $temp =  json_decode($response->getBody()->getContents());

        $result['data'] = $data;

        foreach ($temp->data as $key=>$value){
            if(count($value->nearby) > 0) {
                foreach ($value->nearby as $k => $val) {
                    $result['cars'][$k]['lat'] = $val->latitude;
                    $result['cars'][$k]['lng'] = $val->longitude;
                    $result['cars'][$k]['icon'] = $val->parent_icon;
                }
            }else{
                $result['cars'][0]['lat'] = 0;
                $result['cars'][0]['lng'] = 0;
                $result['cars'][0]['icon'] = 0;
            }
        }

        $temp = json_decode('{"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[51.38031005859375,35.81001773806242],[51.25877380371093,35.77102915686019],[51.137237548828125,35.76824352632614],[51.10702514648437,35.74261114799056],[51.13037109374999,35.70805009803191],[51.23268127441406,35.65227488233256],[51.309585571289055,35.60483530498859],[51.375503540039055,35.60204386504707],[51.373443603515625,35.561277754384555],[51.458587646484375,35.5439598420039],[51.52793884277343,35.61600009092947],[51.52793884277343,35.70749253887843],[51.62200927734375,35.712510430860604],[51.639862060546875,35.758214448574186],[51.53617858886719,35.80222155213377],[51.46476745605469,35.827834717743585],[51.38031005859375,35.81001773806242]]]}}]}');
        $geofence = new Polygon();

        foreach ($temp->features as $key => $value){
            foreach ($value->geometry->coordinates as $k => $val){
                foreach ($val as $k1 => $val1) {
                    $geofence->addPoint(new Coordinate($val1[1],$val1[0]));
                    $result['coordinates'][$k1] = [$val1[1],$val1[0]];
                }
            }
        }

        $point = new Coordinate($lat,$lng);

        $result['check'] = $geofence->contains($point);

        return json_encode($result);

    }

    public function draw()
    {
        return view('draw');
    }

    public function set_coordinate()
    {
        $data = Input::get('result');

        return json_encode($data);
    }
}
