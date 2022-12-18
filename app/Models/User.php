<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $email)
 * @method static whereNotIn(string $string)
 * @property mixed $id
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];



    public function groupOwner(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Group::class,"owner_id",'id')->orderBy("created_at","DESC");
    }
    public function groupMember(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return$this->belongsToMany(Group::class,'group_users','user_id','group_id')->orderBy("created_at","DESC");
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class,'owner_id','id');
    }
    public function modifiedFiles($group_id): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class,'current_editor_id','id')->where('group_id',$group_id);
    }

}
