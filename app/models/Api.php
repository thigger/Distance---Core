<?php

class Api extends \BaseModel {

    protected static $remove = array( 'nodetype', 'owned_by', 'created_by', 'latest_revision', 'published_revision', 'collection_id', 'status');

    public static function makeResponse($content, $root_node = null, $status = 200)
    {
        $contentType = Request::header('Content-Type');

        $content = Api::cleanContent($content);

        if ( $contentType == "text/xml" ) {
            return Api::makeXML($content, $root_node, $status);
        } else if ( $contentType == "application/json" ) {
            return Api::makeJSON($content, $root_node, $status);
        } else {
            return Response::make('Content-Type not recognised.', 400);
        }
    }

    protected static function cleanContent($content) {
        
        if ( ! Input::get('modifiedSince') ) {
            $unset[] = 'deleted_at';
            $unset[] = 'retired_at';
        }

        if ( is_array($content) ) {
                foreach( Api::$remove as $_u ) {
                    unset($content[$_u]);
                }
        } else if ( is_object($content) ) {
            foreach( Api::$remove as $_u ) {
                unset($content->$_u);
            }
        }

        if ( is_array($content) || is_object($content) ) {
            foreach ($content as &$value) {
                $value = Api::cleanContent($value);
            }
        }

        return $content;
    }

    protected static function makeJSON($content, $root_node, $status) {
        return Response::make($content, $status, array('Content-Type' => 'application/json'));
    }

    protected static function makeXML($content, $root_node, $status) {
        $nodeTypes = NodeType::all()->lists('name', 'id');

        $format = new format;

        if ( $root_node != "nodes") {
            if ( method_exists($content, 'toArray') ) {
                return Response::make($format->factory($content->toArray(), null, $nodeTypes)->to_xml($content->toArray(), null, $root_node), $status, array('Content-Type' => 'text/xml'));
            } else {
                $xml = $format->factory($content, null, $nodeTypes)->to_xml($content, null, $root_node);
                return Response::make($xml, $status, array('Content-Type' => 'text/xml'));
            }
        } else {
            $xml = $format->factory($content, null, $nodeTypes)->to_xml($content, null, $root_node);

            // Need to remove the node <nodes> if it exists
            $xml = str_replace(array('<nodes>', '</nodes>'), '', $xml);

            return Response::make($xml, $status, array('Content-Type' => 'text/xml'));
        }
    }
}