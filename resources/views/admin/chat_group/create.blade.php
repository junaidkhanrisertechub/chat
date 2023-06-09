@extends('layouts.app')

@section('content')
<!-- Start Breadcrumbbar -->    

<style>
    .dropdown-toggle{
        height: 40px;
        width: 400px !important;
    }

    #groupError{
        color: red;
    }
</style>


<div class="breadcrumbbar">
    <div class="row align-items-center">
         <div class="col-md-8 col-lg-8">
            <!-- <h4 class="page-title">Add New User</h4> -->
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.chat-groups')}}">Group</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Group</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                
            </div>                        
        </div>
    </div>          
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->    
<div class="contentbar">                
    <!-- Start row -->
    <div class="row">
        <!-- Start col -->
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h5 class="card-title">+ New Group</h5>
                </div>
                <div class="card-body">                 

                @php
                $usersarray = array();
                $emailarray = array();
                foreach($usersList as $users){

                   $email = $users->email;
                   $name = $users->name;
                   $id   = $users->id;

                   $usersarray[$email] = $name;
                   $emailarray[$email] = $id;

                }
                
                $edit_name = '';
                $delete_days = '';
                $edit_id ='';
                $selected_userid = array();
                if(isset($group_info->id)){

                $edit_name =   $group_info->name;
                $delete_days  = $group_info->delete_days;
                $edit_id      = $group_info->id;
               
                foreach( $group_info->members as $member){
                    array_push($selected_userid,$member->user_id); 
                }
              }
                @endphp       

                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                    </div>
                    @endif
                
                    {{ Form::open(array('url' => route('admin.chat-groups.store'), 'method' => 'post')) }}
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('groupname', 'Group Name', array('for' => 'inputName4')); }}
                                {{ Form::text('groupname', $edit_name, ['class' => 'form-control', 'id' => 'groupname', 'placeholder' => 'Enter Group Name', 'required' => 'required']); }}

                                @error('groupname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          
                            <div class="form-group col-md-6">                                
                                {{ Form::label('activedays', 'Group Expiry Days', array('for' => 'inputPassword4')); }}
                                {{ Form::text('activedays', $delete_days, ['class' => 'form-control', 'id' => 'activedays', 'placeholder' => 'Expiry Days', 'required' => 'required']); }}

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>  
                            {{ Form::hidden('groupeditid', $edit_id, ['class' => 'form-control', 'id' =>'groupeditid']); }}

                            {{ Form::hidden('selected_member_ids', implode(',', $selected_userid), ['class' => 'form-control', 'id' =>'selected_member_ids']); }}
                            </div>

<!-- 
                            <label class="col-sm-3 col-form-label">Framework</label> -->
                            <div class="form-group col-md-6">  
                                <select id="userlist" class="js-example-basic-single" multiple name="selectedUser[]" data-live-search="true">                                                           
                                @foreach($usersarray as $email=>$name)
                                    @php   $id = $emailarray[$email]; @endphp
                                    
                                        <option value="{{$id}}~~~{{$name}}" {{ in_array($id,$selected_userid) ? 'selected' : '' }}>  
                                                                             
                                            {{ $email }} 
                                        </option>
                                    @endforeach
                                </select>

                                @error('selectedUser')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                            
                        </div>                        
                        <button type="submit" class="btn btn-primary btn-block createGroup">Create Group</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>
<!-- End Contentbar -->


<link  rel="stylesheet" href="{{url('assets/css/bootstrap-multiselect.css')}}" />
<script type="text/javascript" src="{{url('assets/js/bootstrap-multiselect.js?v=1673156411')}}"></script>

<script>
$(document).ready(function() {

$('#userlist').multiselect({
includeSelectAllOption: false,
nonSelectedText:"Select Users",
}, 'selectAll');	


$('#groupname11').on('blur', function(){

var groupname = $(this).val();
var groupeditid = $('#groupeditid').val();


if(groupname !=''){
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({	
                url:'{{route("admin.chat-groups-exist")}}',
                type:'POST',
                cache:false,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },	
                data:{'group':groupname},		
                beforeSend:function()
                {	
                 
                },
                success:function(data)
                {
                 var obj =  $.parseJSON(data);
                 if(obj.status == 1)
                 {
                    if($('#groupError').length<1 ){
                 
                        $('#groupname').after('<span class="invalid-group" id="groupError" role="alert">'+obj.message+'</span>');
                        return false;
                    }
                 }
                 else{
                    $('#groupError').remove();
                 }
                
                }
			});    
   }
});

$('#createGroup').on('click', function(){


});




});



</script>




@endsection

