<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Pizza extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table = 'pizzas';
    protected $fillable = [
        'name',
        'ingredients',
        'image'
    ];

    protected $casts = [
        'ingredients' => 'array',
    ];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setStepsAttribute($value)
    {
        $this->attributes['ingredients'] = json_encode($value);
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getPriceAttribute()
    {
        $totalIngredientPrice = collect($this->ingredients)->sum();
        $finalPrice = $totalIngredientPrice + ($totalIngredientPrice * 0.5);
        return round($finalPrice, 2);
    }

    public function getIngredientsAttribute($value)
    {
        return json_decode($value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function getSimpleMediaAttribute()
    {
        return $this->getMedia('images')->map(function ($media) {
            return [
                'id' => $media->id,
                'model_type' => $media->model_type,
                'model_id' => $media->model_id,
                'uuid' => $media->uuid,
                'collection_name' => $media->collection_name,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'disk' => $media->disk,
                'conversions_disk' => $media->conversions_disk,
                'size' => $media->size,
                'original_url' => $media->getUrl(),
                'download_url' => $media->getUrl()
            ];
        });
    }
}
