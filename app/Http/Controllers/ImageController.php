<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ImageResource::collection($request->user()->gallery);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request)
    {
        $user = $request->user();

        $user->gallery()->create([
            "path" => $request->file("image")->store("public/images"),
        ]);

        return ImageResource::collection($request->user()->gallery);
    }
}
