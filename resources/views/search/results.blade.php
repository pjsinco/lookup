@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-8">
            <h1>Physicians near {{ $city }}, {{ $state }}</h1>
        </div>
    </div>

    @if ($specialty)
    <div class="row">
        <div class="col-xs-8">
            <h3>{{ $specialty }}</h3>
        </div>
    </div>
    @endif

    @if ($physicians)
    <div class="row">
        <div class="col-xs-6">
            <ul class="list-unstyled">
                @foreach ($physicians as $physician)
                <a href="/physicians/{{ $physician->id }}">
                    <li>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <ul class="list-unstyled">
                                    <li><h3>{{ $physician->full_name }}</h3></li>
                                    <li><h5>{{ $physician->PrimaryPracticeFocusArea }}</h5></li>
                                    <li>{{ $physician->address_1 }}</li>
                                    <li>{{ $physician->City }}, {{ $physician->State_Province }}</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </a>
                @endforeach 
            </ul>
        </div>
    </div>
    @endif
</div>
@stop
