<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Topic;
use App\Models\AttachmentRelationship;
use App\Models\Attachment;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasRoles;
    
    const ROLE_SUPER_ADMIN_NAME = 'super_admin';

    const DEFAULT_AVATAR = '17z1jmEz303dddjKTeVGVCGFEgAIZB2zwMx6hX67.jpeg';

    private static $disk;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!self::$disk)
        {
            self::$disk = \Storage::disk('qiniu');
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'email',
        'avatar',
        'password',
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

    public function avatarUrl($width = "", $height = "")
    {
        if (!$this->avatar)
        {
            $avatar = self::DEFAULT_AVATAR;
        }
        else
        {
            $avatar = $this->avatar;
        }
        if (!$width && !$height)
        {
            return (string)self::$disk->url($avatar);
        }
        $previewOptions = "imageView2/0";
        if ($width)
        {
            $previewOptions .= "/w/$width";
        }
        if ($height)
        {
            $previewOptions .= "/h/$height";
        }
        return (string)self::$disk->imagePreviewUrl($avatar, $previewOptions);

    }

    /**
     * 用户头像，其实是一个附件（图片）
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function avatarAttachment()
    {
        return $this->hasOne(Attachment::class, "key", "avatar")->withDefault(function () {
            return new Attachment([
                'mime_type' => 'image/jpeg',
                'key' => self::DEFAULT_AVATAR,
            ]);
        });
    }
    
    /**
     * 用户全部的头像。target_type = 'user_avatar'
     *
     * @see App\Providers\AppServiceProvider::customMorphMap()
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function allAvatarAttachment()
    {
        return $this->morphToMany(
            Attachment::class,
            "target",
            AttachmentRelationship::TABLE_NAME,
            "target_id",
            "attachment_key",
            "id",
            "key"
        );
    }
    
}
