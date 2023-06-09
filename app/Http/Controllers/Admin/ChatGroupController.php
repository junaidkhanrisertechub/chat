<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chatgroup;
use App\Models\ChatMember;
use Validator;

class ChatGroupController extends Controller
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
     * Show the application chat groups.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $groups = ChatGroup::where('status', '1')->orderBy('id', 'DESC')->with('members')->get(); 
     
        return view('admin.chat_group.index', compact('groups'));
    }

    public function groupCreate(Request $request, $editID=NULL)
    {        
        $group_info ="";
        if($editID){
           $group_info =  ChatGroup::where('id',$editID)->with('members')->first(); 
        } 
      $usersList =  User::all()->where('name','!=', 'Admin');
      return view('admin.chat_group.create')->with(compact('usersList','group_info'));

    }

    public function groupDel(Request $request){

        $del_id = $request->id;
       $ChatGroupDB = ChatGroup::find($del_id);
       $ChatGroupDB->status = 0;

       $ChatGroupDB->save();
       return redirect('/admin/chat-groups');


    }

    public function formValid(Request $request){

        $groupname =  $request->group;
        $groupname_check= ChatGroup::where(['status'=>1, 'name'=>$groupname])->count();
    
        if($groupname_check>0){

        $response['status'] = 1;
        $response['message'] = 'Already Exist. Please use other name';

        }
        else{
            $response['status'] =0;
            $response['message'] = '';
        }
        
    echo   json_encode($response);
    }

    public function storegroupInfo(Request $request){    
       
        $rules=array(            
            'activedays' => ['required'],
            
            "selectedUser" => ["required","array","min:2"],        
            
        );
        if($request->groupeditid ==''){
        $rules['groupname'] = ['required', 'unique:chat_groups,name'];  
        }else{
        $rules['groupname'] = ['required', 'unique:chat_groups,name,'.$request->groupeditid];     
        }
        $this->validate($request, $rules,['selectedUser.required' => 'Please select members.', 'selectedUser.min' => 'Pleas select minimum 2 users.',]);

     
    if($request->groupeditid ==''){

        $ChatGroupDB = new ChatGroup();
    } else{

        $ChatGroupDB = ChatGroup::find($request->groupeditid);
    }
       $ChatGroupDB->name =  $request->groupname;
       $ChatGroupDB->delete_days =  $request->activedays;
       $ChatGroupDB->save();
       $lastInsertedID = $ChatGroupDB->id;
         // delete old members
       ChatMember::where('group_id',$lastInsertedID)->delete();
       if($lastInsertedID !=''){
       
        $selectedUse_array =  $request->selectedUser;

        if(count($selectedUse_array)>0){
        foreach($selectedUse_array as $key=>$selectedUserID)
        {
            $ChatMemberDB = new ChatMember();

            $selectedUserArray = explode('~~~',$selectedUserID);
            $ChatMemberDB->group_id = $lastInsertedID;
            $ChatMemberDB->user_id = $selectedUserArray[0];
            $ChatMemberDB->member_name = $selectedUserArray[1];
            $ChatMemberDB->status = 1;  
            $ChatMemberDB->save();          
       }
     }
    }    
   
    return redirect('/admin/chat-groups');
    


}
}
