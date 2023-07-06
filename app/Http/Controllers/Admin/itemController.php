<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
//storage
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class itemController extends Controller
{
    function index(Request $request)
    {
        //get current username
        $username = $request->user()->username;
        $find = $request->query('search') == null ? '' : $request->query('search');
        //paginate 2 include query
        $items = Item::select('*')
            ->where(function ($subQuery) use ($find) {
                $subQuery = $subQuery->orWhere('code_item', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('name_item', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('area', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('lemari', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('no2', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('satuan', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('satuan_oca', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('convert', 'LIKE', "%" . $find . "%");
                $subQuery = $subQuery->orWhere('note', 'LIKE', "%" . $find . "%");

            })
            ->orderBy('no', 'DESC')
            ->paginate(10)
            ->appends(request()->query())
            ->toArray();
        return view('admin.item', compact('items'));
    }

    function uploadImage(Request $request)
    {

        $request->validate([
            'code_item' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //find one item
        $item = Item::where('code_item', $request->code_item)->first();
        //if not found return back
        if (!$item) {
            return back()
                ->with('message', 'Item not found');
        }
        $image = $request->file('image');
        $imageName = ($request->code_item ?? time()) . '.' . $image->extension();
        try {
            if (!Storage::disk('public')->exists('image/item')) {
                Storage::disk('public')->makeDirectory('image/item');
            }
            $destinationPathThumbnail = storage_path('app/image/item');
            $img = Image::make($image->path());

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPathThumbnail . '/' . $imageName);

            $item->image = $imageName;
            $item->save();

            return back()
                ->with('message', 'Image Upload successful');
        } catch (\Exception $e) {
            return back()
                ->with('message', 'Something wenFt wrong');
        }
    }
}
