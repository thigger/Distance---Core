@extends('layouts.master')

@section('header')
    <h1>{{ $collection->name }}</h1>
@stop

@section('js')
    <style> table thead .cursor{ cursor: pointer; }</style>
    <script type="text/javascript" src="/js/stupidtable.js"></script>
    <script>
        $("table").stupidtable();

        $('#openNodeModal').on('click', function(e) {
            e.preventDefault();

            @if (Route::currentRouteName() == 'nodes.type-list')
                {{-- take them straight there! --}}
                var url = "{{ route('nodes.create', array($collection->application_id, $collection->id, $nodeType->id)) }}";

                window.location = url;

            @else
                $('#addNodeModal').modal('show');
            @endif
        });

        $('#addNodeConfirm').on('click', function(e) {

            var nodeType = $('#node_type_select').val();

            var url = "{{ route('nodes.create', array($collection->application_id, $collection->id)) }}/" + nodeType;

            window.location = url;

        });
    </script>
@stop

@section('body')

    <form class="form-inline pull-left">
        @if (Route::currentRouteName() !== 'nodes.type-list')
            {{ Form::select('filter', array('' => 'No Filter') + $collection->nodetypes->lists('label', 'id'), Input::get('filter') ?: 0) }}
        @endif
        <input type="submit" value="Go" class="btn" />
    </form>

    <p class="pull-right">

        @if (Route::currentRouteName() == 'nodes.type-list')
            <a href="{{ route('nodes.list', array($collection->application_id, $collection->id)) }}" class="btn"><i class="icon-list"></i> Node List</a>
        @endif

        @if (Config::get('core.features.hierarchy'))
            <a href="{{ route('nodes.hierarchy', array($collection->application_id, $collection->id)) }}" class="btn"><i class="icon-sitemap"></i> Hierarchy</a>
        @endif
        
        @if (count(NodeType::forSelect($collection, false, 'create')))
            <a href="{{ route('nodes.create', array($collection->application_id, $collection->id)) }}" class="btn" id="openNodeModal"><i class="icon-plus"></i> New Node</a>
        @endif
    </p>
    
    <table class="table">

        <thead>
            <tr>
                <th class="cursor" data-sort="int">ID</th>
                <th class="cursor" data-sort="string">Title</th>
                <th class="cursor" data-sort="string">Node Type</th>
                <th class="cursor" data-sort="string">Status</th>
                <th class="cursor" data-sort="string">Owner</th>
                <th class="cursor" data-sort="int">Created</th>
                <th width="150"></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($nodes as $node)
                @include('nodes.list-row', compact('node', 'nodeTypes'))
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center">
        <?php echo $nodes->links(); ?>
    </div>

    <div class="modal hide fade" id="addNodeModal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Add Node</h3>
        </div>
        <div class="modal-body">
            <p>Please select a node type to add.</p>
            {{ Form::select('node_type', NodeType::forSelect($collection, false, 'create'), null, array('id' => 'node_type_select', 'class' => 'select2'))}}
            <div id="addNodeModalExisting" style="display: none;">
                {{ Form::hidden('existing_node', null, array('id' => 'existing_node_select')) }}
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn">Close</a>
            <a href="#" class="btn btn-primary" id="addNodeConfirm">Add Node</a>
        </div>
    </div>

@stop