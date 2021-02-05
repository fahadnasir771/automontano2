<?php

namespace App\Http\Controllers\app;

use App\WorksheetObject;
use App\UserWorksheetObject;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class WorksheetObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('app.admin.worksheetobject.index', [
          'worksheet_objects' => WorksheetObject::get()
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
        return view('app.admin.worksheetobject.add', [
          'operators' => User::where('role', 3)->get(),
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
        //
        $data = new WorksheetObject();
        $data->title = $request->title;
        $data->min_time = $request->min_time;
        $data->max_time = $request->max_time;
        $data->save();
        $data->operators()->attach($request->operators);
        return redirect()->route('admin.worksheetobject.index')->with([
          'flashSuccess' => $request->title . ' worksheet object has been created succesfully'
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkSheetObject  $workSheetObject
     * @return \Illuminate\Http\Response
     */
    public function show(WorkSheetObject $workSheetObject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkSheetObject  $workSheetObject
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkSheetObject $workSheetObject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkSheetObject  $workSheetObject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkSheetObject $workSheetObject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkSheetObject  $workSheetObject
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkSheetObject $workSheetObject)
    {
        //
    }
}
