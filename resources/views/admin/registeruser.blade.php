@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
@endsection
