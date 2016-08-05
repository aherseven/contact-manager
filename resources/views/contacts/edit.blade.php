@extends('layouts.main')

@section('content')

 
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Add Contact</strong>
            </div><!-- end .panel-heading -->
            
            {!! Form::model($contact, ['files' => true, 'method'=>'PATCH','route' => ['contacts.update', $contact->id] ] ) !!}

      	@include('contacts.form')
			{!! Form::close() !!}
          </div> <!-- end .panel.panel-default -->
          
       

@endsection