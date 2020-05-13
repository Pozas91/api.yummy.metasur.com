<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeCollection;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return RecipeCollection|Response
     */
    public function index(Request $request)
    {
        /** @var $user User */
        $user = $request->user();

        if ($request->has('query')) {
            $recipes = Recipe::search($request->get('query'))
                ->where('user_id', $user->getAttribute('id'))
                ->orderBy('name')
                ->paginate(20);
        } else {
            $recipes = $user->recipes()->orderBy('name')->paginate(20);
        }

        return new RecipeCollection($recipes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate(Recipe::$rules);

        /** @var User $user */
        $user = $request->user();

        // Prepare tags collection
        $tags = collect($request->input('tags'))->map(function ($tag) {
            return Tag::firstOrCreate(Arr::only($tag, ['name']));
        });

        // Prepare a new recipe
        $recipe = new Recipe;
        $recipe->setAttribute('name', $request->input('name'));
        $recipe->setAttribute('rations', $request->input('rations'));
        $recipe->setAttribute('ingredients', $request->input('ingredients'));
        $recipe->setAttribute('description', $request->input('description'));
        $recipe->setAttribute('steps', $request->input('steps'));
        $recipe->setAttribute('duration', $request->input('duration'));

        // Save relationships
        $user->recipes()->save($recipe);
        $recipe->tags()->saveMany($tags);

        // Update indexes
        $recipe->searchable();

        return response(new RecipeResource($recipe), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Recipe $recipe
     * @return RecipeCollection|RecipeResource|Response
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return RecipeResource
     */
    public function update(Request $request, Recipe $recipe)
    {
        // Validate input
        $request->validate(Recipe::$rules);

        /** @var User $user */
        $user = $request->user();

        // Prepare tags collection
        $tags = collect($request->input('tags'))->map(function ($tag) {
            return Tag::firstOrCreate(Arr::only($tag, ['name']));
        });

        $recipeRules = collect($request->except('tags'));
        $recipe->update($recipeRules->only(array_keys(Recipe::$rules))->toArray());

        // Remove duplicate and keep only ids.
        $tagsUnique = $tags->unique()->pluck('id');

        // Synchronize unique tags with recipe model.
        $recipe->tags()->sync($tagsUnique);

        // Update indexes
        $recipe->searchable();

        return new RecipeResource($recipe);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Recipe $recipe
     * @return Response
     * @throws Exception
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return response(null, 204);
    }

    /**
     * Generate a PDF with all recipes
     * @param Request $request
     * @return Response
     */
    public function pdf(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $recipes = $user->recipes()->get();

        // Set locale to app locale
        Carbon::setLocale(config('app.locale'));

        // Extends execution time to 5 minutes.
        set_time_limit(300);

        $pdf = PDF::loadView('recipes.pdf', compact('recipes'));
        return $pdf->download('mis-recetas.pdf');
    }
}
