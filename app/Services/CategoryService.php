<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    public function checkValidParent($parent_id)
    {
        $parent = Category::find($parent_id);

        if($parent->parent_id){
            return false;
        }

        return true;
    }
}
