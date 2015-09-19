@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 style="margin-top: 1em; font-weight: 200; font-size: 12em; line-height: .8; letter-spacing: -5px; color: #8f8f8f" class="text-center">{{ $location['city'] }}, {{ $location['state'] }} <small style="font-size: 45%; color: #cacaca; letter-spacing: -5px;">{{ $location['zip'] }}</small></h1>
    </div>
</div>
@stop

