<?php

namespace App\Repositories\Group;


use App\Helpers\ApiResponse;
use App\Http\Resources\FileResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PaginationResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Group;

class GroupRepositoryImplement extends Eloquent implements GroupRepository
{


    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;
    protected ?User $user;

    public function __construct(Group $model)
    {
        $this->model = $model;
        $this->user = checkUser();
    }

    public function home(): array
    {
        try {
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $groups = Group::where('owner_id', "0")->orWhere('owner_id', $this->user->id)->orderBy('id')->paginate(request()->per_page ?? 5);
            $publicGroup = $groups->where('owner_id', "0")->where('id', 1)->first();


            $data['status'] = true;
            $data['code'] = 200;
            $data['groups'] = $groups;
            $data['public-group-files'] = $publicGroup->groupFiles;
            return $data;

        } catch (\Throwable $exception) {
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;
        }
    }

    public function owned(): array
    {
        try {

            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $groups = $this->user->groupOwner()->paginate(request()->per_page ?? 4);
            $data['status'] = true;
            $data['code'] = 200;
            $data['groups'] = $groups;
            return $data;

        } catch (\Throwable $exception) {
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;

        }
    }

    public function belongs(): array
    {
        try {
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $groups = $this->user->groupMember()->paginate(request()->per_page ?? 4);
            $data['status'] = true;
            $data['code'] = 200;
            $data['groups'] = $groups;
            return $data;

        } catch (\Throwable $exception) {
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;


        }
    }

    public function details($request): array
    {
        try {
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group = Group::find($request->group_id);
            if (!$group) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "Group Not Found";
                return $data;
            }
            $members = $group->members;
            $groupFiles = $group->groupFiles()->paginate(request()->per_page ?? 5);
            $data['status'] = true;
            $data['code'] = 200;
            $data['group-members'] = $members;
            $data['group-files'] = $groupFiles;
            return $data;


        } catch (\Throwable $exception) {
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "Something Wrong happened";
            return $data;
        }
    }

    public function users($request): array
    {
        try {
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group_id = $request->group_id;
            if ($group_id) {
                $group = Group::find($group_id);
                if (!$group) {
                    $data['status'] = false;
                    $data['code'] = 404;
                    $data['message'] = "Group Not Found";
                    return $data;
                }
                $ids = [];
                foreach ($group->members as $member) {
                    array_push($ids, $member->id);
                }
                $users = User::whereNotIn('id', $ids)->get();
                $data['status'] = true;
                $data['code'] = 200;
                $data['users'] = $users;
                return $data;
            } else {
                $data['status'] = true;
                $data['code'] = 200;
                $data['users'] = User::all();
                return $data;
            }

        } catch (\Throwable $exception) {
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;
        }
    }

    public function addUsers($request): array
    {
        try {
            DB::beginTransaction();
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group = Group::find($request->group_id);
            if (!$group) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "Group Not Found";
                return $data;
            }

            $group->members()->attach($request->members);
            DB::commit();
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Users added Successfully";
            return $data;

        } catch (\Throwable $exception) {
            DB::rollBack();
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "Something Wrong happened";
            return $data;

        }
    }

    public function deleteUsers($request): array
    {
        try {
            DB::beginTransaction();
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group = Group::find($request->group_id);
            if (!$group) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "Group Not Found";
                return $data;
            }
            $group->members()->detach($request->members);
            DB::commit();
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Users deleted Successfully";
            return $data;


        } catch (\Throwable $exception) {
            DB::rollBack();
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;

        }

    }

    public function addFiles($request): array
    {
        try {
        DB::beginTransaction();
            $group = Group::find($request->group_id);
            foreach ($request['files'] as $file) {
                $newFile = File::create([
                    'status' => 'free',
                    'owner_id' => $this->user->id,
                    'current_editor_id' => 0,
                    'group_id' => $group->id
                ]);
                $newFile->addMedia($file['file'])->toMediaCollection($request->group_id == 1 ? "public" : "private_".$group->id);
            }
            DB::commit();
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Files added Successfully";
            return $data;
        } catch (\Throwable $exception) {
            DB::rollBack();
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "Something Wrong happened";
            return $data;
        }
    }

    public function deleteFiles($request): array
    {
        try {
            DB::beginTransaction();
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group=Group::find($request->group_id);
            foreach ($request->files_ids as $file_id) {
                $file = File::find($file_id);
                $file->delete();
            }
            DB::commit();
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Files deleted Successfully";
            return $data;
        } catch (\Throwable $exception) {
            DB::rollBack();
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "Something Wrong happened";
            return $data;
        }
    }

    // Crud Operations :)
    public function store($request): array
    {
        try {
            DB::beginTransaction();
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group = Group::create([
                'name' => $request->group_name,
                'owner_id' => $this->user->id,
            ]);
            $group->members()->attach($request->members);
            DB::commit();
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Group created Successfully";
            return $data;
        } catch (\Throwable $exception) {
            DB::rollBack();
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "Something Wrong happened";
            return $data;
        }
    }

    public function updateGroup($request): array
    {
        try {
            DB::beginTransaction();
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group = Group::find($request->group_id);

            $group->update(['name' => $request->group_name]);
            $group->save();
            DB::commit();
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Group updated Successfully";
            $data['group'] = $group;
            return $data;

        } catch (\Throwable $exception) {
            DB::rollBack();
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;

        }
    }

    public function destroy($request): array
    {
        try {
            DB::beginTransaction();
            if (!$this->user) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "User Not Found";
                return $data;
            }
            $group = Group::find($request->group_id);
            if (!$group) {
                $data['status'] = false;
                $data['code'] = 404;
                $data['message'] = "Group Not Found";
                return $data;
            }
            $group->delete();
            DB::commit();
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Group deleted Successfully";
            return $data;
        } catch (\Throwable $exception) {
            DB::rollBack();
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "Something Wrong happened";
            return $data;
        }
    }
}
