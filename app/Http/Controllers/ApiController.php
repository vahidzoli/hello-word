<?php

namespace App\Http\Controllers;

use Mapper;
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
            "rel" => 'list',
            "method" => 'GET');

        $data[] = array(
            "href" => '/loc',
            "rel" => 'create',
            "method" => 'POST');

        $data[] = array(
            "href" => '/loc/{id}',
            "rel" => 'show',
            "method" => 'GET');


        $response = [
            'Success' => true,
            'version' => '1.0',
            'Data' => $data
        ];

        return Response::json($response);
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
            'lat' => 'required|between:0,99.99',
            'ing' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
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

            Mapper::map(53.381128999999990000, -1.470085000000040000);

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
                    'content' => 'you can update this!'
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
}
