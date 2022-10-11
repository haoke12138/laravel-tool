<?php

namespace ZHK\Tool\Models;

use App\Models\Model;

class InfoDisplay extends Model
{
    public $prev, $next;
    
    public function category()
    {
        return $this->belongsTo(model('InfoDisplayType')->getClassname());
    }

    public function getByType($type, $with = [])
    {
        $model = $this->where('type', $type);
        foreach ($with as $item) {
            $model->with($item);
        }

        return $model->get();
    }

    public function getByCategory($ids)
    {
        return $this->whereIn('category_id', $ids)->get();
    }

    public function nexts($type = '')
    {
        if ($this->next) {
            return $this->next;
        }

        return $this->next = $this->where('type', $type)->where('id', '>', $this->id)->first();
    }

    public function previous($type = '')
    {
        if ($this->prev) {
            return $this->prev;
        }

        return $this->prev = $this->where('type', $type)->where('id', '<', $this->id)->first();
    }

    protected function declares()
    {
        return [];
    }
}
