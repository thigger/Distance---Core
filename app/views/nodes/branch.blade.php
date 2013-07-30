<ol class="dd-list">
    @foreach ($branches as $branch)
        <li class="dd-item" data-id="{{ $branch->id }}">
            <div class="pull-right node-hierarchy-buttons">
                <small class="muted"><em>{{ $nodeTypes[$branch->node->node_type]->label }}</em></small> {{ $branch->node->statusBadge }}
                <div class="btn-group">
                    <a href="{{ route('nodes.view', [$collection->id, $branch->node->id, 'branch', $branch->id]) }}" rel="tooltip" title="View" class="btn btn-mini"><i class="icon-search"></i></a>
                    <a href="{{ route('nodes.edit', [$collection->id, $branch->node->id, 'branch', $branch->id]) }}" rel="tooltip" title="Edit" class="btn btn-mini"><i class="icon-edit"></i></a>
                    @if (Sentry::getUser()->hasAccess('cms.collections.' . $collection->id . '.hierarchy-management'))
                        <a href="#" rel="tooltip" title="Add Link" class="btn btn-mini open-node-modal"><i class="icon-link"></i></a>
                        <a href="#" rel="tooltip" title="Remove Link" class="btn btn-mini open-remove-link-modal"><i class="icon-unlink"></i></a>
                    @endif
                </div>
            </div>
            <div class="dd-handle">
                {{ $branch->node->title }}
            </div>

            @if (count($branch->getChildren()))
                @include('nodes.branch', array('branches' => $branch->getChildren(), 'nodeTypes' => $nodeTypes))
            @endif
        </li>
    @endforeach
</ol>