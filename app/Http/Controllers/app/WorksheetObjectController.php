<?php

namespace App\Http\Controllers\app;

use App\WorksheetObject;
use App\UserWorksheetObject;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
          'worksheet_objects' => WorksheetObject::get(),
          'route' => Route::current()->uri //as this controller is being used for 2 roles so we need to redirect to the repective route for post requestz
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(str_contains(Route::current()->uri, 'admin/') ? true : false);
        
        return view('app.admin.worksheetobject.add', [
          'operators' => User::where('role', 3)->get(),
          'route' => Route::current()->uri //as this controller is being used for 2 roles so we need to redirect to the repective route for post request
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
        // dd(User::where('role', 3)->all()->id);
        $data = new WorksheetObject();
        $data->title = $request->title;
        $data->min_time = $request->min_time;
        $data->max_time = $request->max_time;
        $data->save();
        
        $data->operators()->attach($request->operators);
        if(str_contains(Route::current()->uri, 'admin/')){
          return redirect()->route('admin.worksheetobject.index')->with([
            'flashSuccess' => $request->title . ' worksheet object has been created succesfully'
          ]);
        }else{
          return redirect()->route('acceptor.worksheetobject.index')->with([
            'flashSuccess' => $request->title . ' worksheet object has been created succesfully'
          ]);
        }

        
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
    public function edit($id)
    {
        //
        return view('app.admin.worksheetobject.edit', [
          'data' => WorksheetObject::find($id),
          'operators' => User::where('role', 3)->get(),
          'route' => Route::current()->uri //as this controller is being used for 2 roles so we need to redirect to the repective route for post requestz
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkSheetObject  $workSheetObject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $update = WorksheetObject::find($id);
        $update->title = $request->title;
        $update->min_time = $request->min_time;
        $update->max_time = $request->max_time;
        $update->save();
        $update->operators()->detach();
        $update->operators()->attach($request->operators);

        if(str_contains(Route::current()->uri, 'admin/')){
          return redirect()->route('admin.worksheetobject.index')->with([
            'flashInfo' => $update->title . ' worksheet object has been updated succesfully'
          ]);
        }else{
          return redirect()->route('acceptor.worksheetobject.index')->with([
            'flashInfo' => $update->title . ' worksheet object has been updated succesfully'
          ]);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkSheetObject  $workSheetObject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data = WorksheetObject::find($id);
        $data->delete();
        if(str_contains(Route::current()->uri, 'admin/')){
          return redirect()->route('admin.worksheetobject.index')->with([
            'flashDanger' => $data->title . ' worksheet object has been deleted succesfully'
          ]);
        }else{
          return redirect()->route('acceptor.worksheetobject.index')->with([
            'flashDanger' => $data->title . ' worksheet object has been deleted succesfully'
          ]);
        }

    }
}
