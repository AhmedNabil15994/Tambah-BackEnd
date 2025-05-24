<?php

namespace Modules\Core\Traits;


trait CoreTrait
{

    public function removeEmptyValuesFromArray($inputArray)
    {
        $collection = collect($inputArray);
        $filteredItems = $collection->filter(function ($value, $key) {
            return $value != null && $value != '';
        });
        return $filteredItems->all();
    }

    public function uploadImage($imgPath, $img)
    {
        $imgName = $img->hashName();
        $img->move($imgPath, $imgName);
        return $imgName;

        /*$imgName = $img->hashName();
        $ImageUpload = \Image::make($img);
        $ImageUpload->save($imgPath . '/' . $imgName);
        return $imgName;*/
    }

}
