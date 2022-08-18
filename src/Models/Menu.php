<?php

namespace ZHK\Tool\Models;

use Dcat\Admin\Models\Menu as Base;
use ZHK\Tool\Models\Trail\BatchUpdate;

class Menu extends Base
{
    use BatchUpdate;
}