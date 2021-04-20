<?php

namespace App\Http\Controllers\app;

use App\Chat;
use App\User;
use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'bodyClass' => 'chat-application',
        ];
      
        // $users = User::where('id', '!=', Auth::id())->get();

        $users = DB::select("select users.id, users.role, users.name, users.email, count(is_read) as unread 
        from users LEFT  JOIN  chats ON users.id = chats.from and is_read = 0 and chats.to = " . Auth::id() . "
        where users.id != " . Auth::id() . "
        group by users.id, users.name, users.email, users.role");

        return view('/app/chat/index', [
            'pageConfigs' => $pageConfigs
        ])->with([
            'users' => $users
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chat = new Chat();
        $chat->from = Auth::id();
        $chat->to = $request->receiver_id;
        $chat->message = $request->message;
        $chat->is_read = 0;
        $chat->save();

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

        $data = [
            'from' => Auth::id(),
            'to' => $request->receiver_id
        ];

        $pusher->trigger('my-channel', 'chat', $data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $my_id = Auth::id();

        // notifications get cleared
        Chat::where(['from' => $user_id, 'to' => $my_id])->update(['is_read' => 1]);

        // getting all the chats
        $chats = Chat::where(function($query) use($user_id, $my_id){
            $query->where('from', $my_id)->where('to', $user_id);
        })->orWhere(function($query) use($user_id, $my_id){
            $query->where('from', $user_id)->where('to', $my_id);
        })->orderBy('id', 'ASC')->get();


        return view('app.chat.chats')->with([
            'chats' => $chats,
            'to' => User::find($chats[0]->from ?? '')
        ]);
    }

    public function chat_status($user_id)
    {
        $receiver = User::find($user_id);
        return [
            'chat_status' => $receiver->login_status
        ];
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
