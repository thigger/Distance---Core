<?php

return array(
    
    'CMS Global Permissions' => array(
        'value' => 'cms.*',
        'children' => array(

            'Node Types' => array(
                'value' => 'cms.node-types.*',
                'children' => array(
                    
                    'Create' => array(
                        'value' => 'cms.node-types.create'
                    ),
                    'Read' => array(
                        'value' => 'cms.node-types.read'
                    ),
                    'Update' => array(
                        'value' => 'cms.node-types.update'
                    ),
                    'Delete' => array(
                        'value' => 'cms.node-types.delete'
                    ),

                ),
            ),

            'Users' => array(
                'value' => 'cms.users.*',
                'children' => array(
                    
                    'Create' => array(
                        'value' => 'cms.users.create'
                    ),
                    'Read' => array(
                        'value' => 'cms.users.read'
                    ),
                    'Update' => array(
                        'value' => 'cms.users.update'
                    ),
                    'Delete' => array(
                        'value' => 'cms.users.delete'
                    ),

                ),
            ),

            'Groups' => array(
                'value' => 'cms.groups.*',
                'children' => array(
                    
                    'Create' => array(
                        'value' => 'cms.groups.create'
                    ),
                    'Read' => array(
                        'value' => 'cms.groups.read'
                    ),
                    'Update' => array(
                        'value' => 'cms.groups.update'
                    ),
                    'Delete' => array(
                        'value' => 'cms.groups.delete'
                    ),

                ),
            ),

        ),
    ),

    // 'API Access' => array(
    //     'value' => 'api.*',
    // ),

);