<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Parental\HasChildren;

class Taxonomy extends Model
{
    use HasChildren;
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'type', 'title', 'slug', 'meta', 'description', 'weight',
    ];

    protected $casts = [
        'meta' => AsArrayObject::class,
    ];

    public function toSearchableArray(): array
    {
        return ['id', 'title'];
    }
}
