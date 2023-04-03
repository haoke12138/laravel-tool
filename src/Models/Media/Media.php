<?php

namespace ZHK\Tool\Models\Media;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use App\Models\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['width', 'admin_id', 'media_group_id', 'path', 'file_name', 'size', 'type', 'file_ext', 'disk', 'meta', 'created_at'];

    protected $hidden = ['file_name'];
    protected $appends = ['width', 'height', 'size', 'media_type', 'name', 'url'];

    public $timestamps = false;

    public function mediaGroup()
    {
        return $this->belongsTo(model('ZHK.Tool:Media.Group')->getClassname());
    }

    public function getMetaAttribute($val)
    {
        return json_decode($val, 1);
    }

    public function getWidthAttribute()
    {
        return $this->meta['width'];
    }

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getHeightAttribute()
    {
        return $this->meta['height'];
    }

    public function getMediaTypeAttribute()
    {
        return $this->type;
    }

    public function getNameAttribute()
    {
        return $this->file_name;
    }

    public function getSizeAttribute()
    {
        $size = $this->meta['size'];
        $units = array(' B', ' KB', ' M', ' G', ' T');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $units[$i];
    }

    protected function declares()
    {
        return [
            'keyword' => "(file_name like ? or file_ext like ?)",
        ];
    }
}
