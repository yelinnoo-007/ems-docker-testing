<?php

namespace App\Http\Controllers;

use App\Contracts\PlatformUserInterface;
use App\Http\Requests\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Models\PlatformUser;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Config;

class ImageUpdateController extends Controller
{
    use ImageTrait;
    private $genre;

    public function __construct(private PlatformUserInterface $platformUserInterface)
    {
        $this->middleware('auth:sanctum');
        $this->genre = Config::get('variables.ONE');
    }


    public function update(ImageRequest $request, PlatformUser $platform_user)
    {
        $imageId = Image::where('link_id', $platform_user->id)->where('genre', $this->genre)->first()->id;
        $image =  $this->updateImage($request, auth()->user()->id, $this->genre, $this->platformUserInterface, $imageId);
        return new ImageResource($image);
    }
}
