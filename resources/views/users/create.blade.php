@extends('layouts.app')

@section('content')
<!-- Start Breadcrumbbar -->                    
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Add New User</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.users')}}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New User</li>
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
                    <h5 class="card-title">Add New User</h5>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                
                    {{ Form::open(array('url' => route('admin.user.store'), 'method' => 'post')) }}
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('name', 'Name', array('for' => 'inputName4')); }}
                                {{ Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', 'required' => 'required']); }}

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('mobile', 'Mobile', array('for' => 'inputMobile4')); }}
                                {{ Form::number('mobile', '', ['class' => 'form-control', 'id' => 'mobile', 'placeholder' => 'Mobile Number', 'required' => 'required']); }}

                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('email', 'E-Mail', array('for' => 'inputEmail4')); }}
                                {{ Form::email('email', '', ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email Address', 'required' => 'required']); }}

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">                                
                                {{ Form::label('password', 'Password', array('for' => 'inputPassword4')); }}
                                {{ Form::text('password', '', ['class' => 'form-control', 'id' => 'password', 'placeholder' => 'Password', 'required' => 'required']); }}

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <button type="submit" class="btn btn-primary btn-block">Create User</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>
<!-- End Contentbar -->

@endsection