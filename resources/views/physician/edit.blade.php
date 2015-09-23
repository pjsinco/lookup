@extends('app')

@section('content')

<div class="row">

        <div class="row">
            <div class="col-xs-offset-2 col-xs-8 col-xs-offset-2">
                <h1>{{ $physician['full_name'] }} <small>Edit your profile</small></h1>
                <hr />
                <p>Etiam porta sem malesuada magna mollis euismod. Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                <hr />
                {!! Form::model($physician, ['method' => 'PATCH', 'url' => 'physician/' . $physician->aoa_mem_id, 'class' => 'form', 'name' => 'update']) !!}

                    <div class="form-group">
                        <strong>{!! Form::label('Business Address', null, ['class' => 'control-label', 'for' => 'address_1']) !!}</strong>
                        {!! Form::text('address_1', null, ['class' => 'form-control input-lg', 'id' => 'address1', 'placeholder' => 'Enter business address']) !!}
                    </div> <!-- .form-group -->

                    <div class="form-group">
                        <strong>{!! Form::label('Company Name', null, ['class' => 'control-label', 'for' => 'address_2']) !!}</strong>
                        {!! Form::text('address_2', null, ['class' => 'form-control input-lg', 'id' => 'address2', 'placeholder' => 'Enter Company Name']) !!}
                    </div> <!-- .form-group -->

                    
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <strong>{!! Form::label('Business city', null, ['class' => 'control-label', 'for' => 'City']) !!}</strong>
                            {!! Form::text('City', null, ['class' => 'form-control input-lg', 'id' => 'city', 'placeholder' => 'Enter Business City']) !!}
                        </div> <!-- .form-group -->

                        <div class="form-group col-xs-6">
                            <label for="State_Province" class="control-label">Business State</label>
                            <select class="form-control" id="state" name="State_Province">
                                <option value="">N/A</option>
                                <option value="AK">Alaska</option>
                                <option value="AL">Alabama</option>
                                <option value="AR">Arkansas</option>
                                <option value="AZ">Arizona</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DC">District of Columbia</option>
                                <option value="DE">Delaware</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="IA">Iowa</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MD">Maryland</option>
                                <option value="ME">Maine</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MO">Missouri</option>
                                <option value="MS">Mississippi</option>
                                <option value="MT">Montana</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="NE">Nebraska</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NV">Nevada</option>
                                <option value="NY">New York</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="PR">Puerto Rico</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VA">Virginia</option>
                                <option value="VT">Vermont</option>
                                <option value="WA">Washington</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WV">West Virginia</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div> <!-- .form-group -->
                    </div> <!-- .row -->

                    <div class="form-group">
                        <strong>{!! Form::label('Business ZIP', null, ['class' => 'control-label', 'for' => 'Zip']) !!}</strong>
                        {!! Form::text('Zip', null, ['class' => 'form-control input-lg', 'id' => 'zip', 'placeholder' => 'Enter Business Zip']) !!}
                    </div> <!-- .form-group -->

                    <div class="form-group">
                        <strong>{!! Form::label('Phone', null, ['class' => 'control-label', 'for' => 'Phone']) !!}</strong>
                        {!! Form::text('Phone', null, ['class' => 'form-control input-lg', 'id' => 'phone', 'placeholder' => 'Enter Business Phone']) !!}
                    </div> <!-- .form-group -->

                    <div class="form-group">
                        <strong>{!! Form::label('website', null, ['class' => 'control-label', 'for' => 'website']) !!}</strong>
                        {!! Form::url('website', null, ['class' => 'form-control input-lg', 'id' => 'website', 'placeholder' => 'Enter Business Website']) !!}
                    </div> <!-- .form-group -->


                    <div class="form-group">
                        {!! Form::submit('Update Profile', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>



            </div> <!-- .col-xs-12 -->
        </div> <!-- .row -->
        <div class="row">
            <div class="col-xs-offset-8 col-xs-4 text-right">
            </div>
        </div>

        {!! Form::close() !!}

    </div> <!-- .col-xs-12 -->
</div> <!-- .row -->
        
@stop
