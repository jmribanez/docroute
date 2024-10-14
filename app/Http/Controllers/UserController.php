<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Auth::user()->can('list users')) {
            abort(403);
        }
        $users = User::all();
        return view('user.index')
            ->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Auth::user()->can('create user')) {
            abort(403);
        }
        $offices = Office::all();
        return view('user.create')
            ->with('offices',$offices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $length = 6;
        if(!Auth::user()->can('create user')) {
            abort(403);
        }
        $user = new User;
        $user->name_family = $request->name_family;
        $user->name_first = $request->name_first;
        $user->email = $request->email;
        $user->office_id = $request->office;
        $user->password = Hash::make($request->password);
        if($request->hasFile('user_photo')) {
            $allowedFileExtension = ['jpg','jpeg','png'];
            $file = $request->file('user_photo');
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedFileExtension);
            if($check) {
                $newFileName = substr(bin2hex(random_bytes(ceil($length/2))),0,$length);
                $file->storeAs('user_photos',$newFileName . "." . $extension);
                $user->user_photo_url = $newFileName . "." . $extension;
            }
        }
        $user->save();
        $user->assignRole('Standard');
        return redirect('/user')
            ->with('status','success')
            ->with('message',$user->name_first . ' ' . $user->name_family . ' was created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $user??abort('404','User does not exist.');
        return view('user.view')
            ->with('user',$user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(!Auth::user()->can('edit user')) {
            abort(403);
        }
        $user = User::find($id);
        $offices = Office::all();
        $user??abort('404','User does not exist.');
        return view('user.edit')
            ->with('user',$user)
            ->with('offices',$offices);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $length = 6;
        if(!Auth::user()->can('edit user')) {
            abort(403);
        }
        $user = User::find($id);
        if($request->has('accessLevel')) {
            $user->roles()->detach();
            $user->assignRole($request->accessLevel);
            return redirect('/user')
            ->with('status','success')
            ->with('message','User access level updated.');
        }
        if($request->has('newpassword')) {
            $user->password = Hash::make($request->newpassword);
            $user->update();
            return redirect('/user')
            ->with('status','success')
            ->with('message','User password reset.');
        }
        $user->name_family = $request->name_family;
        $user->name_first = $request->name_first;
        $user->email = $request->email;
        $user->office_id = $request->office;
        if($request->hasFile('user_photo')) {
            $allowedFileExtension = ['jpg','jpeg','png'];
            $file = $request->file('user_photo');
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedFileExtension);
            if($check) {
                // Remove old photo
                Storage::delete('user_photos/'.$user->user_photo_url);
                $newFileName = substr(bin2hex(random_bytes(ceil($length/2))),0,$length);
                $file->storeAs('user_photos',$newFileName . "." . $extension);
                $user->user_photo_url = $newFileName . "." . $extension;
            }
        }
        $user->update();
        return redirect()->route('user.edit',$id)
            ->with('status','success')
            ->with('message',$user->name_first . ' ' . $user->name_family . ' was updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        // Remove old photo
        Storage::delete('user_photos/'.$user->user_photo_url);
        $user->forceDelete();
        return redirect('/user')
            ->with('status','success')
            ->with('message',$user->name_first . ' ' . $user->name_family . ' was deleted.');
    }

    public function ajax_findUser(string $searchname) {
        $users = User::select('users.id','name_family','name_first','office_name')->where('name_family','like', $searchname.'%')->orWhere('name_first','like', $searchname.'%')->join('offices','office_id','=','offices.id')->take(5)->get();
        return response()->json($users);
    }
}
