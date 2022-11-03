<?php

namespace App\Http\Controllers;

use App\Models\WorkPermitFile;
use Illuminate\Http\Request;

class WorkPermitFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\WorkPermitFile  $workPermitFile
     * @return \Illuminate\Http\Response
     */
    public function show(WorkPermitFile $workPermitFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkPermitFile  $workPermitFile
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkPermitFile $workPermitFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkPermitFile  $workPermitFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkPermitFile $workPermitFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkPermitFile  $workPermitFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkPermitFile $workPermitFile)
    {
        //
    }

    public function WorkPermitFileController($id, Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $fileName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);

        $workPermitFile = new WorkPermitFile;
        $workPermitFile->workPermitId = $id;
        $workPermitFile->name = $fileName;
        $workPermitFile->path = public_path('uploads') . '/' . $fileName;
        $workPermitFile->save();

        return back()
            ->with('success', 'You have successfully upload file.')
            ->with('file', $fileName);
    }
}
