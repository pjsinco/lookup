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
        {!! Form::open(['url' => '/physicians/search', 'method' => 'GET', 'id' => 'findADo', 'name' => 'findADo', 'class' => 'form-inline findADo']) !!}
            {!! Form::hidden('city', null,   ['id' => 'city']) !!}
            {!! Form::hidden('state', null,  ['id' => 'state']) !!}
            {!! Form::hidden('zip', null,    ['id' => 'zip']) !!}
            {!! Form::hidden('lat', null,    ['id' => 'lat']) !!}
            {!! Form::hidden('lon', null,    ['id' => 'lon']) !!}
            {!! Form::hidden('s_code', null, ['id' => 'sCode']) !!}
            <div class="form-group">
                {{-- Form::label('location') --}}
                {!! Form::text('specialty', null, ['class' => 'form-control input-lg', 'id' => 'specialty', 'placeholder' => 'Search by name or specialty']) !!}
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
