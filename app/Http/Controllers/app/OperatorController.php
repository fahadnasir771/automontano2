<?php

namespace App\Http\Controllers\app;

use App\User;
use DateTime;
use App\MainBar;
use App\JobTimer;
use App\Worksheet;
use Carbon\Carbon;
use Pusher\Pusher;
use App\WorksheetJob;
use App\WorksheetObject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SecondaryBar;
use App\WorksheetFailure;
use App\WorksheetVehicle;
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

        return view('app.operator.worksheets')->with([
            'timer_continue' => $in_progress,
            'worksheets' => Worksheet::with(['jobs.timer','jobs.object'])->where('customer_accepted', 1)->get()
        ]);
    }

   

    public function timer(Request $request)
    {

        // Global Variable
        $SEC_PX = 1.43020833;
        
        // Pusher to start work
        $options = [
            'cluster' => 'ap2',
            'useTLS' => true
        ];
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        //timer start function
        if($request->started == 1){

            // Checking if main bar already exists
            $check_if_exists = MainBar::where('html_id_verify', explode('-', $request->html_id)[0])
                                ->where('user_id', Auth::id())
                                ->first();

            $append_html = false;
            $data = array();
            if(is_null($check_if_exists)){
                $append_html = true;

                // Insert into main bar
                $main_bar = new MainBar();
                $main_bar->user_id = Auth::id();
                $main_bar->html_id_verify = explode('-', $request->html_id)[0];
                $main_bar->left = ((date('H') * 60 * 60) + (date('i') * 60) + date('s')) * $SEC_PX;

                // Calculating width
                $timer_data1 = JobTimer::where('html_id', $request->html_id)->first();
                $job_data1 = WorksheetJob::find($timer_data1->worksheet_job_id);
                $relatable_worksheets = WorksheetJob::where('worksheet_id', $job_data1->worksheet_id)->get();
                $objects1 = array();
                for($i=0; $i < count($relatable_worksheets); $i++){
                    $objects1[$i]['operator'] = $relatable_worksheets[$i]['operator_id'];
                    $objects1[$i]['object'] = $relatable_worksheets[$i]['object_id'];
                }
                $same = array();
                $sum = array();
                for($i=0; $i < count($objects1); $i++){

                    if(in_array($objects1[$i]['operator'], $same)){
                        for ($j=0; $j < count($sum); $j++) { 
                            if($sum[$j]['operator'] == $objects1[$i]['operator']){
                                $object2 = WorksheetObject::find($objects1[$i]['object']);
                                $calc2 = ($object2->max_time * 60) * $SEC_PX;

                                $sum[$j]['width'] += $calc2;
                                $sum[$j]['jobs'] += 1;
                                break;
                            }
                        }
                    }else{
                        $object1 = WorksheetObject::find($objects1[$i]['object']);
                        $calc1 = ($object1->max_time * 60) * $SEC_PX;

                        $sum[$i]['operator'] = $objects1[$i]['operator'];
                        $sum[$i]['width'] = $calc1;
                        $sum[$i]['jobs'] = 1;
                        array_push($same, $objects1[$i]['operator']);
                    }
                    
                }
                $sum = array_values($sum); 
                for($i=0; $i < count($sum); $i++){
                    
                    if($sum[$i]['operator'] == Auth::id()){
                        $main_bar->width = $sum[$i]['width'];
                        $main_bar->jobs = $sum[$i]['jobs'];
                        break;
                    }
                }

                $main_bar->color = 'royalblue';
                $main_bar->mode = 'work-not-started';
                
                $main_bar->jobs_done = 0;
                // The objects id will be the auto incerement id
                $license_plate_text = WorksheetVehicle::where('worksheet_id', $job_data1->worksheet_id)->first();
                $main_bar->text = $license_plate_text->license_plate;
                $main_bar->date = date('d');
                $main_bar->month = date('m');
                $main_bar->year = date('Y');
                $main_bar->save();

                // Insert into secondary bar
                $secondary_bar_objects = array();
                for($i=0; $i < count($objects1); $i++){
                    if($objects1[$i]['operator'] == Auth::id()){
                        $object4 = WorksheetObject::find($objects1[$i]['object']);
                        $calc4 = ($object4->max_time * 60) * $SEC_PX;

                        $secondary_bar_objects[$i]['title'] = $object4->title;
                        $secondary_bar_objects[$i]['width'] = $calc4;
                    }
                }
                
                $secondary_bar_objects = array_values($secondary_bar_objects);
                $secondary_bar_html = array();
                $THE_LEFT = $main_bar->left;

                for($i=0; $i < count($secondary_bar_objects); $i++){
                    $secondary_bar = new SecondaryBar();
                    $secondary_bar->user_id = Auth::id();
                    $secondary_bar->left = $THE_LEFT;
                    $secondary_bar->width = $secondary_bar_objects[$i]['width'];
                    $THE_LEFT = $secondary_bar->left + $secondary_bar->width;
                    $secondary_bar->color = '#434343';
                    $secondary_bar->mode = 'pre-object';
                    $secondary_bar->mode2 = '';
                    $secondary_bar->worksheet_id = $main_bar->id;
                    $secondary_bar->objects_index = $i+1;
                    $secondary_bar->status = 0;
                    if(count($secondary_bar_objects) == 1){
                        $secondary_bar->position = 'last';
                    }else{
                        if($i == 0 ){
                            $secondary_bar->position = 'first';
                        }elseif($i == (count($secondary_bar_objects) - 1)){
                            $secondary_bar->position = 'last';
                        }else{
                            $secondary_bar->position = 'middle';
                        }
                    }
                    
                    $secondary_bar->text = $secondary_bar_objects[$i]['title'];
                    $secondary_bar->date = date('d');
                    $secondary_bar->month = date('m');
                    $secondary_bar->year = date('Y');
                    $secondary_bar->save();

                    array_push($secondary_bar_html, '<div class="bar secondary-bar" data-left="' . $secondary_bar->left . '" data-color="#434343" data-width="' . $secondary_bar->width . '" data-mode="pre-object" data-worksheet-id="' . $secondary_bar->worksheet_id . '" data-object-index="' . $secondary_bar->objects_index . '" data-status="0" data-position="' . $secondary_bar->position . '" data-mode2="" data-id="' . $secondary_bar->id . '" style="display: none">' . $secondary_bar->text . '</div>');
                }
                $data = [
                    'started' => 1,
                    'main' => '<div class="bar main-bar" data-left="' . $main_bar->left . '"  data-color="royalblue" data-width="' . $main_bar->width . '" data-mode="work-not-started"  data-jobs="' . $main_bar->jobs . '" data-jobs-done="0" data-objects-id="' . $main_bar->id . '" data-id="' . $main_bar->id . '">' . $main_bar->text . '</div>',
                    'operator' => Auth::id(),
                    'secondary' => $secondary_bar_html,
                    'append_html' => $append_html
                ];
            }else{
                // return ['dont'];
                $data = [
                    'started' => 1,
                    'operator' => Auth::id(),
                    'append_html' => $append_html
                ];
            }

            
            $pusher->trigger('my-channel', 'worksheetJob', $data);


            
            // $timer_data = JobTimer::where('html_id', $request->html_id)->first();
            // $job_data = WorksheetJob::find($timer_data->worksheet_job_id);
            // $worksheet_data = Worksheet::find($job_data->worksheet_id);

            // $startDate = time();
            // $expiry = date('Y-m-d H:i:s', strtotime('+' . $worksheet_data->days_required . ' day', $startDate)); // to calculate dluvery date
            
            // Worksheet::where('id', $worksheet_data->id)->update([
            //     //worksheet 
            //     'work_started' => Carbon::now(),
            //     'work_actually_started' => 1,
            //     'delivery_date' => $expiry
            // ]);

            // WorksheetJob::where('id', $job_data->id)->update([
            //     //job
            //     'started' => 1
            // ]);

            // JobTimer::where('html_id', $request->html_id)->update([
            //     //Job Timer
            //     'started' => 1,
            //     'in_progress' => 1,
            //     'min_at' => time() + $request->min_at,
            //     'max_at' => time() + $request->max_at,
            //     'started_at' => time()
            // ]);

        }

        //timer finish function 
        if($request->finished == 1){
            $data = [
                'stopped' => 1,
                'operator' => Auth::id()
            ];
            $pusher->trigger('my-channel', 'worksheetJob', $data);

            // $timer_data = JobTimer::where('html_id', $request->html_id)->first();
            // $job_data = WorksheetJob::find($timer_data->worksheet_job_id);

            // WorksheetJob::where('id', $job_data->id)->update([
            //     //job
            //     'completed' => 1,
            // ]);

            // JobTimer::where('html_id', $request->html_id)->update([
            //     //timer
            //     'in_progress' => 0,
            //     'finished' => 1,
            //     'finished_at' => time()
            // ]);

        }
    }
}
