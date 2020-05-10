<?php

use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Recipe::class, 100)->create()->each(function (Recipe $recipe) {
            $recipe->tags()->attach(Arr::random(
                Tag::pluck('id')->toArray(),
                5
            ));
        });
    }
}
