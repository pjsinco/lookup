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
<script src="js/typeahead.bundle.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">

    $(document).ready(function() {

        /**
         * Ajax in our random location and populate the location form field
         *
         */
        $('#location').load('api/v1/locations/random', function(responseText, textStatus, jqXHR) {
            var json = JSON.parse(responseText);
            $(this).val(json.data.city + ', ' + json.data.state + ' ' + json.data.zip );
        });

        var locations = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            limit: 10,
            remote: {
                url: 'api/v1/locations/%QUERY',
                wildcard: '%QUERY',
                filter: function(locations) {
                    return $.map(locations, function(d) {
                        return {
                            city: d.city,
                            state: d.state,
                            zip: d.zip,
                            value: d.city + ', ' + d.state + ' ' + d.zip
                        }
                    })
                }
            }
        }) 

        locations.initialize();

        $('#location').typeahead({
            hint: true,
            highlight: true
        }, {
            name: 'countries',
            displayKey: 'value',
            source: locations.ttAdapter()
        });
    });

    
</script>
@stop
