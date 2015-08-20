@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-offset-4 col-xs-4 col-xs-offset-4">
            <h1 class="text-center">Find a Workbench</h1>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
        {!! Form::open(['url' => '/results', 'method' => 'GET', 'class' => 'form-inline finda']) !!}
            {!! Form::hidden('city', null, ['class' => 'city']) !!}
            {!! Form::hidden('state', null, ['class' => 'state']) !!}
            {!! Form::hidden('zip', null, ['class' => 'zip']) !!}
            {!! Form::hidden('lat', null, ['class' => 'lat']) !!}
            {!! Form::hidden('lon', null, ['class' => 'lon']) !!}
            <div class="form-group">
                {{-- Form::label('location') --}}
                {!! Form::text('what', null, ['class' => 'form-control input-lg', 'id' => 'physician', 'placeholder' => 'Search by name or specialty']) !!}
            </div>
            <div class="form-group">
                {{-- Form::label('location') --}}
                {!! Form::text('location', null, ['class' => 'form-control input-lg', 'id' => 'location', 'placeholder' => 'City, state or zip']) !!}
            </div>
            <div class="form-group">
                {!! Form::submit('Find a DO', ['class' => 'btn btn-primary btn-lg']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <div id="ragged"></div>
</div> <!-- .container -->
@stop
