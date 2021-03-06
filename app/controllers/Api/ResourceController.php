<?php namespace Api;

use Api, Catalogue, Resource, Collection;
use App, Response;

class ResourceController extends \BaseController {
    public function resources()
    {
        $responseCode = 200;

        if ( \Input::get('catalogueID')) {
            $catalogues_ids = array( \Input::get('catalogueID') );
        } else if ( \Request::header('Collection-Token')) {
            $collection_catalogues_query = Collection::whereApiKey(\Request::header('Collection-Token'))->first()->catalogues()->get();
            $catalogues_ids = array();

            foreach ($collection_catalogues_query as $catalogue) {
                $catalogues_ids[] = $catalogue->id;
            }
        } else {
            return Response::make('', 400);
        }

        if (count($catalogues_ids)) {
            $catalogues = Catalogue::whereIn('id', $catalogues_ids);
        } else {
            return Response::make('', 404);
        }

        if ( \Input::get('sync') ) {
            $catalogues = $catalogues->with(array('resources' => function($query) {
                $sync = false;

                if ( \Input::get('sync') == "true") {
                    $sync = 1;
                }

                $query->whereSync( $sync );
            }));
        } else {
            $catalogues = $catalogues->with('resources');
        }

        if ($archive = \Input::get('archive')) {

            if ($archive == 'true') {

                $collection = Collection::whereApiKey(\Request::header('Collection-Token'))->first();

                $directory = storage_path() . '/archives/' . $collection->id;
                $archives = glob($directory . '/*.zip');
                rsort($archives);

                if (count($archives) > 0 and isset($archives[0])) {

                    $timestamp = basename($archives[0], '.zip');

                    $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($archives[0], 200, array(
                        'Last-Modified' => date("Y-m-d\TH:i:s\Z", $timestamp)
                    ), true, 'attachment', false, false);

                    return $response;
                } else {
                    return Response::make('', 404);
                }

            }

        }

        if ( \Input::get('modifiedSince') ) {

            $carbon = new \Carbon\Carbon(\Input::get('modifiedSince'));

            $catalogues = $catalogues->with( array('resources' => function($query) use($carbon) {
                $query->withTrashed();
                $query->where('updated_at', '>', $carbon->toDateTimeString() );
            }));
        }

        $result = $catalogues->get();

        if (\Input::get('modifiedSince')) {
            $empty = true;
            foreach($result as $catalogue) {
                if (count($catalogue->resources) > 0) {
                    $empty = false;
                }
            }

            if ($empty) {
                $responseCode = 304;
            }
        }

        return Api::makeResponse($result, 'catalogues', $responseCode);
    }
    
    public function resource($id, $language = 'en')
    {        
        $resource = Resource::whereId($id)->first();

        if (!$resource) {
            return Response::make('resource not found', 404);
        }
        
        return Resource::fetch($resource->catalogue_id, $language, $resource->filename, true);
    }
}