<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class userController extends Controller
{
    public function  list(Request $request)
    {
        $sort = $request->query('sort_by') == null ? 'id' : $request->query('sort_by');
        $find = $request->query('search') == null ? '' : $request->query('search');
        $userList = User::where(function ($subQuery) use ($find){
            $subQuery = $subQuery->orWhere('username', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('name', 'LIKE', "%".$find."%");
        })->where('role',1)->orderBy($sort,'asc')->paginate(10)->appends(request()->query())->toArray();
    return view('admin.userList',compact('userList'));
    }

    public function delete($username,Request $request)
    {

        //prevent from deleting self
        if($request->user()->username == $username){
            return redirect()->route('admin.userList')->with('message','Tidak dapat menghapus akun sendiri');
        }

        try{
        $user = User::where('username',$username)->firstOrFail();
        $user->delete();

        return redirect()->route('admin.userList')->with('message','Berhasil menghapus akun');
        }catch(\Exception $e){
            return redirect()->route('admin.userList')->with('message','Gagal menghapus akun');
        }
    }
}
