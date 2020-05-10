<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Recipe extends Model
{
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'duration', 'image', 'ingredients', 'rations', 'steps', 'tags'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $with = [
        'tags'
    ];

    public static $rules = [
        'name' => ['required', 'min:3'],
        'rations' => ['required', 'integer', 'min:1'],
        'duration' => ['required', 'integer', 'min:1'],
        'ingredients' => ['required', 'string', 'min:3'],
        'description' => ['required', 'string', 'min:3'],
//            'image' => ['image'],
        'steps' => ['required', 'string', 'min:3'],
        'tags' => ['required'],
        'tags.*.name' => ['required', 'min:3'],
    ];

    // MARK: SCOUT

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'recipes_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...
        unset($array['created_at']);
        unset($array['updated_at']);
        unset($array['rations']);
        unset($array['duration']);
        unset($array['image']);
        unset($array['rations']);

        $array['tags'] = collect($array['tags'])->map(function ($tag) {
            return $tag['name'];
        })->toArray();

        return $array;
    }

    // MARK: MUTATORS

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
