@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div id="success-data"> 
  @if (\Session::has('success'))
        <div class="alert alert-success">
          {{ \Session::get('success') }}
        </div>
       @endif
</div>

            <div class="card">
                <div class="card-header">{{ __('Bulk Register') }}</div>
                <div class="card-body">
                {{ Form::open(array('url' => 'admin/uploadFile', 'enctype'=>'multipart/form-data', 'method' => 'POST')) }}
                    {{@csrf_field()}}

                    {{Form::file('file') }}

                    {{ Form::submit('Submit') }} 
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

<br/>
<br/>
<?php 
    
    print_r($fileUpload);
?>

@endsection
