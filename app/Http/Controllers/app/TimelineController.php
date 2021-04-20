<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\MainBar;
use App\SecondaryBar;
use App\User;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index(){  
        
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
            $m1->year = date(Y);
            $m1->save();
        }
        
        
                
    }
}
