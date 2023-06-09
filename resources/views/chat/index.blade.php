@extends('layouts.app_chat')

@section('content')
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />

    <!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Chat</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Chat</a></li>
                </ol>
            </div>
        </div>
        @can('admin-dashboard')
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="{{route('admin.chat-groups')}}">
                    <button class="btn btn-primary">Chat Groups</button>
                </a>
            </div>
        </div>
        @endcan
    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">

        <!-- Start col -->
        <div class="col-lg-5 col-xl-4">
            <div class="chat-list">
                <div class="chat-search">
                    <form>
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon3">
                            <div class="input-group-append">
                            <button class="btn" type="submit" id="button-addon3"><i class="feather icon-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="chat-user-list">
                    <ul class="list-unstyled mb-0">
                        @if($users->count() > 0)
                            @foreach($users as $user)
                                <li class="media">
                                    <img class="align-self-center rounded-circle" src="assets/images/users/girl.svg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <h5>
                                            {{ $user->name }}
                                            <span class="badge badge-success ml-2">1</span>
                                            <span class="timing">Jan 22</span>
                                        </h5>
                                        <p>Admin</p>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="media">
                                <p>No users found! </p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div><!-- End col -->

        <!-- Start col -->
        <div class="col-lg-7 col-xl-8">
            <div class="chat-detail">
                <div class="chat-head">
                    <ul class="list-unstyled mb-0">
                        <li class="media">
                            <img class="align-self-center mr-3 rounded-circle" src="assets/images/users/girl.svg" alt="Generic placeholder image">
                            <div class="media-body">
                                <h5 class="font-18">Amy Adams</h5>
                                <p class="mb-0">typing...</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="chat-body" id="messageOutput">

                </div>
                <div class="chat-bottom">
                    <div class="chat-messagebar">
                        <chat :user="{{ Auth::user() }}" />
                    </div>
                </div>
            </div>
        </div><!-- End col -->
    </div><!-- End row -->
</div><!-- End contentbar -->
@endsection
