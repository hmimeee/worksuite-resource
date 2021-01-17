<?php

namespace Modules\Resource\Http\Controllers\Admin;

use App\Helper\Reply;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Resource\Entities\Resource;
use App\Http\Controllers\Admin\AdminBaseController;

class ResourceController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->pageTitle = 'Resources';
        $this->pageIcon = 'ti-folder';
        $this->icons = $this->icons();
        $this->resources = Resource::paginate(15);
        $this->trashCount = Resource::onlyTrashed()->count();

        return view('resource::admin.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('resource::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //Allowed file type
        $allowedFiles = ['pdf', 'docx', 'svg', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'txt', 'xlsx', 'xls', 'doc', 'ppt', 'zip', 'psd', 'ai', 'eps'];
        $hasDisallowed = false;

        foreach ($request->allFiles() as $key => $file) {
            $fileExt = $file->getClientOriginalExtension();

            //Check if the file is allowed to upload
            if (!in_array($fileExt, $allowedFiles)) {
                $hasDisallowed = true;
                continue;
            }
            $details = $request['details_' . str_replace('file_', '', $key)];
            //Take only 255 charecter for details
            if (strlen($details) > 255) {
                $details = substr($details, 0, 255);
            }
            //get only file name without extention
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $file = $file->store('resources');
            
            Resource::create([
                'user_id' => auth()->id(),
                'file' => $file,
                'ext' =>  $fileExt,
                'name' => $fileName,
                'details' => $details,
            ]);
        }

        if ($hasDisallowed) {
            return Reply::success('Uploaded successfully, except disallowed files');
        }
        
        if (count($request->allFiles()) == 0) {
            return Reply::error('Please select at least one file');
        }

        return Reply::success('Uploaded successfully');
    }

    /**
     * Show the specified resource.
     * @param int $resource
     * @return Response
     */
    public function show(Resource $resource)
    {
        return Storage::download($resource->file);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('resource::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $resource
     * @return Response
     */
    public function delete(Resource $resource)
    {
        if ($resource->delete()) {
            return Reply::success('The file has been moved to trash');
        }
        return Reply::error('Something went wrong');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function trash()
    {
        $this->pageTitle = 'Resources Trash';
        $this->pageIcon = 'ti-trash';
        $this->icons = $this->icons();
        $this->resources = Resource::onlyTrashed()->paginate(15);

        return view('resource::admin.trash', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @return Response
     */
    public function restore($resource)
    {
        $resource = Resource::withTrashed()
        ->find($resource)
        ->restore();

        if ($resource) {
            return Reply::success('File has been restored successfully');
        }

        return Reply::error('Something went wrong');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($resource)
    {
        $resource = Resource::withTrashed()->find($resource);

        $hasFile = Storage::exists($resource->file);
        if ($hasFile) {
            Storage::delete($resource->file);
        }

        if ($resource->forceDelete()) {
            return Reply::success('File permanently deleted');
        }

        return Reply::error('Something went wrong');
    }

    public function icons()
    {
        $mimeType = [
            'txt' => 'fa-file-text',
            'htm' => 'fa-file-code-o',
            'html' => 'fa-file-code-o',
            // 'php' => 'fa-file-code-o',
            'css' => 'fa-file-code-o',
            'js' => 'fa-file-code-o',
            'json' => 'fa-file-code-o',
            'xml' => 'fa-file-code-o',
            'swf' => 'fa-file-o',
            'CR2' => 'fa-file-o',
            'flv' => 'fa-file-video-o',

            // images
            'png' => 'fa-file-image-o',
            'jpe' => 'fa-file-image-o',
            'jpeg' => 'fa-file-image-o',
            'jpg' => 'fa-file-image-o',
            'gif' => 'fa-file-image-o',
            'bmp' => 'fa-file-image-o',
            'ico' => 'fa-file-image-o',
            'tiff' => 'fa-file-image-o',
            'tif' => 'fa-file-image-o',
            'svg' => 'fa-file-image-o',
            'svgz' => 'fa-file-image-o',

            // archives
            'zip' => 'fa-file-o',
            'rar' => 'fa-file-o',
            'exe' => 'fa-file-o',
            'msi' => 'fa-file-o',
            'cab' => 'fa-file-o',

            // audio/video
            'mp3' => 'fa-file-audio-o',
            'qt' => 'fa-file-video-o',
            'mov' => 'fa-file-video-o',
            'mp4' => 'fa-file-video-o',
            'mkv' => 'fa-file-video-o',
            'avi' => 'fa-file-video-o',
            'wmv' => 'fa-file-video-o',
            'mpg' => 'fa-file-video-o',
            'mp2' => 'fa-file-video-o',
            'mpeg' => 'fa-file-video-o',
            'mpe' => 'fa-file-video-o',
            'mpv' => 'fa-file-video-o',
            '3gp' => 'fa-file-video-o',
            'm4v' => 'fa-file-video-o',

            // adobe
            'pdf' => 'fa-file-pdf-o',
            'psd' => 'fa-file-image-o',
            'ai' => 'fa-file-o',
            'eps' => 'fa-file-o',
            'ps' => 'fa-file-o',

            // ms office
            'doc' => 'fa-file-text',
            'rtf' => 'fa-file-text',
            'xls' => 'fa-file-excel-o',
            'ppt' => 'fa-file-powerpoint-o',
            'docx' => 'fa-file-text',
            'xlsx' => 'fa-file-excel-o',
            'pptx' => 'fa-file-powerpoint-o',


            // open office
            'odt' => 'fa-file-text',
            'ods' => 'fa-file-text',

            // archive
            'zip' => 'fa-file-archive-o',
            'rar' => 'fa-file-archive-o',
            'gz' => 'fa-file-archive-o',

        ];

        return $mimeType;
    }
}
