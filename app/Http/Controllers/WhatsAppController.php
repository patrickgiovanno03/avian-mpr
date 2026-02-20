<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('whatsapp.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getQR(Request $request)
    {
        $response = Http::post('https://api.kirimi.id/v1/connect-device', [
            'user_code' => 'KMQ32X1225',
            'device_id' => 'D-YAGC9',
            'secret' => 'c81a73a176506d9f2916e7f706def8f0f18c5631c8354dbe0aa141aecfa2cad9',
        ]);

        return response()->json($response->json());
    }

    public function status(Request $request)
    {
        $response = Http::post('https://api.kirimi.id/v1/device-status', [
            'user_code' => 'KMQ32X1225',
            'device_id' => 'D-YAGC9',
            'secret' => 'c81a73a176506d9f2916e7f706def8f0f18c5631c8354dbe0aa141aecfa2cad9',
        ]);

        return response()->json($response->json());
    }
}
