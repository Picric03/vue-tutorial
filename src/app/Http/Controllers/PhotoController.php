<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'download', 'show']);
    }

    public function create(StorePhoto $request)
    {
        $extension = $request->photo->extension();

        $photo = new Photo();

        $photo->filename = $photo->id . '.' . $extension;
        Storage::disk('public')->putFileAs('', $request->photo, $photo->filename);

        DB::beginTransaction();

        try {
            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Storage::disk('public')->delete($photo->filename);
            throw $exception;
        }

        return response($photo, 201);
    }

    public function index()
    {
        $photo = Photo::with(['owner'])
            ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        return $photo;
    }

    public function download(Photo $photo)
    {
        if (!Storage::disk('public')->exists($photo->filename)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.$photo->filename.'"',
        ];

        return response(Storage::disk('public')->get($photo->filename), 200, $headers);
    }

    public function show(string $id)
    {
        $photo = Photo::where('id', $id)->with(['owner'])->first();
        return $photo ?? abort(404);
    }


}
