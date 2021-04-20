<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Customer;
use App\JobTimer;
use App\LateBar;
use App\Timeline;
use App\User;
use App\Worksheet;
use App\WorksheetCustomer;
use App\WorksheetFailure;
use App\WorksheetJob;
use App\WorksheetObject;
use App\WorksheetSparePart;
use App\WorksheetVehicle;
use App\WorksheetVehicleImage;
use CreateWorksheetJobsTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class WorksheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //  dd( Route::current()->uri);
        return view('app.admin.worksheets.index')->with([
            'worksheets' => Worksheet::with(['spare_parts', 'jobs'])->orderBy('id','DESC')->get(),
            'user' => User::all()     
        ]);
        
    }

    //Vehicle data by license plate
    public function vehicle_data(Request $request)
    {
        $GLOBALS['plate'] = $request->plate;
        if(!isset($request->plate)){
            return view('app.admin.worksheets.index')->with([
                'vehicle_search' => 1  
            ]);
        }else{
            $worksheets = Worksheet::with(['spare_parts', 'jobs', 'vehicle'])->whereHas('vehicle', function($query){
                $query->where('license_plate', $GLOBALS['plate']);
            })->orderBy('id','DESC')->get();
            // return $worksheets;
            return view('app.admin.worksheets.index')->with([
                'license_plate' => $request->plate,
                'worksheets' => $worksheets,
                'user' => User::all()     
            ]);
        }
        
    }

    // All timeline display
    public function all_timeline()
    {
        return view('app.operator.worksheets')->with([
            'timer_continue' => JobTimer::where('in_progress', '1')->first(),
            'worksheets' => Worksheet::with(['jobs.timer','jobs.object'])->get(),
            'user' => User::all()
        ]);
    }

    // Timeline by Worksheet
    public function timeline_by_worksheet(Request $request)
    {
        return view('app.operator.worksheets')->with([
            'timer_continue' => JobTimer::where('in_progress', '1')->first(),
            'worksheets' => Worksheet::with(['jobs.timer','jobs.object'])->where('id', $request->wid)->get(),
            'user' => User::all()
        ]);
    }

    // Timeline by operator
    public function timeline_by_operator(Request $request)
    {

        return view('app.operator.worksheets')->with([
            'timer_continue' => JobTimer::where('in_progress', '1')->first(),
            'worksheets' => Worksheet::with(['jobs.timer','jobs.object'])->get(),
            'user' => User::all(),
            'operators' => User::where('role', '3')->get(),
            'operator_id_2' => User::find($request->oid),
            'search_by_operator' => 1
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        // return WorksheetObject::with('operators')->get();
        return view('app.admin.worksheets.add')->with([
            'jobs' => WorksheetObject::with('operators')->get(),
            'operators' => User::where('role', '3')->get(),
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
        // Global Variable
        $SEC_PX = 1.43020833;

        $data = $request->except('_token');
        
        # data variabls defined
        $vehicle = $data['vehicle'];
        unset($vehicle['images']);
        $vehicle_images = $data['vehicle']['images']; 
        $failures = $data['failure']; // array
        $spare_parts = $data['spare']; //array
        $create_objects = $data['create_job']; //array, objects to be created in WorksheetObject table
        $existing_objects = $data['existing_job']; //array
        $customer = $data['customer'];
        $meta = $data['meta'];



        #### 0: Creating Customer Account
        if (User::where('email', $customer['phone'])->exists()) {
            $user = User::where('email', $customer['phone'])->first();
        }else{
            $user = new User();
            $user->title = 'Customer';
            $user->name = $customer['full_name'];
            $user->email = $customer['phone'];
            $user->password = bcrypt($vehicle['license_plate']);
            $user->role = 4;
            $user->save();
        }
        
        #### 1: Worksheet data
        $worksheet = New Worksheet();
        $worksheet->user_id = Auth::user()->id;
        $worksheet->customer_accepted = 1;
        $worksheet->work_started = $meta['work_started'];
        $worksheet->days_required = $meta['days_required'];
        $worksheet->voucher_type = $meta['voucher_radio'];
        $worksheet->customer_id = $user->id;
        // $worksheet->is_task = array_key_exists("internal_task_check",$meta) ? 1 : 0;
        $worksheet->save();

        #### 2: Inserting data to worksheet objects
        $jobs_exists = true;
        if( $create_objects[0]['object'] != null){
            for ($i=0; $i < count($create_objects); $i++) { 
                $objects_created[$i] = new WorksheetObject();
                $objects_created[$i]->title = $create_objects[$i]['object'];
                $objects_created[$i]->min_time = $create_objects[$i]['min_time'];
                $objects_created[$i]->max_time = $create_objects[$i]['max_time'];
                $objects_created[$i]->save();
                $objects_created[$i]->operators()->attach($create_objects[$i]['operator']);
            }
    
            #### 2.1: Making array to insert newly created objects and existing objects in jobs
            for($i=0; $i < count($objects_created); $i++){
                $objects_created[$i] = ['object' => $objects_created[$i]->id, 'operator' => $create_objects[$i]['operator']];
            }
            if($existing_objects[0]['object'] != null){
                $jobs = array_merge($existing_objects, $objects_created);
            }else{
                $jobs = $objects_created;
            }
            
        }else{
            if($existing_objects[0]['object'] != null){
                $jobs = $existing_objects;
            }else{
                $jobs_exists = false;
            }
            
        }
        
        #### 3: Insert into worksheet jobs
        if($jobs_exists){
            for ($i=0; $i < count($jobs); $i++) { 
                $job[$i] = new WorksheetJob();
                $job[$i]->worksheet_id = $worksheet->id;
                $job[$i]->object_id = $jobs[$i]['object'];
                $job[$i]->operator_id = $jobs[$i]['operator'];
                $job[$i]->save();

                ### 3.1: Create timers for each job
                $timer = new JobTimer();
                $timer->worksheet_job_id = $job[$i]->id;
                $timer->html_id = 'w' . $worksheet->id . '-j' . ($i+1);
                $timer->save();
            }
        }

        #### 4: Insert into failures
        for ($i=0; $i < count($failures); $i++) { 
            $failure[$i] = new WorksheetFailure();
            $failure[$i]->worksheet_id = $worksheet->id;
            $failure[$i]->failure_title = $failures[$i]['failure_title'];
            $failure[$i]->failure_quotation = $failures[$i]['failure_quotation'];
            $failure[$i]->save();
        }

        #### 5: Insert into spare parts
        for ($i=0; $i < count($spare_parts); $i++) { 
            $spare_parts[$i]['worksheet_id'] = $worksheet->id;
            $spare_part[$i] = WorksheetSparePart::create($spare_parts[$i]);
        }

        ### 6: Insert into customer 
        $customer['worksheet_id'] = $worksheet->id;
        $customer_created = WorksheetCustomer::create($customer);

        ### 7: insert into vehicle
        $vehicle['worksheet_id'] = $worksheet->id;
        $vehicle['customer_id'] = $customer_created->id;
        $vehicle_create = WorksheetVehicle::create($vehicle);

        ### 8: insert images of vehicle
        for ($i=0; $i < count($vehicle_images); $i++) { 
            $destinationPath =  public_path('images/worksheet/');
            $image = $vehicle_images[$i];
            $file_name = (time() * ($i+1)). '_' . $image->getClientOriginalName();
            $images[$i] = $image->move($destinationPath,$file_name);
            $save_image = new WorksheetVehicleImage();
            $save_image->worksheet_vehicle_id = $vehicle_create->id;
            $save_image->image = $images[$i];
            $save_image->save();
        }

        ### 9: Timeline
        // Work start time variables
        $datetime_local = $meta['work_started'];
        // Time
        $time = substr($datetime_local, -5);
        $hr = explode(':', $time)[0];
        $min = explode(':', $datetime_local)[1];
        // Date
        $date_str = substr($datetime_local, 0, 10);
        $year = explode('-', $date_str)[0];
        $month = explode('-', $date_str)[1];
        $date = explode('-', $date_str)[2];

        ## 9.1: Insert into late bar
        $same = array();
        $sum = array();
        for($i=0; $i < count($existing_objects); $i++){

            if(in_array($existing_objects[$i]['operator'], $same)){
                for ($j=0; $j < count($sum); $j++) { 
                    if($sum[$j]['operator'] == $existing_objects[$i]['operator']){
                        $object2 = WorksheetObject::find($existing_objects[$i]['object']);
                        $calc2 = ($object2->max_time * 60) * $SEC_PX;

                        $sum[$j]['width'] += $calc2;
                        break;
                    }
                }
            }else{
                $object1 = WorksheetObject::find($existing_objects[$i]['object']);
                $calc1 = ($object1->max_time * 60) * $SEC_PX;

                $sum[$i]['operator'] = $existing_objects[$i]['operator'];
                $sum[$i]['width'] = $calc1;
                array_push($same, $existing_objects[$i]['operator']);
            }
            
        }
        $sum = array_values($sum);  
        for($i=0; $i < count($sum); $i++){
            $late_bar = new LateBar();
            $late_bar->user_id = $sum[$i]['operator'];

            # Calculating Left
            $late_bar_left = (($hr * 60 * 60) + ($min * 60)) * $SEC_PX;
            $late_bar->left = $late_bar_left;

            $late_bar->width = $sum[$i]['width'];
            $late_bar->color = '#343434';
            $late_bar->text = $vehicle['license_plate'];
            $late_bar->date = $date;
            $late_bar->month = $month;
            $late_bar->year = $year;
            
            $late_bar->save();
        }

        
        if(str_contains(Route::current()->uri, 'admin/')){
            return redirect()->route('admin.worksheets.index')->with([
                'flashSuccess' => 'Worksheet#' . $worksheet->id . ' has been created successfully'
            ]);
        }else{
            return redirect()->route('acceptor.worksheets.index')->with([
                'flashSuccess' => 'Worksheet#' . $worksheet->id . ' has been created successfully'
            ]);
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
