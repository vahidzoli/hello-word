<?php

namespace App\Http\Controllers;

use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        Mapper::map(35.7599644 , 51.40986264,['zoom' => 15, 'markers' => ['title' => 'My Location', 'animation' => 'DROP']]);

//        Mapper::map(35.7600092958542,51.409870348870754,['zoom' => 15, 'markers' => ['title' => 'My Location', 'animation' => 'DROP']]);

        //Mapper::map(35.7600092958542,51.409870348870754,['zoom' => 15, 'markers' => ['title' => 'My Location', 'animation' => 'DROP']]);


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

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return Response::json($response);
        }

        //return view('home');
    }
}
