<?php

namespace App\Http\Controllers\app;

use App\User;
use App\Worksheet;
use App\WorksheetJob;
use App\WorksheetObject;
use App\WorksheetFailure;
use App\WorksheetSparePart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WorksheetVehicle;

class ManageWorksheetController extends Controller
{

    // FAILURES
    public function failure_index($id){
        return view('app.admin.manage.failure')->with([
            'failures' => WorksheetFailure::where('worksheet_id', $id)->get()
        ]);
    }   

    public function failure_update(Request $request, $id){


        WorksheetFailure::where('worksheet_id', $id)->delete();

        for ($i=0; $i < count($request->failure); $i++) { 
            $failure[$i] = new WorksheetFailure();
            $failure[$i]->worksheet_id = $id;
            $failure[$i]->failure_title = $request->failure[$i]['failure_title'];
            $failure[$i]->save();
        }

        return redirect()->back()->with([
            'flashInfo' => 'Worksheet failures have been updated successfully'
        ]);

    }

    // SPARE PARTS
    public function spare_index($id){
        return view('app.admin.manage.spare')->with([
            'spares' => WorksheetSparePart::where('worksheet_id', $id)->get()
        ]);
    } 

    public function spare_update(Request $request, $id){
        // dd($request->all());
        $spare_parts = $request->spare;
        // dd($spare_parts);
        WorksheetSparePart::where('worksheet_id', $id)->delete();

        for ($i=0; $i < count($spare_parts); $i++) { 
            $spare_parts[$i]['worksheet_id'] = $id;
            $spare_part[$i] = WorksheetSparePart::create($spare_parts[$i]);
        }

        return redirect()->back()->with([
            'flashInfo' => 'Worksheet Spare Parts have been updated successfully'
        ]);

    }


    // JOBS
    public function jobs_index($id){
        return view('app.admin.manage.jobs')->with([
            'jobs_created' => WorksheetJob::where('worksheet_id', $id)->get(),
            'jobs' => WorksheetObject::with('operators')->get(),
            'operators' => User::where('role', '3')->get(),
            'objects' => WorksheetObject::all()
        ]);
    } 

    public function jobs_update(Request $request, $id){
        dd($request->all());
    }

    // Vehicle
    public function vehicle_index($id){
        return view('app.admin.manage.vehicle')->with([
            'vehicle' => WorksheetVehicle::where('worksheet_id', $id)->first()
        ]);
    } 

    public function vehicle_update(Request $request, $id){
        dd($request->all());
    }
}
