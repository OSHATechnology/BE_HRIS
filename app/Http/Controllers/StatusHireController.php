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
        // return view('status.index', compact('status' ));
        return response()->json([
            'code' => '200',
            'status'=> 'OK',
            'data' => $statusHires
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('status.create');
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
        // return redirect()->route('/');
        return response()->json([
            'code' => '200',
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
        // return view('status.show', compact('status'));
        return response()->json([
            'code' => '200',
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
        // return view('status.edit', compact('status'));
        return response()->json([
            'code' => '200',
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
        // return redirect()->route('status.index')
        return response()->json([
            'code' => '200',
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
        if ($status->delete()) {
            return response()->json([
                "message" => "detele success",
            ]);
        } else {
            dd("error");
        }
        
        // dd($status);
        // return redirect()->route('/');
    }
}
