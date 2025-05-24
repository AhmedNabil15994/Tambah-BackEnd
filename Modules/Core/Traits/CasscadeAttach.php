<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait CasscadeAttach
{
    protected static function bootCasscadeAttach()
    {
        static::deleted(function ($model) {
            if (method_exists($model, 'forceDelete')) {
                if ($model->forceDeleting) {
                    static::deletedAttachs($model);
                }
            } else {
                static::deletedAttachs($model);
            }
        });
    }

    protected static function deletedAttachs($model)
    {
        if ($model->casscadeAttachs && is_array($model->casscadeAttachs)) {
            foreach ($model->casscadeAttachs as $attribute) {
                if ($model->$attribute) {
                    $path = str_replace("storage", "app/public", $model->$attribute);

                    File::delete(storage_path($path));
                }
            }

            if ($model->containFolderForAttach == true) {
                static::deleteFolderForModel($model);
            }

        } else {
            static::deleteFolderForModel($model);
        }
    }

    protected static function deleteFolderForModel($model)
    {
        $folderName = $model->folderCasscadeDelete ?? $model->getTable();
        File::deleteDirectory(storage_path("app/public/" . $folderName . "/" . $model->id));
    }


}
