@extends('layouts.app')

@section('content')
<!-- Start Breadcrumbbar -->                    
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Chat groups</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('chat')}}">Chats</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chat Groups</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                @can('user-create')
                <a href="{{ route('admin.chat-groups-create') }}">
                    <button class="btn btn-primary">Add New Chat Group</button>
                </a>
                @endcan
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
                    <h5 class="card-title">Chat Groups</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Members</th>
                                    <th>Active Days</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group)

                                
                                    <tr>
                                        <td>{{ $group->name }}</td>
                                        <td>@php foreach($group->members as $memberInfo){
                                             $member_name= $memberInfo['member_name'];

                                            @endphp
                                            <span class="badge bg-info text-dark">{{$member_name}}</span>
                                            @php
                                            }
                                            @endphp
                                        </td>
                                        <td>{{ $group->delete_days }}</td>
                                        <td><a href="{{route('admin.chat-groups-create')}}/{{$group->id}}" >Edit</a>&nbsp;&nbsp;<a href="{{route('admin.chat-groups-del')}}/{{$group->id}}" >Delete</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>
<!-- End Contentbar -->
@endsection