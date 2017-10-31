<?php

namespace App\Http\Controllers;

use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://flipapp.org/api/v5/client/nearby",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"lat\"\r\n\r\n35.7600092958542\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"lng\"\r\n\r\n51.409870348870754\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjhkNWYyNjM4ZDBmYWRkZTlmN2MyZjhmZDIxODQyYjIxZDA2ZTE2MDBlY2ViYjQ5M2QzYzQ4M2ExNTA2YmU2YTBhMDRhN2Y5YTY0YTQ0ZmRhIn0.eyJhdWQiOiIxNjg3IiwianRpIjoiOGQ1ZjI2MzhkMGZhZGRlOWY3YzJmOGZkMjE4NDJiMjFkMDZlMTYwMGVjZWJiNDkzZDNjNDgzYTE1MDZiZTZhMGEwNGE3ZjlhNjRhNDRmZGEiLCJpYXQiOjE1MDgyMjg4ODcsIm5iZiI6MTUwODIyODg4NywiZXhwIjoxNTM5NzY0ODg3LCJzdWIiOiIxMjE5Iiwic2NvcGVzIjpbXX0.bjhBinlyyEwD5g5mhxA9Qs2bzc-AROGEKHRlaTACLQ_0Fjl15RgrzWsxnXxrIIdbdXP8sHGSYY5jY7pOmB_lglX9VBOKKQlTZ6EqLsrNriu_-avTKuPq_rBr9z1Wzxaey74B4ism_3pBdjP5FOf_97nkR88dqKQsfpsYRWJlzIQamLEl9JXt-QB4TIjY8RlmJ_LuaLwQcfElRzdqpxU6Gi9Og174QwiYBjpCXSnubb8XwbkSrrY-rHP-wDDtSEHvH9enwzHnOuEFr80u77hl5jV5eAaAeY9m0A-s4EXJX67Evm1tdvCXQ-h37VE0bEPU2OImFMA6qLnzM4sIwrRMfsbaVqWhA1BXVa_0KsTMvb6SHvT16nVFNmU819ugo5JG-VtiuoCt4aCRlhTJdsjTejQbNpE34uCYwAMUmRgaQue2qgm-JAtYni37cG4fw133Afx2G73IkzfSjjkYWiHkvCCmm1hdg1_WxIqBhQoUCy5XwNmneS0oR3d2O_nCNL1EvjbM6Ht0KQZOzHM1heW1mAh7HHB0cIcm40_6DVaxIc4kZVFxwY36Fux6YbIAQKsP6SjF40J_lk4m9TFBX18u6gOF9vnbs6VfrtTePHOkB9ofOvRXu3mdAoKo-jbdXmY0A-Fp_3wQzioRyOezLia4UuN3b78zWy6tM2qKiO_OUPU",
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 5e941556-ae51-efca-5b9e-3c70fe1a2d6a"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            echo "cURL Error #:" . $err;
        }else{
            $res = json_decode($response);

            Mapper::map(35.75986646,51.40951362,['zoom' => 20, 'marker' => false]);

            foreach ($res->data as $key=>$value){
                foreach ($value->nearby as $k=>$val){
//                    Mapper::marker($val->latitude,$val->longitude,['markers' => ['icon' => $val->parent_icon, 'scale' => 1000, 'animation' => 'DROP']]);
                    Mapper::informationWindow($val->latitude,$val->longitude, 'Content', ['icon' => $val->parent_icon, 'scale' => 1000]);

                }
            }
        }

        curl_close($curl);

        return view('near');
    }
}
