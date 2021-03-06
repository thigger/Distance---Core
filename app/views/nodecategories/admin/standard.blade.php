<i class="icon-sort drag_handle"></i>
<?php $identifier = uniqid(); ?>

<input type="hidden" name="columns[{{ $identifier }}][category]" value="{{ $category['name'] }}" />

@if ($data)
    <input type="hidden" name="columns[{{ $identifier }}][name]" value="{{ $data->name }}" />
@endif

<div class="control-group">
    
    <div class="controls">
        <p><strong>{{ $category['label'] }}</strong> - <a href="#" class="js-remove-category">Remove Field</a></p>
    </div>
</div>

<div class="control-group">
    {{ Form::label('columns[' . $identifier . '][label]', 'Name', array('class' => 'control-label')) }}
    <div class="controls">
        {{ Form::text('columns[' . $identifier . '][label]', @$data->label, array('class' => 'span4 category-name-field')) }}
    </div>
</div>

<div class="control-group">
    {{ Form::label('columns[' . $identifier . '][description]', 'Description', array('class' => 'control-label')) }}
    <div class="controls">
        {{ Form::text('columns[' . $identifier . '][description]', @$data->description, array('class' => 'span4')) }}
    </div>
</div>

<div class="control-group">
    @if ($category['name'] == 'resource' or $category['name'] == 'html' or $category['name'] == 'html-i18n')
        {{-- Catalogue selection with extension filtering --}}
        {{ Form::label('columns[' . $identifier . '][catalogue]', 'Catalogue', array('class' => 'control-label')) }}
        @if (!$data)
            <div class="controls">
                <p>Please save the node type and then come back to choose a catalogue for each collection</p>
            </div>
        @else
            @foreach(Collection::all() as $collection)
                <div class="controls">
                        {{ Form::select('columns[' . $identifier . '][catalogue][' . $collection->id . ']', Catalogue::forNodeTypeSelect($collection->id), @$data->catalogue->$collection->id, array('class' => 'span4')) }}
                        <span class="help-inline">{{ $collection->name }}</span>
                </div>
                <br />
            @endforeach
        @endif
    </div>

    <div class="control-group">
    {{ Form::label("columns[{$identifier}][includeWhenExpanded]", 'Include when expanded', array('class' => 'control-label')) }}
    <div class="controls">
        <label class="radio inline">
            {{ Form::radio("columns[{$identifier}][includeWhenExpanded]", 1, popRadio(1, @$data->includeWhenExpanded)) }} Yes
        </label>
        <label class="radio inline">
            {{ Form::radio("columns[{$identifier}][includeWhenExpanded]", 0, popRadio(0, @$data->includeWhenExpanded, true)) }} No
        </label>
    </div>
    
    @elseif ($category['name'] == 'bit')
    @elseif ($category['name'] == 'enum')
    @elseif ($category['name'] == 'enum-multi')
    @elseif ($category['name'] == 'nodelookup')
    @elseif ($category['name'] == 'nodelookup-multi')
    @elseif ($category['name'] == 'date')
    @else

        {{ Form::label('default_value', 'Default Value', array('class' => 'control-label')) }}
        <div class="controls">
            {{ Form::text('columns[' . $identifier . '][default]', @$data->default, array('class' => 'span4 category-default-field')) }}
        </div>

        @if ($category['name'] == 'date')
            DATE
        @endif
    @endif
</div>

{{-- Radio buttons (aka booleans) are always going to be present anyway --}}
@if ($category['name'] != 'bit' and $category['name'] != 'resource')
    <div class="control-group">
        {{ Form::label("columns[{$identifier}][required]", 'Required', array('class' => 'control-label')) }}
        <div class="controls">
            <label class="radio inline">
                {{ Form::radio("columns[{$identifier}][required]", 1, popRadio(1, @$data->required), array('class' => 'category-required-field')) }} Yes
            </label>
            <label class="radio inline">
                {{ Form::radio("columns[{$identifier}][required]", 0, popRadio(0, @$data->required, true)) }} No
            </label>
        </div>
    </div>
@endif
