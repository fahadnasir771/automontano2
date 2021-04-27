<?php

namespace App\Http\Controllers\app;

use App\Task;
use App\User;
use App\LateBar;
use App\JobTimer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
          'tasks' => Task::all(),
          'users' => User::all()
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
        // Global Variable
        $SEC_PX = 1.43020833;

        // Creating new task
        $task = new Task();
        $task->title = $request->title;
        $task->min_time = $request->min_time;
        $task->max_time = $request->max_time;
        $task->work_start = $request->work_start;
        $task->operator_id = $request->operator;
        $task->save();
        // $data->operators()->attach($request->operators);

        //  Create timers for each job
        $timer = new JobTimer();
        $timer->worksheet_job_id = 0;
        $timer->task_id = $task->id;
        $timer->html_id = 't' . $task->id . '-task';
        $timer->save();

        // INSERT TO LATE BAR 
        // Work start time variables
        $datetime_local = $request->work_start;
        // Time
        $time = substr($datetime_local, -5);
        $hr = explode(':', $time)[0];
        $min = explode(':', $datetime_local)[1];
        // Date
        $date_str = substr($datetime_local, 0, 10);
        $year = explode('-', $date_str)[0];
        $month = explode('-', $date_str)[1];
        $date = explode('-', $date_str)[2];

        $late_bar = new LateBar();
        $late_bar->user_id = $request->operator;

        # Calculating Left
        $late_bar_left = (($hr * 60 * 60) + ($min * 60)) * $SEC_PX;
        $late_bar->left = $late_bar_left;

        $late_bar->width = ($request->max_time * 60) * $SEC_PX;
        $late_bar->color = '#343434';
        $late_bar->text = 'Task#' . $task->id . ' (' . $request->title . ')';
        $late_bar->date = $date;
        $late_bar->month = $month;
        $late_bar->year = $year;
        
        $late_bar->save();

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
