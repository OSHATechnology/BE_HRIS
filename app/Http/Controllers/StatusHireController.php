<?php

namespace App\Http\Controllers;

use App\Models\StatusHire;
use Illuminate\Http\Request;

class StatusHireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statusHires = StatusHire::all();
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'data' => $statusHires
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $status = new StatusHire;
        $status->name = $request->name;
        $status->save();
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'data' => $status
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = StatusHire::find($id);
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'data' => $status
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = StatusHire::find($id);
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'data' => $status
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $status = StatusHire::find($id);
        $status->name = $request->name;
        $status->save();
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'message' => 'update success',
            'data' => $status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = StatusHire::find($id);
        $status->delete();
            return response()->json([
                'code' => 200,
                'status'=> 'OK',
                "message" => "detele success",
            ]);
    }
}
