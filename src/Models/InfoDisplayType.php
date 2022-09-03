<?php

namespace ZHK\Tool\Models;

use App\Models\Model;

class InfoDisplayType extends Model
{
    protected $table = 'info_display_type';

    public function getByType($type, $with = [])
    {
        $model = $this->where('type', $type);
        foreach ($with as $item) {
            $model->with($item);
        }

        return $model->get();
    }

    protected function declares()
    {
        return [];
    }
}
