<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\Chat;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::user()->id)->get();
//        return view('chat.index', compact('users'));
        return view('chat.index', compact('users'));
    }

    public function messages(){
        return Message::with('user')->get();
    }

    public function messageStore(Request $request){
        $user = Auth::user();

//        $messages = $user->messages()->create([
//            'message' => $request->message
//        ]);

        if(request()->has('file')){
            $filename = request('file')->store('chat');
            $message=Message::create([
                'user_id' => request()->user()->id,
                'image' => $filename,
                'receiver_id' => request('receiver_id')
            ]);
        }else{
            $message = auth()->user()->messages()->create(['message' => $request->message]);

        }
        broadcast(new SendMessage(auth()->user(),$message->load('user')))->toOthers();

//        broadcast(new SendMessage($user, $message))->toOthers();
        return $filename;
//        return 'message sent';
    }
    public function send_message(Request $request){
        event(new Chat(
            $request->input('username'),
            $request->input('message')
        ));
        return true;
    }
}
