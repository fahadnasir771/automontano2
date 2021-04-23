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

        // Affected 
        $increase_left_affected = false;
        $increase_width_affected = false;
        $job_late_affected = false;
        
        // Fetch all work-started Main bars containg secondary bars
        $work_start_main_bar = MainBar::where('mode', 'work-started')->with('secondary_bars')->get();

        
        for($i=0; $i < count($work_start_main_bar) ; $i++) { // Loop through all main bars where work is started

            for ($j=0; $j < count($work_start_main_bar[$i]->secondary_bars); $j++) { // Loop through all sec bars where work status is 1

                if($work_start_main_bar[$i]->secondary_bars[$j]->status == 1){ // The secondary bar which is under progress
                    
                    $time_comparison = [];
                    $time_comparison['bar'] = (int)
                    (
                        ($work_start_main_bar[$i]->secondary_bars[$j]->left / $SEC_PX) + 
                        ($work_start_main_bar[$i]->secondary_bars[$j]->width / $SEC_PX)
                    );
                    $time_comparison['now'] = $TIME;
                    // return $time_comparison;
                    // die();
                    // dd($time_comparison);
                    if($time_comparison['now'] >= $time_comparison['bar']){ // If time passes the work time

                        if(
                            MainBar::where('mode', 'job-late')->exists() || 
                            MainBar::where('mode', 'normal-job-late')->exists()
                        ) 
                        { 
                            // job-late mode laready exists
                            if(MainBar::where('mode', 'job-late')->exists()){
                                $update_job_late = MainBar::where('mode', 'job-late')->first();
                            }else{
                                $update_job_late = MainBar::where('mode', 'normal-job-late')->first();
                                $update_job_late->mode = 'job-late';
                            }
                            
                            $update_job_late->width = $update_job_late->width + (($time_comparison['now'] - $time_comparison['bar']) * $SEC_PX);
                            $update_job_late->save();
                        }else{
                            // Insert Job late bar
                            $main_bar = new MainBar();
                            $main_bar->user_id = $work_start_main_bar[$i]->user_id;
                            $main_bar->html_id_verify = -1;
                            $main_bar->left = ($work_start_main_bar[$i]->left + $work_start_main_bar[$i]->width);
                            $main_bar->width = ($time_comparison['now'] - $time_comparison['bar']) * $SEC_PX;
                            $main_bar->color = '#dc3545';
                            $main_bar->mode = 'job-late';
                            $main_bar->jobs = $work_start_main_bar[$i]->jobs;
                            $main_bar->jobs_done = $work_start_main_bar[$i]->jobs_done;
                            $main_bar->text = '';
                            $main_bar->date = date('d');
                            $main_bar->month = date('m');
                            $main_bar->year = date('Y');
                            $main_bar->save();
                        }

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
                        $increase_left_affected = true;
                        $increase_width_affected = true;
                        $job_late_affected = true;

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
        if(!$increase_left_affected){
            for ($i=0; $i < count($increase_left_1); $i++) { 
                // dd(((time() - strtotime($increase_left_1[$i]->updated_at)) * $SEC_PX) );
                $increase_left_1[$i]->left = $increase_left_1[$i]->left + ((time() - strtotime($increase_left_1[$i]->updated_at)) * $SEC_PX);
                $increase_left_1[$i]->save();
            }
        }

        // if there is any increase width 
        $increase_width_1 = SecondaryBar::where('mode2', 'increase-width')->get();
        if(!$increase_width_affected){
            for ($i=0; $i < count($increase_width_1); $i++) { 
                $increase_width_1[$i]->width = $increase_width_1[$i]->width + ((time() - strtotime($increase_width_1[$i]->updated_at)) * $SEC_PX);
                $increase_width_1[$i]->save();
            }
        }

        // if there is any job late
        $job_late_1 = MainBar::where('mode', 'job-late')->get();
        if(!$job_late_affected){
            for ($i=0; $i < count($job_late_1); $i++) { 
                $job_late_1[$i]->width = $job_late_1[$i]->width + ((time() - strtotime($job_late_1[$i]->updated_at)) * $SEC_PX);
                $job_late_1[$i]->save();
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
