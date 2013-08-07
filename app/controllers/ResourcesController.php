<?php

class ResourcesController extends BaseController
{
    
    public function load($fileName)
    {
        return Resource::fetch(urldecode($fileName));
    }

    public function index() {
        $catalogues = Collection::current()->catalogues()->with('resources')->get();

        return View::make('resources.index', compact('catalogues'));
    }

    public function show($catalogueId) {
        $catalogue = Catalogue::with('resources')->findOrFail($catalogueId);

        return View::make('resources.show', compact('catalogue'));
    }

    public function create() {
        $catalogue = new Catalogue;
        $collections = Collection::get();

        return View::make('catalogues.form', compact('catalogue', 'collections'));
    }

    public function store() {
        // Let's run the validator
        $validator = new Core\Validators\Catalogue;

        // If the validator fails
        if ($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        $catalogue = new Catalogue;

        $catalogue->name = Input::get('name');
        $catalogue->restrictions = array_filter(explode(',', trim(Input::get('restrictions', ''))));

        $catalogue->save();

        $catalogue->collections()->sync(Input::get('collections', []));

        return Redirect::route('catalogues.index')
                ->with('successes', new MessageBag(array($catalogue->name . ' has been created.')));
    }

    public function edit($catalogueId) {
        $catalogue = Catalogue::with('collections')->findOrFail($catalogueId);
        $collections = Collection::get();

        return View::make('catalogues.form', compact('catalogue', 'collections'));
    }

    public function update($catalogueId) {

        $catalogue = Catalogue::findOrFail($catalogueId);

        // Let's run the validator
        $validator = new Core\Validators\Catalogue;

        // If the validator fails
        if ($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        $catalogue->name = Input::get('name');
        $catalogue->restrictions = array_filter(explode(',', trim(Input::get('restrictions', ''))));
        $catalogue->collections()->sync(Input::get('collections', []));

        $catalogue->save();


        return Redirect::route('catalogues.index')
                ->with('successes', new MessageBag(array($catalogue->name . ' has been updated.')));
    }

    public function process($catalogId) {

        $response = array('success' => false);

        $catalogue = Catalogue::find($catalogId);

        if (!$catalogue) {
            $response['msg'] = 'Invalid catalogue.';
            return json_encode($response);
        }

        $uploadPath = app_path() . '/../resources/';
        $fileUpload = Input::file('file');

        if ($fileUpload->getError() > 0) {

            switch($fileUpload->getError()) {
                case 1:
                case 2:
                    $response['msg'] = 'The file was bigger than the maximum allowed file size.';
                    break;
                case 7:
                    $response['msg'] = 'The destination folder is not set to writable.';
                    break;
                default:
                    $response['msg'] = 'An unknown error occured.';
                    break;
            }

            return json_encode($response);
        }

        $fileName = $fileUpload->getClientOriginalName();

        // Let's replace all spaces with underscores
        $fileName = str_replace(' ', '_', $fileName);

        $fileExt = $fileUpload->getClientOriginalExtension();
        $fileBeforeExt = str_replace('.' . $fileExt, '', $fileName);

        // We don't want to overwrite if the file is already there...
        if (file_exists($uploadPath . $fileName)) {
            // Remove the ext and add a random number (and hope that it doesn't exist!!)
            $rand = substr(md5(time()), 0, 6);
            $fileName = $fileBeforeExt . '-' . $rand . '.' . $fileExt;
            $fileBeforeExt = $fileBeforeExt . '-' . $rand;
        }

        $fileMime = $fileUpload->getMimeType();

        if ($fileUpload->move($uploadPath, $fileName)) {

            $resource = new Resource;
            $resource->filename = $fileName;
            $resource->catalogue_id = $catalogue->id;
            $resource->sync = 1;
            $resource->mime = $fileMime;

            /*
                No thumbnails for now...
            
            // Now to create the thumbnail versions
            $dimensions = array(
                'view' => new \Imagine\Image\Box(150,100),
                'thumb' => new \Imagine\Image\Box(75,50),
            );

            $imagine = new \Imagine\Gd\Imagine();
            $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

            foreach($dimensions as $key => $size) {
                try {
                    $imagine->open($uploadPath . $fileName)
                            ->thumbnail($size, $mode)
                            ->save($uploadPath . '/' . $key . '/' . $fileName);
                } catch(\InvalidArgumentException $e) {
                    // Do nothing as this is probably just a PDF
                }
            }

            */

            if ($resource->save()) {
                $response['data'] = $resource->toArray();
                $response['success'] = true;
                return json_encode($response);
            } else {
                $response['msg'] = 'Failed to save file info to the database.';
                return json_encode($response);
            }

        } else {
            $response['msg'] = 'Failed to move file to final destination.';
            return json_encode($response);
        }

    }

}