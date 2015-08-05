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
        {!! Form::open(['url' => '/results', 'method' => 'GET', 'class' => 'form']) !!}
            {!! Form::hidden('city', null, ['class' => 'city']) !!}
            {!! Form::hidden('state', null, ['class' => 'state']) !!}
            {!! Form::hidden('zip', null, ['class' => 'zip']) !!}
            {!! Form::hidden('lat', null, ['class' => 'lat']) !!}
            {!! Form::hidden('lon', null, ['class' => 'lon']) !!}
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

        var city, state, zip, lat, lon;

        /**
         * Ajax in our random location and populate the location form field
         *
         */
        $('#location').load('api/v1/locations/random', function(responseText, textStatus, jqXHR) {
            var json = JSON.parse(responseText);
            city = json.data.city;
            state = json.data.state;
            zip = json.data.zip;
            lat = json.data.lat;
            lon = json.data.lon;

            $(this).val(json.data.city + ', ' + json.data.state + ' ' + json.data.zip );
            $('.city').val(city);
            $('.state').val(state);
            $('.zip').val(zip);
            $('.lat').val(lat);
            $('.lon').val(lon);
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
                            lat: d.lat,
                            lon: d.lon,
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

        $('#location').bind('typeahead:select', function(evt, suggestion) {
            console.log(suggestion);
            city = suggestion.city;
            state = suggestion.state;
            zip = suggestion.zip;
            lat = suggestion.lat;
            lon = suggestion.lon;
        });

        $('#location').on('blur', function(evt) {
            $('.city').val(city);
            $('.state').val(state);
            $('.zip').val(zip);
            $('.lat').val(lat);
            $('.lon').val(lon);
        })
    });

    
</script>
@stop
