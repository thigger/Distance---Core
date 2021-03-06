@extends('layouts.master')

@section('header')
    @if ($nodeType->exists)
        <h1>Editing Node Type</h1>
    @else
        <h1>New Node Type</h1>
    @endif
@stop

@section('js')
    <script>
    $('#collections, #js-category-select').select2();

    $("#js-nodes_container").sortable({
        handle: '.drag_handle',
        placeholder: 'sortable_placeholder',
        forcePlaceholderSize: true
    });

    $('.form-horizontal').on('submit', function(e) {

        var allNamesPresent = true;
        var allRequiredDefaultsPresent = true;

        $('#js-nodes_container .well').each(function(index, value) {

            var name = $(value).find('.category-name-field');

            if (name.val().trim() == '') {
                allNamesPresent = false;
            }

            var required = $(value).find('.category-required-field:checked');

            if (required.length > 0) {
                var def = $(value).find('.category-default-field');

                if (def.length > 0) {
                    if (def.val().trim() == 0) {
                        allRequiredDefaultsPresent = false;
                    }
                }
            }

        });

        if (!allNamesPresent) {
            alert('Please make sure all columns have a name');
            e.preventDefault();
            return false;
        }

        if (!allRequiredDefaultsPresent) {
            alert('Please make sure all required columns have a default value.');
            e.preventDefault();
            return false;
        }

    });

    $('#js-category-add').on('click', function(e) {

        e.preventDefault();

        var chosen_category = $('#js-category-select option:selected').val();

        $.ajax({
            url: "{{ route('node-types.form-template') }}",
            method: 'POST',
            data: {
                category: chosen_category
            },
            success: function(data) {
                var template = $('#js-category_template');
                var injected_template = template.find('li').html(data);
                $('#js-nodes_container').append(template.html());
            },
            error: function(xhr, status, errorString) {
                alert(errorString)
            }
        });

    });

    $(document).on('click', '.js-remove-category', function(e) {
        e.preventDefault();
        $(this).closest('li').remove();
    });

    $(document).on('click', '.js-enum-add', function(e) {

        e.preventDefault();

        var enum_container = $(this).closest('.control-group').find('.enum_values');

        // Grab the first element, empty it and stick it at the bottom
        var ele = enum_container.find('.js-enum-template').clone();

        var labelEle = $(this).closest('.control-group').find('.js-values-label').attr('for')
        ele.find('input').attr('name', labelEle);

        if (enum_container.children('.controls').length == 0) {
            // We have no existing ones, let's set a default
            ele.find('i').addClass('icon-ok');
        } else {
            ele.find('i').addClass('icon-remove');
        }

        enum_container.append(ele.html());

    });

    $(document).on('click', '.js-enum-existing-minus', function(e) {

        e.preventDefault();

        var enum_container = $(this).closest('.control-group').find('.enum_values');
        if ( enum_container.children('.controls').length <= 1) {
            alert('You must have at least one value');
        } else {
            if (confirm("Are you sure you want to remove this value? All nodes that have the value set to empty.")) {
                $(this).closest('div').remove();
            }
        }

    });

    $(document).on('click', '.js-enum-minus', function(e) {

        e.preventDefault();

        var enum_container = $(this).closest('.input').find('.enum_values');
        if ( enum_container.children().length == 2) {
            alert('You must have at least one value');
        } else {
            $(this).closest('div').remove();
        }

    });

    $(document).on('click', '.js-enum-default', function(e) {

        e.preventDefault();

        var enum_container = $(this).closest('.control-group').find('.enum_values');

        enum_container.find('.js-enum-default i').removeClass('icon-ok').addClass('icon-remove');

        $(this).find('i').removeClass('icon-remove').addClass('icon-ok');

        // Now to set the default hidden value
        console.log($(this).closest('js-enum-default').val());

    });

    </script>

@stop

@section('body')
    
    {{ formModel($nodeType, 'node-types', array('class' => 'nodetype-form'), false) }}

    <div class="control-group">
        {{ Form::label('label', 'Name', array('class' => 'control-label')) }}
        <div class="controls">
            {{ Form::text('label', null, array('class' => 'span8')) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('collections', 'Collections', array('class' => 'control-label')) }}
        <div class="controls">
            {{ Form::select('collections[]', Collection::toDropDown(), $nodeCollections, array('class' => 'span8', 'multiple' => 'multiple', 'id' => 'collections')) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('field', 'Field', array('class' => 'control-label')) }}
        <div class="controls">
            {{ Form::select('field', NodeType::categorySelect(), null, array('id' => 'js-category-select', 'class' => 'span7')) }}
            <a href="#" id="js-category-add" class="btn" style="margin-left: 10px">Add</a>
        </div>
    </div>

    <ul id="js-nodes_container">
        @if ($nodeType->exists)
            @foreach($nodeType->columns as $column)
                <li class="well">
                    {{ NodeType::viewForCategory($column->category, $column) }}
                </li>
            @endforeach
        @endif
    </ul>

    <div class="control-group pull-right">
        <div class="controls">
            @if (!$nodeType->exists)
                {{ Form::submit('Create Node Type', array('class' => 'btn btn-primary node-type-submit')) }}
            @else
                {{ Form::submit('Save Changes', array('class' => 'btn btn-primary node-type-submit')) }}
            @endif
        </div>
    </div>

    {{ Form::close() }}

    <div id="js-category_template" style="display: none"><li class="well"></li></div>
@stop