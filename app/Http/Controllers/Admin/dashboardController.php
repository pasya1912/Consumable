<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
//storage
use Illuminate\Support\Facades\Storage;

class dashboardController extends Controller
{
    function index(Request $request)
    {
        //get current username
        //get first day, second day third day of the week
        $dates = [];
        $dates[] = date('Y-m-d', strtotime('monday this week'));
        $dates[] = date('Y-m-d', strtotime('tuesday this week'));
        $dates[] = date('Y-m-d', strtotime('wednesday this week'));
        $dates[] = date('Y-m-d', strtotime('thursday this week'));
        $dates[] = date('Y-m-d', strtotime('friday this week'));
        $dates[] = date('Y-m-d', strtotime('saturday this week'));
        $dates[] = date('Y-m-d', strtotime('sunday this week'));
        $chartData = [];
        foreach($dates as $key => $date){
            //get count approved, rejected and waiting from Requests model in single query assign to data array
            $chartData['all'][$key] = DB::table('request')->whereDate('tanggal',$date)->whereNot('status','canceled')->count();
            $chartData['approved'][$key] = DB::table('request')->where('status','approved')->whereDate('tanggal',$date)->count();
            $chartData['revised'][$key] = DB::table('request')->where('status','revised')->whereDate('tanggal',$date)->count();
            $chartData['rejected'][$key]= DB::table('request')->where('status','rejected')->whereDate('tanggal',$date)->count();
            $chartData['wait'][$key] = DB::table('request')->where('status','wait')->whereDate('tanggal',$date)->count();
        }


        $requests = Requests::where('status', 'wait')->orderBy('id', 'ASC')->get();



        return view('admin.dashboard', compact('chartData', 'requests'));
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

            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPathThumbnail . '/' . $imageName);

            $item->image = $imageName;
            $item->save();

            return back()
                ->with('message', 'Image Upload successful');
        } catch (\Exception $e) {
            return back()
                ->with('message', 'Something wen\'t wrong');
        }



    }


}
