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
use App\Task;
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

    public function tasks(){
        $operators_jobs = Task::with('timer')->where('operator_id', Auth::user()->id)->get();
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
        // $all = Task::with(['timer'])->where('operator_id', Auth::id())->get();
        // return ($all[0]);
        return view('app.operator.tasks')->with([
            'timer_continue' => $in_progress,
            'tasks' => Task::with(['timer'])->get()
            // 'worksheets' => Worksheet::with(['jobs.timer','jobs.object'])->where('customer_accepted', 1)->get()
        ]);
    }

   

    public function timer(Request $request)
    {

        // Global Variable
        $SEC_PX = 1.43020833;
        $TIME = (time() - strtotime("today"));
        
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
            // die();
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

                // Calculating width and number of jobs
                $timer_data1 = JobTimer::where('html_id', $request->html_id)->first();

                if($request->is_task == 1){
                    $task_data1 = Task::find($timer_data1->task_id);
                    $main_bar->width = ($task_data1->max_time * 60) * $SEC_PX;
                    $main_bar->jobs = 1;
                }else{
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
                }
                

                $main_bar->color = 'royalblue';
                $main_bar->mode = 'work-started';
                
                $main_bar->jobs_done = 0;
                // The objects id will be the auto incerement id
                if($request->is_task == 1){
                    $main_bar->text = 'Task#' . $task_data1->id;
                }else{
                    $license_plate_text = WorksheetVehicle::where('worksheet_id', $job_data1->worksheet_id)->first();
                    $main_bar->text = $license_plate_text->license_plate;
                }
                
                $main_bar->date = date('d');
                $main_bar->month = date('m');
                $main_bar->year = date('Y');
                $main_bar->save();

                // Insert into secondary bar
                $secondary_bar_html = array();
                if($request->is_task == 1){
                    $secondary_bar = new SecondaryBar();
                    $secondary_bar->user_id = Auth::id();
                    $secondary_bar->left = $main_bar->left;
                    $secondary_bar->width = ($task_data1->max_time * 60) * $SEC_PX;
                    // $THE_LEFT = $secondary_bar->left + $secondary_bar->width;
                    $secondary_bar->color = '#434343';
                    $secondary_bar->mode = 'pre-object';
                    $secondary_bar->mode2 = '';
                    $secondary_bar->main_bar_id = $main_bar->id;
                    $secondary_bar->objects_index = 1;
                    $secondary_bar->status = 0;
                    $secondary_bar->position = 'last';
                    $secondary_bar->status = 1;
                    
                    $secondary_bar->text = $task_data1->title;
                    $secondary_bar->date = date('d');
                    $secondary_bar->month = date('m');
                    $secondary_bar->year = date('Y');
                    $secondary_bar->save();

                }else{
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
                        $secondary_bar->main_bar_id = $main_bar->id;
                        $secondary_bar->objects_index = $i+1;
                        $secondary_bar->status = 0;
                        if(count($secondary_bar_objects) == 1){
                            $secondary_bar->position = 'last';
                            $secondary_bar->status = 1;
                        }else{
                            if($i == 0 ){
                                $secondary_bar->position = 'first';
                                $secondary_bar->status = 1;
                                $secondary_bar->color = 'purple';
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

                        
                    }
                }
                
                array_push($secondary_bar_html, '<div class="bar secondary-bar" data-left="' . $secondary_bar->left . '" data-color="#434343" data-width="' . $secondary_bar->width . '" data-mode="pre-object" data-worksheet-id="' . $secondary_bar->main_bar_id . '" data-object-index="' . $secondary_bar->objects_index . '" data-status="0" data-position="' . $secondary_bar->position . '" data-mode2="" data-id="' . $secondary_bar->id . '" style="display: none">' . $secondary_bar->text . '</div>');

                $data = [
                    'started' => 1,
                    'main' => '<div class="bar main-bar" data-left="' . $main_bar->left . '"  data-color="royalblue" data-width="' . $main_bar->width . '" data-mode="work-not-started"  data-jobs="' . $main_bar->jobs . '" data-jobs-done="0" data-objects-id="' . $main_bar->id . '" data-id="' . $main_bar->id . '">' . $main_bar->text . '</div>',
                    'operator' => Auth::id(),
                    'secondary' => $secondary_bar_html,
                    'append_html' => $append_html
                ];
            }else{

                // Start work and he was late on his prevoius work
                if(
                    SecondaryBar::where('user_id', Auth::id())
                                ->where('mode', 'delay1')
                                ->exists()
                ){
                    // Updating dealy1 seconndary bar
                    $update_sec_bar_2 = SecondaryBar::where('user_id', Auth::id())
                                                    ->where('mode', 'delay1')
                                                    ->first();
                    $update_sec_bar_2->mode = 'normal';
                    $delay1_instances_time = $TIME - ($update_sec_bar_2->left / $SEC_PX);
                    $update_sec_bar_2->width = $delay1_instances_time * $SEC_PX;
                    $update_sec_bar_2->save();

                    // Updating job-late main bar
                    if(
                        $update_main_bar_4 = MainBar::where('user_id', Auth::id())
                                                    ->where('mode', 'job-late')
                                                    ->exists()
                    ){
                        $update_main_bar_4 = MainBar::where('user_id', Auth::id())
                                                ->where('mode', 'job-late')
                                                ->first();
                        $update_main_bar_4->width = $update_main_bar_4->width + ((time() - strtotime($update_main_bar_4->updated_at)) * $SEC_PX);
                        $update_main_bar_4->save();
                    }
                    

                    // Updating work under progress main bar
                    $update_main_bar_2 = MainBar::where('user_id', Auth::id())->where('mode', 'work-in-progress')->first();
                    $update_main_bar_2->mode = 'work-started';
                    $update_main_bar_2->save();

                    // Update job-late main bar
                    if(
                        MainBar::where('user_id', Auth::id())->where('mode', 'job-late')->exists()
                    ){
                        $update_main_bar_3 = MainBar::where('user_id', Auth::id())->where('mode', 'job-late')->first();
                        $update_main_bar_3->mode = 'normal-job-late';
                        $update_main_bar_3->save();
                    }
                    

                    // 1: Updating all next Secondary bars
                        // 1.1 Pre-object
                        $update_sec_bar_3 = SecondaryBar::where('user_id', Auth::id())
                                    ->where('main_bar_id', $update_main_bar_2->id)
                                    ->where('mode', 'pre-object')
                                    ->where('status', 0)
                                    ->get();
                        for ($i = 0; $i < count($update_sec_bar_3); $i++) {
                            $update_sec_bar_3[$i]->mode = 'pre-object2';
                            $update_sec_bar_3[$i]->mode2 = 'normal';
                            $update_sec_bar_3[$i]->left = $update_sec_bar_3[$i]->left + ((time() - strtotime($update_sec_bar_3[$i]->updated_at)) * $SEC_PX);
                            $update_sec_bar_3[$i]->save();
                        }

                        // 1.2 Pre-object2
                        $update_sec_bar_4 = SecondaryBar::where('user_id', Auth::id())
                                    ->where('main_bar_id', $update_main_bar_2->id)
                                    ->where('mode', 'pre-object2')
                                    ->where('status', 0)
                                    ->get();
                        for ($i = 0; $i < count($update_sec_bar_4); $i++) {
                            $update_sec_bar_4[$i]->mode2 = 'normal';
                            $update_sec_bar_4[$i]->left = $update_sec_bar_4[$i]->left + ((time() - strtotime($update_sec_bar_4[$i]->updated_at)) * $SEC_PX);
                            $update_sec_bar_4[$i]->save();
                        }

                    // Updating next in pre-object 3 case
                    $sec_bar_8 = SecondaryBar::where('user_id', Auth::id())
                                             ->where('main_bar_id', $update_main_bar_2->id)
                                             ->where('objects_index', '>', $update_main_bar_2->jobs_done )
                                             ->orderBy('id')
                                             ->get();
                    for ($i=0; $i < count($sec_bar_8); $i++) { 
                        $sec_bar_8[$i]->mode2 =  'normal';
                        $sec_bar_8[$i]->left = $sec_bar_8[$i]->left + ((time() - strtotime($sec_bar_8[$i]->updated_at)) * $SEC_PX);
                        $sec_bar_8[$i]->save();
                    }

                    // Making status 1 for next scheduled work
                    $update_sec_bar_1 = SecondaryBar::where('user_id', Auth::id())
                                ->where('main_bar_id', $update_main_bar_2->id)
                                ->where('status', 3)
                                ->latest('updated_at')
                                ->first();
                    SecondaryBar::where('user_id', Auth::id())
                                ->where('main_bar_id', $update_main_bar_2->id)
                                ->where('objects_index', ($update_sec_bar_1->objects_index + 1))
                                ->update(
                                    [
                                        'status' => 1,
                                        'color' => 'purple'
                                    ]
                                );

                }else{ // Start work and he was early on his prevoius work

                    // Updating delay1 seconndary bar state
                    if(
                        SecondaryBar::where('user_id', Auth::id())
                                    ->where('mode', 'delay1')
                                    ->exists()
                    ){
                        $update_sec_bar_2 = SecondaryBar::where('user_id', Auth::id())
                                                    ->where('mode', 'delay1')
                                                    ->first();
                        $update_sec_bar_2->mode = 'normal';
                        $delay1_instances_time = $TIME - ($update_sec_bar_2->left / $SEC_PX);
                        $update_sec_bar_2->width = $delay1_instances_time * $SEC_PX;
                        $update_sec_bar_2->save();
                    }

                    // Calculating Impact
                    $sec_bar_5 = SecondaryBar::where('user_id', Auth::id())
                                             ->where('mode', 'early-pre-object-bonus')
                                             ->first();
                    $impact = ( ($sec_bar_5->width) + ($sec_bar_5->left) ) - ( $TIME * $SEC_PX );

                    
                    // Reducing Main bar width,  changing mode
                    $main_bar_2 = MainBar::where('user_id', Auth::id())
                                         ->where('mode', 'work-in-progress')
                                         ->first();
                    $main_bar_2->width = $main_bar_2->width - $impact;
                    $main_bar_2->mode = 'work-started';
                    $main_bar_2->save();

                    // Append new main-bar or update it
                    if(
                        MainBar::where('user_id', Auth::id())
                               ->where('mode', 'pre-object-early-2')
                               ->exists()
                    ){
                        $main_bar_3 = MainBar::where('user_id', Auth::id())
                                             ->where('mode', 'pre-object-early-2')
                                             ->first();
                        $main_bar_3->width = $main_bar_3->width + $impact;
                        $main_bar_3->left = $main_bar_2->left + $main_bar_2->width;
                        $main_bar_3->save();
                    }else{
                        $m1 = new MainBar();
                        $m1->user_id = Auth::id();
                        $m1->html_id_verify = -1;
                        $m1->left = $main_bar_2->left + $main_bar_2->width;
                        $m1->width = $impact;
                        $m1->color = 'skyblue';
                        $m1->mode = 'pre-object-early-2';
                        $m1->jobs = '';
                        $m1->jobs_done = '';
                        $m1->text = '';
                        $m1->date = date('d');
                        $m1->month = date('m');
                        $m1->year = date('Y');
                        $m1->save();
                    }

                    

                    // Reducing the width of early bonus in sec bar, making it normal
                    $sec_bar_6 = SecondaryBar::where('user_id', Auth::id())
                                             ->where('mode', 'early-pre-object-bonus')
                                             ->first();
                    $sec_bar_6->width = $sec_bar_6->width - $impact;
                    $sec_bar_6->mode = 'normal';
                    $sec_bar_6->save();

                    // Reducing the width of every element except those which are done
                    $sec_bar_7 = SecondaryBar::where('user_id', Auth::id())
                                             ->where('main_bar_id', $main_bar_2->id)
                                             ->where('objects_index', '>', $main_bar_2->jobs_done )
                                             ->orderBy('id')
                                             ->get();
                    for ($i=0; $i < count($sec_bar_7); $i++) { 
                        $sec_bar_7[$i]->left =  $sec_bar_7[$i]->left - $impact;
                        $sec_bar_7[$i]->save();
                    }

                    

                    // Updating the next job schedule
                    $sec_bar_8 = SecondaryBar::where('user_id', Auth::id())
                                             ->where('main_bar_id', $main_bar_2->id)
                                             ->where('objects_index', $main_bar_2->jobs_done + 1 )
                                             ->first();
                    $sec_bar_8->status = 1;
                    $sec_bar_8->color = 'purple';
                    $sec_bar_8->save();
                }

                // slicing job late
                if(
                    MainBar::where('user_id', Auth::id())
                             ->where(function($q){
                                 $q->where('mode', 'job-late')
                                   ->orWhere('mode', 'normal-job-late');
                             })
                             ->exists()
                ){
                    if(
                        MainBar::where('user_id', Auth::id())
                                ->where('mode', 'pre-object-early-2')
                                ->exists()
                    ){
                        $t2_helper = MainBar::where('user_id', Auth::id())
                                            ->where(function($q){
                                                $q->where('mode', 'work-started')
                                                  ->orWhere('mode', 'work-in-progress');
                                            })
                                            ->first();

                        $t1 = MainBar::where('user_id', Auth::id())
                                     ->where('mode', 'pre-object-early-2')
                                     ->first();
                        $t2 = SecondaryBar::where('user_id', Auth::id())
                                          ->where('main_bar_id', $t2_helper->id)
                                          ->where('position', 'last')
                                          ->first();
                        if(($t2->left + $t2->width) < ($t1->left + $t1->width)){
                            MainBar::where('user_id', Auth::id())
                                    ->where(function($q){
                                        $q->where('mode', 'job-late')
                                        ->orWhere('mode', 'normal-job-late');
                                    })
                                    ->delete();
                        }
                    }
                }

                // return ['dont'];
                $data = [
                    'started' => 1,
                    'operator' => Auth::id(),
                    'append_html' => $append_html
                ];
            }

            
            $pusher->trigger('my-channel', 'worksheetJob', $data);


            
            $timer_data = JobTimer::where('html_id', $request->html_id)->first();

            if($request->is_task == 1){
                $task_data1 = Task::find($timer_data->task_id);
                Task::where('id', $task_data1->id)->update([
                    'started' => 1
                ]);

            }else{
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
            }

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
            $data = [
                'stopped' => 1,
                'operator' => Auth::id()
            ];
            $pusher->trigger('my-channel', 'worksheetJob', $data);

            // When he stop his work (Basic config for sec bar)
            $update_sec_1 = SecondaryBar::where('user_id', Auth::id())->where('status', '1')->first();
            $update_sec_5 = SecondaryBar::where('user_id', Auth::id())->where('status', '1')->first();
            $update_sec_1->status = 3;
            $update_sec_1->width = $update_sec_1->width + ((time() - strtotime($update_sec_1->updated_at)) * $SEC_PX);
            $update_sec_1->mode2 = 'normal';
            $update_sec_1->save();

            // When he stop his work (Basic config for main bar)
            $update_main_bar = MainBar::where('user_id', Auth::id())->where('mode', 'work-started')->first();
            $update_main_bar->mode = 'work-in-progress';
            $update_main_bar->jobs_done = $update_main_bar->jobs_done + 1;
            $update_main_bar->save();
            
            // When he stop his late work
            if(
                $TIME >= (($update_sec_5->left / $SEC_PX) + ($update_sec_5->width / $SEC_PX))
            ){
                
                // If all the work is complete
                $update_main_bar_5 = MainBar::where('user_id', Auth::id())->where('mode', 'work-in-progress')->first();
                if($update_main_bar_5->jobs == $update_main_bar_5->jobs_done){

                    if(
                        MainBar::where('user_id', Auth::id())
                                ->where('mode', 'job-late')
                                ->exists()
                    ){
                        $update_main_bar_4 = MainBar::where('user_id', Auth::id())
                                                    ->where('mode', 'job-late')
                                                    ->first();
                        $update_main_bar_4->width = $update_main_bar_4->width + ((time() - strtotime($update_main_bar_4->updated_at)) * $SEC_PX);
                        $update_main_bar_4->save();
                    }   
                    

                    // Main Bar
                    $amain = MainBar::where('user_id', Auth::id())
                                    ->where('mode','work-in-progress')
                                    ->first();
                                
                    // Sec bar
                    $bmain = SecondaryBar::where('user_id', Auth::id())
                                          ->where('main_bar_id', $amain->id)
                                          ->where('position', 'last')
                                          ->first();

                    // Slicing last job
                    $bmain_width = ((($bmain->left / $SEC_PX) + ($bmain->width / $SEC_PX)) - $TIME);
                    $bmain->width = (($bmain->width / $SEC_PX) - $bmain_width) * $SEC_PX;
                    $bmain->save();

                    

                    // slicing pre object early 2
                    if(
                        MainBar::where('user_id', Auth::id())
                                 ->where('mode', 'pre-object-early-2')
                                 ->exists()
                    ){
                        $a1 = MainBar::where('user_id', Auth::id())
                                    ->where('mode', 'pre-object-early-2')
                                    ->first();
                        $b1 = SecondaryBar::where('user_id', Auth::id())
                                          ->where('main_bar_id', $amain->id)
                                          ->where('position', 'last')
                                          ->first();
                        if(( $b1->left + $b1->width ) <= $a1->left){
                            $a1->delete();
                        }

                        if(( $b1->left + $b1->width ) <= ($a1->left + $a1->width )){
                            $a1->width = ($b1->left + $b1->width) - $a1->left;
                            $a1->save();
                        }
                        
                    }


                    MainBar::where('user_id', Auth::id())->update(['mode' => 'normal']);
                    SecondaryBar::where('user_id', Auth::id())->update(['mode' => 'normal', 'mode2' => 'normal']);
                }else{

                    // adding gray bar until next job he starts
                    $add_grey_bar = new SecondaryBar(); 
                    $add_grey_bar->user_id = Auth::id();
                    $add_grey_bar->left = $update_sec_1->left + $update_sec_1->width;
                    $add_grey_bar->width = 0;
                    $add_grey_bar->color = 'gray';
                    $add_grey_bar->mode = 'delay1';
                    $add_grey_bar->mode2 = '';
                    $add_grey_bar->main_bar_id = $update_sec_1->main_bar_id;
                    $add_grey_bar->objects_index = -1;
                    $add_grey_bar->status = '';
                    $add_grey_bar->position = '';
                    $add_grey_bar->text = '';
                    $add_grey_bar->position = '';
                    $add_grey_bar->date = date('d');
                    $add_grey_bar->month = date('m');
                    $add_grey_bar->year = date('Y');
                    $add_grey_bar->save();

                }

                // slicing job late
                if(
                    MainBar::where('user_id', Auth::id())
                             ->where(function($q){
                                 $q->where('mode', 'job-late')
                                   ->orWhere('mode', 'normal-job-late');
                             })
                             ->exists()
                ){
                    if(
                        MainBar::where('user_id', Auth::id())
                                ->where('mode', 'pre-object-early-2')
                                ->exists()
                    ){
                        $t2_helper = MainBar::where('user_id', Auth::id())
                                            ->where(function($q){
                                                $q->where('mode', 'work-started')
                                                  ->orWhere('mode', 'work-in-progress');
                                            })
                                            ->first();

                        $t1 = MainBar::where('user_id', Auth::id())
                                     ->where('mode', 'pre-object-early-2')
                                     ->first();
                        $t2 = SecondaryBar::where('user_id', Auth::id())
                                          ->where('main_bar_id', $t2_helper->id)
                                          ->where('position', 'last')
                                          ->first();
                        if(($t2->left + $t2->width) < ($t1->left + $t1->width)){
                            MainBar::where('user_id', Auth::id())
                                    ->where(function($q){
                                        $q->where('mode', 'job-late')
                                        ->orWhere('mode', 'normal-job-late');
                                    })
                                    ->delete();
                        }
                    }
                }

            }

            // When he stop his early work
            if(
                $TIME < (($update_sec_5->left / $SEC_PX) + ($update_sec_5->width / $SEC_PX))
            ){

                // If all the work is complete
                $update_main_bar_5 = MainBar::where('user_id', Auth::id())->where('mode', 'work-in-progress')->first();
                if($update_main_bar_5->jobs == $update_main_bar_5->jobs_done) {
                    
                    // Main Bar
                    $amain = MainBar::where('user_id', Auth::id())
                                    ->where('mode','work-in-progress')
                                    ->first();
                                
                    // Sec bar
                    $bmain = SecondaryBar::where('user_id', Auth::id())
                                          ->where('main_bar_id', $amain->id)
                                          ->where('position', 'last')
                                          ->first();

                    // Slicing last job
                    $bmain_width = ((($bmain->left / $SEC_PX) + ($bmain->width / $SEC_PX)) - $TIME);
                    $bmain->width = (($bmain->width / $SEC_PX) - $bmain_width) * $SEC_PX;
                    $bmain->color = '#238f23';
                    $bmain->save();

                    // Slicing main bar
                    if(($bmain->left + $bmain->width) < ($amain->left + $amain->width)){
                        $amain->width = ($bmain->left + $bmain->width) - $amain->left;
                        $amain->save();
                    }

                    // slicing pre object early 2
                    if(
                        MainBar::where('user_id', Auth::id())
                                 ->where('mode', 'pre-object-early-2')
                                 ->exists()
                    ){
                        $del = false;
                        $a1 = MainBar::where('user_id', Auth::id())
                                    ->where('mode', 'pre-object-early-2')
                                    ->first();
                        $b1 = SecondaryBar::where('user_id', Auth::id())
                                          ->where('main_bar_id', $amain->id)
                                          ->where('position', 'last')
                                          ->first();
                        if(( $b1->left + $b1->width ) <= $a1->left){
                            $a1->delete();
                            $del = true;
                        }else if(( $b1->left + $b1->width ) <= ($a1->left + $a1->width )){
                            $a1->width = ($b1->left + $b1->width) - $a1->left;
                            $a1->save();
                            $del = true;
                        }

                        if($del){
                            if(
                                MainBar::where('user_id', Auth::id())
                                        ->where(function($q){
                                            $q->where('mode', 'job-late')
                                            ->orWhere('mode', 'normal-job-late');
                                        })
                                        ->exists()
                            ){
                                MainBar::where('user_id', Auth::id())
                                        ->where(function($q){
                                            $q->where('mode', 'job-late')
                                            ->orWhere('mode', 'normal-job-late');
                                        })
                                        ->delete();
                                
                            }
                        }
                        
                    }
                    
                    MainBar::where('user_id', Auth::id())->update(['mode' => 'normal']);
                    SecondaryBar::where('user_id', Auth::id())->update(['mode' => 'normal', 'mode2' => 'normal']);
                }else{
                    // $update_sec_6 = SecondaryBar::where('user_id', Auth::id())->where('status', '1')->first();
                    $update_sec_5_skyblue_width = ((($update_sec_5->left / $SEC_PX) + ($update_sec_5->width / $SEC_PX)) - $TIME);
                    $update_sec_5->width = 
                    (($update_sec_5->width / $SEC_PX) - $update_sec_5_skyblue_width) * $SEC_PX;
                    $update_sec_5->color = '#238f23';
                    $update_sec_5->save();

                    // Main Bar
                    $amain = MainBar::where('user_id', Auth::id())
                                    ->where('mode','work-in-progress')
                                    ->first();

                    

                    // adding skyblue bar 
                    $add_grey_bar = new SecondaryBar(); 
                    $add_grey_bar->user_id = Auth::id();
                    $add_grey_bar->left = $TIME * $SEC_PX;
                    $add_grey_bar->width = $update_sec_5_skyblue_width * $SEC_PX;
                    $add_grey_bar->color = 'skyblue';
                    $add_grey_bar->mode = 'early-pre-object-bonus';
                    $add_grey_bar->mode2 = '';
                    $add_grey_bar->main_bar_id = $update_sec_1->main_bar_id;
                    $add_grey_bar->objects_index = -1;
                    $add_grey_bar->status = '';
                    $add_grey_bar->position = '';
                    $add_grey_bar->text = '';
                    $add_grey_bar->date = date('d');
                    $add_grey_bar->month = date('m');
                    $add_grey_bar->year = date('Y');
                    $add_grey_bar->save();

                    // Updating all next Secondary bars
                    $main_bar_1 = MainBar::where('user_id', Auth::id())->where('mode', 'work-in-progress')->first();
                    SecondaryBar::where('user_id', Auth::id())
                                ->where('main_bar_id', $main_bar_1->id)
                                ->where('mode', 'pre-object')
                                ->where('status', 0)
                                ->update(['mode' => 'pre-object3']);
                }

                // slicing job late
                if(
                    MainBar::where('user_id', Auth::id())
                             ->where(function($q){
                                 $q->where('mode', 'job-late')
                                   ->orWhere('mode', 'normal-job-late');
                             })
                             ->exists()
                ){
                    if(
                        MainBar::where('user_id', Auth::id())
                                ->where('mode', 'pre-object-early-2')
                                ->exists()
                    ){
                        $t2_helper = MainBar::where('user_id', Auth::id())
                                            ->where(function($q){
                                                $q->where('mode', 'work-started')
                                                  ->orWhere('mode', 'work-in-progress');
                                            })
                                            ->first();

                        $t1 = MainBar::where('user_id', Auth::id())
                                     ->where('mode', 'pre-object-early-2')
                                     ->first();
                        $t2 = SecondaryBar::where('user_id', Auth::id())
                                          ->where('main_bar_id', $t2_helper->id)
                                          ->where('position', 'last')
                                          ->first();
                        if(($t2->left + $t2->width) < ($t1->left + $t1->width)){
                            MainBar::where('user_id', Auth::id())
                                    ->where(function($q){
                                        $q->where('mode', 'job-late')
                                        ->orWhere('mode', 'normal-job-late');
                                    })
                                    ->delete();
                        }
                    }
                }
                
                
            }



            $timer_data = JobTimer::where('html_id', $request->html_id)->first();

            if($request->is_task == 1){
                $task_data1 = Task::find($timer_data->task_id);
                Task::where('id', $task_data1->id)->update([
                    'completed' => 1
                ]);
            }else{
                $job_data = WorksheetJob::find($timer_data->worksheet_job_id);

                WorksheetJob::where('id', $job_data->id)->update([
                    //job
                    'completed' => 1,
                ]);
            }
            

            JobTimer::where('html_id', $request->html_id)->update([
                //timer
                'in_progress' => 0,
                'finished' => 1,
                'finished_at' => time()
            ]);

        }
    }
}
