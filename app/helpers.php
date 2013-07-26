<?php

function formModel($model, $routeName) {
    $class = 'form-horizontal';

    if ($model->exists) {
        return Form::model($model, array('route' => array($routeName . '.update', $model->id), 'method' => 'PUT', 'class' => $class));
    } else {
        return Form::model($model, array('route' => array($routeName . '.store'), 'class' => $class));
    }
}

function findObjectInArray($array = array(), $item = '', $type = '') {

    foreach($array as $object) {
        if ($object->$type == $item) {
            return $object;
        }
    }

    return null;
}

function popRadio( $value, $data, $checkedByDefault = false) {
    if (!$data) return $checkedByDefault;
    return ($value == $data);
}

function columnExists($column_name, $table_name) {
    return (DB::table('information_schema.columns')
                ->whereTableSchema( Config::get('database.connections.mysql.database') )
                ->whereTableName($table_name)
                ->whereColumnName($column_name)
                ->count() > 0);
}

function tableExists($table_name) {
    return (DB::table('information_schema.tables')
                ->whereTableSchema( Config::get('database.connections.mysql.database') )
                ->whereTableName($table_name)
                ->count() > 0);
}