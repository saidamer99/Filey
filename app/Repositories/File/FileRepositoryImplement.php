<?php

namespace App\Repositories\File;

use App\Helpers\ApiResponse;
use App\Http\Resources\FileDetailsResource;
use App\Models\Group;
use Illuminate\Http\JsonResponse;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\File;

class FileRepositoryImplement extends Eloquent implements FileRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(File $model)
    {
        $this->model = $model;
    }

    public function details($request): array
    {
        try {
            $this->user = checkUser();
            if (!$this->user) {

                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $file = File::find($request->file_id);
            $fileGroup = Group::find($request->group_id);
            if (!$file) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "File Not Found";
                return $data;
            }
            if (!$fileGroup) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "Group Not Found";
                return $data;
            }
            if (!$file->group->contains($fileGroup)) {
                $data['status'] = false;
                $data['code'] = 403;
                $data['message'] = "Not allowed";
                return $data;
            }
            if (!$fileGroup->members->contains($this->user)) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not in Group";
                return $data;
            }
            $data['status'] = true;
            $data['code'] = 200;
            $data['file'] = $file;
            return $data;
        } catch (\Throwable $exception) {
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;
        }
    }

    public function checkIn($request):array
    {
        try {
            $this->user = checkUser();
            if (!$this->user) {

                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $files=[];
            foreach ($request->files_ids as $file_id){
              $file=File::find($file_id);
              $file->update([
                  'status'=>"preserver",
                  'current_editor_id'=>$this->user->id
              ]);
              $fileData=[
                  'file_id'=>$file->id,
                  'file_download_path'=>$file->getFirstMediaUrl(),
              ];
                array_push($files,$fileData);
            }
            $data['status'] = true;
            $data['code'] = 200;
            $data['files']=$files;
            $data['message'] = "File checked in";
            return $data;

        }catch (\Throwable $exception){
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;
        }
    }

    public function checkOut($request):array
    {    try {
        $this->user = checkUser();
        if (!$this->user) {

            $data['status'] = false;
            $data['code'] = 404;
            $data['message'] = "User Not Found";
            return $data;
        }
        $group=Group::find($request->group_id);
        foreach ($request->files as $data){
           $file=File::find($data['file_id']);
           $file->media->delete();
           $file->addMedia($data['new_file'])->toMediaCollection($group->id==1?"public":"private_".$group->id);
           $file->update(["status"=>"free","current_editor_id"=>0]);
        }
        $data['data']=$group->groupFiles;
        $data['status'] = true;
        $data['code'] = 200;
        $data['message'] = "File checked out";
        return $data;
    }catch (\Throwable $exception){
        $data['status'] = false;
        $data['code'] = 500;
        $data['message'] = "SomeThing Wrong happened";
        return $data;
    }

    }
    // Write something awesome :)
}
