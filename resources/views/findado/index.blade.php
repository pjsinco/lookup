@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-offset-4 col-xs-4 col-xs-offset-4">
            <h1 class="text-center">Find a Demo</h1>
        </div>
    </div>
    <hr />
    <div class="row">
        {!! Form::open(['url' => 'location', 'action' => 'GET', 'class' => 'form']) !!}
        <div class="col-xs-offset-2 col-xs-4">
            <div class="form-group">
                {{-- Form::label('location') --}}
                {!! Form::text('name_spec', null, ['class' => 'form-control input-lg', 'id' => 'nameSpec', 'placeholder' => 'Search by name or specialty']) !!}
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                {{-- Form::label('location') --}}
                {!! Form::text('location', null, ['class' => 'form-control input-lg', 'id' => 'location', 'placeholder' => 'City, state or zip']) !!}
            </div>
        </div>
        <div class="col-xs-offset-2 col-xs-4">
            <div class="form-group">
                {!! Form::submit('Find a DO', ['class' => 'btn btn-primary btn-lg']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div> <!-- .container -->
@stop

@section('script')
<script type="text/javascript" charset="utf-8">

    $(document).ready(function() {

        $('#location').load('/location/random', function(responseText, textStatus, jqXHR) {
            var json = JSON.parse(responseText);
            $(this).val(json[0].city + ', ' + json[0].state + ' ' + json[0].zip );
        })

    });

    
</script>
@stop
