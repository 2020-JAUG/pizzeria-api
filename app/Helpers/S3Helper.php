<?php

namespace App\Helpers;

use App\Models\Pizza;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;


final class S3Helper extends DefaultPathGenerator
{

    public static function putImage($request,  Pizza $pizza)
    {
        if ($request->isValid()) {
            $pizza->addMedia($request)->toMediaCollection('images');
        }
    }
}
