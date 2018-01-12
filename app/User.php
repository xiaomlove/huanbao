<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Topic;
use App\Models\AttachmentRelationship;
use App\Models\Attachment;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    
    const ROLE_SUPER_ADMIN_NAME = 'super_admin';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function topics()
    {
        return $this->hasMany(Topic::class, 'uid');
    }
    
    /**
     * 用户头像。target_type = 'user_avatar'
     *
     * @see App\Providers\AppServiceProvider::customMorphMap()
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function avatars()
    {
        return $this->morphToMany(
            Attachment::class,
            "target",
            AttachmentRelationship::TABLE_NAME,
            "target_id",
            "attachment_key",
            "cid",
            "key"
        );
    }
    
}
