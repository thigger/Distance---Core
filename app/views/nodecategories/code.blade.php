<?php
    if (!isset($data)) {
        $value = @$column->default;
    } else {
        $value = @$data->{$column->name};
    }
?>

<link rel="stylesheet" href="/js/codemirror/lib/codemirror.css">
<script src="/js/codemirror/lib/codemirror.js"></script>

@if ( Config::get('core-code-editor.' . $column->syntax . '.scripts') )
  @foreach ( Config::get('core-code-editor.' . $column->syntax . '.scripts')  as $script )
    <script src="{{ $script }}"></script>
  @endforeach
@endif

{{ Form::textarea('nodetype['. $column->name .']', Input::old('nodetype.' . $column->name, $value), ['class' => 'span10', 'id' => 'code']) }}

<script type="text/javascript">
      window.onload = function() {
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        lineWrapping: true,
        mode: "text/html"
      });
      };
    </script>

@if ($column->description)
    <span class="help-block">{{ $column->description }}</span>
@endif