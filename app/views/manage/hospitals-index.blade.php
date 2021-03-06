@extends('layouts.master')

@section('header')
    <h1>Hospitals</h1>
@stop

@section('body')
    <h2>{{ $trust->latestRevision()->name }}</h2>

    <p class="pull-right">
        <a href="{{ route('manage.index') }}" class="btn"><i class="icon-arrow-left"></i> Trusts</a>
        <a href="{{ route('manage.hospital.create', array($trust->id)) }}" class="btn"><i class="icon-plus"></i> New Hospital</a>
    </p>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hospitals as $hospital)
                <?php
                    $hospitalData = $hospital->latestRevision();
                ?>
            <tr>
                <td>
                    {{ $hospitalData->name }}
                </td>
                <td width="250">
                    <a href="{{ route('manage.hospital.index', array($trust->id, $hospital->id)) }}" class="btn btn-small"><i class="icon-hospital"></i> View Wards</a>
                    <a href="{{ route('manage.hospital.edit', array($trust->id, $hospital->id)) }}" data-toggle="modal" class="btn btn-small"><i class="icon-pencil"></i> Edit Hospital</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade hide" id="deleteModal">
        <div style="display: none;" class="userDeleteUrl"></div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Delete User</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this user? Deleting this user will transfer ownership of all nodes they own to you. This cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cancel</a>
            <a href="#" class="btn btn-primary yes" id="deleteModelConfirm">Yes, Delete it.</a>
        </div>
    </div>

    <script>
        $(document).ready( function() {
            $('#deleteModelConfirm').on('click', function(e) {
                window.location = $('#deleteModal .userDeleteUrl').html();
            });

            $(".deleteModal").click( function(e) {
                e.preventDefault();
                $('#deleteModal .userDeleteUrl').html($(this).attr('href'));

                $("#deleteModal").modal('show');
            });
        });
    </script>

@stop
