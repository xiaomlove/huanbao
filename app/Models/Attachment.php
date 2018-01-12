<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\User;

class Attachment extends Model
{
    private static $disk;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::$disk = \Storage::disk('qiniu');
    }

    protected $fillable = [
        'uid',
        'mime_type',
        'key',
        'size',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function commentDetails()
    {
        return $this->morphedByMany(
            CommentDetail::class,
            "target",
            AttachmentRelationship::TABLE_NAME,
            "attachment_key",
            "target_id",
            "key",
            "cid"
        );
    }

    public function url($width = "", $height = "")
    {
        $mimeType = $this->mime_type;
        if (strpos($mimeType, "image") !== false && ($width || $height))
        {
            $previewOptions = "imageView2/0";
            if ($width)
            {
                $previewOptions .= "/w/$width";
            }
            if ($height)
            {
                $previewOptions .= "/h/$height";
            }
            return self::$disk->imagePreviewUrl($this->key, $previewOptions);
        }
        return self::$disk->url($this->key);
    }
    
}
