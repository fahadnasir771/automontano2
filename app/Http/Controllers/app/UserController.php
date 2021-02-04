<?php

namespace App\Http\Controllers\app;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.admin.users.index')->with('users', User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.admin.users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $roles = [1 => 'Admin', 2 => 'Acceptor', 3 => 'Operator', 4 => 'Customer'];
        $data = array('title' => $roles[$data['role']]) + $data;

        $data['password'] = bcrypt($data['password']);
        // dd($data);
        User::create($data);
        return redirect()->route('admin.users.index')->with([
            'flashSuccess' => $data['name'] . ' account has been created succesfully'
        ]);
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
        $data = User::where('id', $id)->first();
        return view('app.admin.users.edit',compact('data'));
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

        $update = User::find($id);
        $update->name = $request->name;
        $update->email = $request->email;
        $update->role = $request->role;
        $update->save();
        return redirect()->route('admin.users.index')->with([
          'flashSuccess' => $request->name . ' account has been updated succesfully'
        ]);

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
        $data = User::find($id);
        $data->delete();
        return redirect()->route('admin.users.index')->with([
          'flashSuccess' => $data->name . ' account has been removed succesfully'
        ]);
    }
}
