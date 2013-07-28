@extends('layouts.master')

@section('header')
    @if ($app->exists)
        <h1>Editing App</h1>
    @else
        <h1>New App</h1>
    @endif
@stop

@section('body')
    
    {{ formModel($app, 'apps') }}

    <div class="control-group">
        {{ Form::label('name', 'Name', ['class' => 'control-label']) }}
        <div class="controls">
            {{ Form::text('name', null, ['class' => 'span11']) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('api_key', 'API Key', ['class' => 'control-label']) }}
        <div class="controls">
            {{ Form::text('api_key', Input::old('api_key', ($app->api_key) ?: md5(rand())), ['class' => 'span11']) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('collections', 'Collections', ['class' => 'control-label']) }}
        <div class="controls">
            {{ Form::select('collections[]', $collections->lists('name', 'id'), $app->collections->lists('id'), ['class' => 'span11 select2', 'multiple' => 'multiple', 'data-placeholder' => 'Select the available collections']) }}
        </div>
    </div>

    <div class="form-actions">
        @if ($app->exists)
            <input type="submit" class="btn btn-primary" value="Save changes" />
        @else
            <input type="submit" class="btn btn-primary" value="Create App" />
        @endif
    </div>

    {{ Form::close() }}

@stop