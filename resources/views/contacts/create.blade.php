@extends('layouts.main')

@section('content')

 
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Add Contact</strong>
            </div><!-- end .panel-heading -->
            
            {!! Form::open(['route' => 'contacts.store','files' => true]) !!}

      	@include('contacts.form')
			{!! Form::close() !!}
          </div> <!-- end .panel.panel-default -->
          
       

@endsection