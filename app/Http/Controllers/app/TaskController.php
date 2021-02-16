<?php

namespace App\Http\Controllers\app;

use App\Task;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('app.admin.task.index', [
          'tasks' => Task::get()
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
        return view('app.admin.task.add', [
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
        $data = new Task();
        $data->title = $request->title;
        $data->min_time = $request->min_time;
        $data->max_time = $request->max_time;
        $data->save();
        $data->operators()->attach($request->operators);
        return redirect()->route('admin.task.index')->with([
          'flashSuccess' => $request->title . ' task has been created succesfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('app.admin.task.edit', [
          'data' => Task::find($id),
          'operators' => User::where('role', 3)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $update = Task::find($id);
        $update->title = $request->title;
        $update->min_time = $request->min_time;
        $update->max_time = $request->max_time;
        $update->save();
        $update->operators()->detach();
        $update->operators()->attach($request->operators);
        return redirect()->route('admin.task.index')->with([
          'flashInfo' => $update->title . ' task has been updated succesfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      $data = Task::find($id);
      $data->delete();
      return redirect()->route('admin.task.index')->with([
        'flashDanger' => $data->title . ' task has been removed succesfully'
      ]);

    }
}
