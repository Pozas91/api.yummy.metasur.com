<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return TagCollection|Response
     */
    public function index(Request $request)
    {
        /** @var $user User */
        $user = $request->user();

        return new TagCollection($user->tags()->flatten());
    }
}
