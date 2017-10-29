<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Location;
use Illuminate\Support\Facades\Response;

class LocationController extends Controller
{
    public function index(Request $request){

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

                $data = array("title" => "You are already verified",
                    "detail" => "You are verified, there is no need for verify again.",
                    "code" => 500);

                $response = [
                    'Success' => false,
                    'Data' => $data
                ];
            }
            else{

                $data = array('content' => "SMS code re-sent successfully");

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
}
