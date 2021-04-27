<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\MainBar;
use App\SecondaryBar;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index(){  
        // Global Variable
        $SEC_PX = 1.43020833;
        $TIME = (time() - strtotime("today"));
        
        // Fetch all work-started Main bars containg secondary bars
        $work_start_main_bar = MainBar::where('mode', 'work-started')->with('secondary_bars')->get();
        // Basic configuration
        for($i=0; $i < count($work_start_main_bar) ; $i++) { // Loop through all main bars where work is started

            for ($j=0; $j < count($work_start_main_bar[$i]->secondary_bars); $j++) { // Loop through all sec bars 

                if($work_start_main_bar[$i]->secondary_bars[$j]->status == 1){ // The secondary bar where work status is 1
                    
                    $time_comparison = [];
                    $time_comparison['bar'] = (int)
                    (
                        ($work_start_main_bar[$i]->secondary_bars[$j]->left / $SEC_PX) + 
                        ($work_start_main_bar[$i]->secondary_bars[$j]->width / $SEC_PX)
                    );
                    $time_comparison['now'] = $TIME;
                    
                    // pre object 2 is not present
                    if($time_comparison['now'] >= $time_comparison['bar']){ // If time passes the work time


                        // Secondary bar increase width (under progress)
                        $work_start_main_bar[$i]->secondary_bars[$j]->color = '#dc3545';
                        $work_start_main_bar[$i]->secondary_bars[$j]->mode2 = 'increase-width';
                        
                        $work_start_main_bar[$i]->secondary_bars[$j]->width = 
                        $work_start_main_bar[$i]->secondary_bars[$j]->width +
                        ($time_comparison['now'] - $time_comparison['bar']) * $SEC_PX;

                        $work_start_main_bar[$i]->secondary_bars[$j]->save();

                        // Secondary bar increase left (jobs after under progress)
                        $jobs_after_under_progress = 
                        SecondaryBar::where('main_bar_id', $work_start_main_bar[$i]->secondary_bars[$j]->main_bar_id)
                                    ->where('objects_index', '>', $work_start_main_bar[$i]->secondary_bars[$j]->objects_index)
                                    ->get();
                        for ($k=0; $k < count($jobs_after_under_progress); $k++) { 
                            $jobs_after_under_progress[$k]->left = 
                            $jobs_after_under_progress[$k]->left + 
                            ($time_comparison['now'] - $time_comparison['bar']) * $SEC_PX;

                            $jobs_after_under_progress[$k]->mode2 = 'increase-left';
                            $jobs_after_under_progress[$k]->save();
                        }
                     

                    }

                    

                }

            }

        }

        // FOR EARLY JOBS
        $work_progress_main_bar = MainBar::where('mode', 'work-in-progress')->with('secondary_bars')->get();
        // Adding job late
        for($i=0; $i < count($work_progress_main_bar) ; $i++) { // Loop through all main bars where work is started
            
            for ($j=0; $j < count($work_progress_main_bar[$i]->secondary_bars); $j++) { // Loop through all sec bars

                if($work_progress_main_bar[$i]->secondary_bars[$j]->mode == 'early-pre-object-bonus'){

                    $time_comparison = [];
                    $time_comparison['bar'] = (int)
                    (
                        ($work_progress_main_bar[$i]->secondary_bars[$j]->left / $SEC_PX) + 
                        ($work_progress_main_bar[$i]->secondary_bars[$j]->width / $SEC_PX)
                    );
                    $time_comparison['now'] = $TIME;

                    if($time_comparison['now'] >= $time_comparison['bar']){
                        
                        // making early-pre-object-bonus normal
                        $work_progress_main_bar[$i]->secondary_bars[$j]->mode = 'normal';
                        $work_progress_main_bar[$i]->secondary_bars[$j]->save();

                        // Adding delay1
                        if(
                            SecondaryBar::where('mode', 'delay1')
                                        ->where('main_bar_id', $work_progress_main_bar[$i]->id)
                                        ->exists()
                        ){
                            $sec_bar_1 = SecondaryBar::where('mode', 'delay1')
                                        ->where('main_bar_id', $work_progress_main_bar[$i]->id)
                                        ->first();
                            $delay1_instances_time_1 = $TIME - ($sec_bar_1->left / $SEC_PX);
                            $sec_bar_1->width = $delay1_instances_time_1 * $SEC_PX;
                            $sec_bar_1->save();
                            
                        }else{
                            // adding gray bar until next job he start
                            $add_grey_bar = new SecondaryBar(); 
                            $add_grey_bar->user_id = $work_progress_main_bar[$i]->user_id;
                            $add_grey_bar->left = $TIME * $SEC_PX;
                            $add_grey_bar->width = 0;
                            $add_grey_bar->color = 'gray';
                            $add_grey_bar->mode = 'delay1';
                            $add_grey_bar->mode2 = '';
                            $add_grey_bar->main_bar_id = $work_progress_main_bar[$i]->id;
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
                        
                        // Secondary bar increase left (jobs after skyblue)
                        $sec_bar_2 =  SecondaryBar::where('mode', 'delay1')
                                                    ->where('main_bar_id', $work_progress_main_bar[$i]->id)
                                                    ->first();
                        $jobs_after_skyblue = 
                        SecondaryBar::where('main_bar_id', $work_progress_main_bar[$i]->secondary_bars[$j]->main_bar_id)
                                    ->where('objects_index', '>', $work_progress_main_bar[$i]->jobs_done )
                                    ->orderBy('id')
                                    ->get();
                        
                        $width1 = 0;
                        for ($k=0; $k < count($jobs_after_skyblue); $k++) { 
                            if($k == 0){
                                $width1 = 0;
                            }else{
                                $width1 += ($jobs_after_skyblue[$k]->width);
                            }
                            $jobs_after_skyblue[$k]->left = ($sec_bar_2->left + $sec_bar_2->width) + $width1;

                            $jobs_after_skyblue[$k]->mode2 = 'increase-left';
                            $jobs_after_skyblue[$k]->save();
                        }
                        

                    }

                }

            }

            

        }
        

        // If there is delay1 instance seconday bar
        $delay1_instances = SecondaryBar::where('mode', 'delay1')->get();
        for ($i=0; $i < count($delay1_instances); $i++) { 
            $delay1_instances_time = $TIME - ($delay1_instances[$i]->left / $SEC_PX);
            $delay1_instances[$i]->width = $delay1_instances_time * $SEC_PX;
            $delay1_instances[$i]->save();
        }

        // if there is any increase left 
        $increase_left_1 = SecondaryBar::where('mode2', 'increase-left')->get();
        for ($i=0; $i < count($increase_left_1); $i++) { 
            $increase_left_1[$i]->left = $increase_left_1[$i]->left + ((time() - strtotime($increase_left_1[$i]->updated_at)) * $SEC_PX);
            $increase_left_1[$i]->save();
        }

        // if there is any increase width 
        $increase_width_1 = SecondaryBar::where('mode2', 'increase-width')->get();
        for ($i=0; $i < count($increase_width_1); $i++) { 
            $increase_width_1[$i]->width = $increase_width_1[$i]->width + ((time() - strtotime($increase_width_1[$i]->updated_at)) * $SEC_PX);
            $increase_width_1[$i]->save();
        }
        

        // if there is any job late
        $job_late_1 = MainBar::where('mode', 'job-late')->get();
        for ($i=0; $i < count($job_late_1); $i++) { 
            $job_late_1[$i]->width = $job_late_1[$i]->width + ((time() - strtotime($job_late_1[$i]->updated_at)) * $SEC_PX);
            $job_late_1[$i]->save();
        }



        // ADDING LATE BAR APPROPRIATELY
        // For started works
        $work_start_main_bar_2 = MainBar::where('mode', 'work-started')->orWhere('mode', 'work-in-progress')->with('secondary_bars')->get();
        for($i=0; $i < count($work_start_main_bar_2) ; $i++) {
            
            // Secondary bar with last object index
            $albs1 = SecondaryBar::where('main_bar_id', $work_start_main_bar_2[$i]->id)
                                ->where('position', 'last')
                                ->first();
            $albs1_width = $albs1->left + $albs1->width;  

            // Main bar with work-started mode
            if(
                MainBar::where('user_id', $work_start_main_bar_2[$i]->user_id)
                       ->where('mode', 'pre-object-early-2')
                       ->exists()
            ){
                $albm3 = MainBar::where('user_id', $work_start_main_bar_2[$i]->user_id)
                                ->where('mode', 'pre-object-early-2')
                                ->first();
                $albm1_width = $albm3->left + $albm3->width;
            }else{
                $albm1_width = $work_start_main_bar_2[$i]->left + $work_start_main_bar_2[$i]->width;
            }
            

            if($albs1_width > $albm1_width){
                
                // if job-late already exists
                if(
                    MainBar::where('user_id', $work_start_main_bar_2[$i]->user_id)
                           ->where(function($q){
                                $q->where('mode', 'job-late')
                                  ->orWhere('mode', 'normal-job-late');
                            })
                           ->exists()  
                ){
                    $albm2 = MainBar::where('user_id', $work_start_main_bar_2[$i]->user_id)
                                    ->where(function($q){
                                        $q->where('mode', 'job-late')
                                        ->orWhere('mode', 'normal-job-late');
                                    })
                                    ->first();  

                    // To apply job late on normal job late - work-start
                    if($work_start_main_bar_2[$i]->mode == 'work-started'){
                        $albs2 = SecondaryBar::where('main_bar_id', $work_start_main_bar_2[$i]->id)
                                        ->where('status', 1)
                                        ->first();
                        if($albs2->mode2 == 'increase-width'){
                            $albm2->mode = 'job-late';
                        }
                    }else{
                        $albs2 = SecondaryBar::where('main_bar_id', $work_start_main_bar_2[$i]->id)
                                             ->where('position', 'last')
                                             ->first();
                        if($albs2->mode2 == 'increase-left' || $albs2->mode2 == 'increase-width'){
                            $albm2->mode = 'job-late';
                        }    
                    }
                    


                    $albm2->left = $albm1_width;
                    $albm2->width = $albs1_width - $albm1_width;
                    $albm2->save();

                }else{ // if job-late not exists

                    // Insert Job late bar
                    $main_bar = new MainBar();
                    $main_bar->user_id = $work_start_main_bar_2[$i]->user_id;
                    $main_bar->html_id_verify = -1;
                    $main_bar->left = $albm1_width;
                    $main_bar->width = $albs1_width - $albm1_width;
                    $main_bar->color = '#dc3545';
                    $main_bar->mode = 'job-late';
                    $main_bar->jobs = $work_start_main_bar_2[$i]->jobs;
                    $main_bar->jobs_done = $work_start_main_bar_2[$i]->jobs_done;
                    $main_bar->text = '';
                    $main_bar->date = date('d');
                    $main_bar->month = date('m');
                    $main_bar->year = date('Y');
                    $main_bar->save();

                }
                
            }

        }

        


        return view('app.timeline.index')->with(
            [
                'operators' => User::where('role', 3)
                                ->with(
                                    [
                                        'late_bars' => function($q){
                                            if(isset($_GET['selection'])){
                                                $year = explode('-', $_GET['selection'])[0];
                                                $month = explode('-', $_GET['selection'])[1];
                                                $date = explode('-', $_GET['selection'])[2];
                                                
                                                return $q
                                                        ->where('late_bars.date', $date)
                                                        ->where('late_bars.month', $month)
                                                        ->where('late_bars.year', $year)->orderBy('id');
                                            }else{
                                                return $q
                                                    ->where('late_bars.date', date('d'))
                                                    ->where('late_bars.month', date('m'))
                                                    ->where('late_bars.year', date('Y'))->orderBy('id');
                                            }   
                                            
                                        },  
                                        'main_bars' => function($q){
                                            if(isset($_GET['selection'])){
                                                $year = explode('-', $_GET['selection'])[0];
                                                $month = explode('-', $_GET['selection'])[1];
                                                $date = explode('-', $_GET['selection'])[2];
                                                
                                                return $q
                                                        ->where('main_bars.date', $date)
                                                        ->where('main_bars.month', $month)
                                                        ->where('main_bars.year', $year)->orderBy('id');
                                            }else{
                                                return $q
                                                    ->where('main_bars.date', date('d'))
                                                    ->where('main_bars.month', date('m'))
                                                    ->where('main_bars.year', date('Y'))->orderBy('id');
                                            }   
                                            
                                        },
                                        'secondary_bars' => function($q){
                                            if(isset($_GET['selection'])){
                                                $year = explode('-', $_GET['selection'])[0];
                                                $month = explode('-', $_GET['selection'])[1];
                                                $date = explode('-', $_GET['selection'])[2];
                                                
                                                return $q
                                                        ->where('secondary_bars.date', $date)
                                                        ->where('secondary_bars.month', $month)
                                                        ->where('secondary_bars.year', $year)->orderBy('id');
                                            }else{
                                                return $q
                                                    ->where('secondary_bars.date', date('d'))
                                                    ->where('secondary_bars.month', date('m'))
                                                    ->where('secondary_bars.year', date('Y'))->orderBy('id');
                                            }   
                                            
                                        }
                                    ]
                                    )
                                ->get(),
            ]
        );
        
        
    }

    public function update(Request $request) {
        //  return $request;
        if($request->type == 'main'){
            $something = false;
            $main = MainBar::find($request->id);
            
            if($request->mode != ''){
                $main->mode = $request->mode;
                $something = true;
            }
            if($something){
                $main->save();
            }
        }

        if($request->type == 'sec'){
            // return $request;
            $something = false;
            $sec = SecondaryBar::find($request->id);
            
            if(!is_null($request->mode)){
                $sec->mode = $request->mode;
                $something = true;
            }
            if(!is_null($request->mode2)){
                $sec->mode2 = $request->mode2;
                $something = true;
            }
            if(!is_null($request->status)){
                $sec->status = $request->status;
                $something = true;
            }
            if(!is_null($request->color)){
                $sec->color = $request->color;
                $something = true;
            }
            
            if($something){
                $sec->save();
                
            }
        }
        if($request->type == 'insert-main'){
            $m1 = new MainBar();
            $m1->left = $request->left;
            $m1->width = $request->width;
            $m1->color = $request->color;
            $m1->mode = $request->mode;
            $m1->jobs = -1;
            $m1->jobs_done = -1;
            $m1->text = '';
            $m1->date = date('d');
            $m1->month = date('m');
            $m1->year = date('Y');
            $m1->save();
        }
        
        
                
    }
}
