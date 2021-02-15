<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\JobTimer;
use App\User;
use App\Worksheet;
use App\WorksheetJob;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function worksheets()
    {
        $operators_jobs = WorksheetJob::with('timer')->where('operator_id', Auth::user()->id)->get();
        $in_progress_found = false;
        for ($i=0; $i < count($operators_jobs); $i++) { 
            if($operators_jobs[$i]['timer']->in_progress == 1){
                $in_progress = $operators_jobs[$i]['timer'];
                $in_progress_found = true;
            }
        }
        if(!$in_progress_found){
            $in_progress = false;
        }
        // dd($in_progress);
        return view('app.operator.worksheets')->with([
            'timer_continue' => $in_progress,
            'worksheets' => Worksheet::with(['jobs.timer','jobs.object'])->where('customer_accepted', 1)->get()
        ]);
    }

   

    public function timer(Request $request)
    {
        

        //timer start function
        if($request->started == 1){
            $timer_data = JobTimer::where('html_id', $request->html_id)->first();
            $job_data = WorksheetJob::find($timer_data->worksheet_job_id);
            $worksheet_data = Worksheet::find($job_data->worksheet_id);

            $startDate = time();
            $expiry = date('Y-m-d H:i:s', strtotime('+' . $worksheet_data->days_required . ' day', $startDate)); // to calculate dluvery date
            
            Worksheet::where('id', $worksheet_data->id)->update([
                //worksheet 
                'work_started' => Carbon::now(),
                'work_actually_started' => 1,
                'delivery_date' => $expiry
            ]);

            WorksheetJob::where('id', $job_data->id)->update([
                //job
                'started' => 1
            ]);

            JobTimer::where('html_id', $request->html_id)->update([
                //Job Timer
                'started' => 1,
                'in_progress' => 1,
                'min_at' => time() + $request->min_at,
                'max_at' => time() + $request->max_at,
                'started_at' => time()
            ]);

        }

        //timer finish function 
        if($request->finished == 1){
            $timer_data = JobTimer::where('html_id', $request->html_id)->first();
            $job_data = WorksheetJob::find($timer_data->worksheet_job_id);

            WorksheetJob::where('id', $job_data->id)->update([
                //job
                'completed' => 1,
            ]);

            JobTimer::where('html_id', $request->html_id)->update([
                //timer
                'in_progress' => 0,
                'finished' => 1,
                'finished_at' => time()
            ]);

        }
    }
}
