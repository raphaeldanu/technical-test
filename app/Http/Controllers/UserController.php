<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Permission\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot(Permission::CAN_ACCESS_USERS)) {
            return redirect()->route('home')->with('warning', "Not Authorized");
        }

        $users = User::all();
        $users->transform(fn($user) => [
            $user->id,
            $user->name, 
            $user->username, 
            '<nobr>
            <a href="'.route('users.show', ['user'=>$user]).'" class="btn btn-xs btn-default text-teal mx-1 shadow p-2" title="Details">
                <i class="fa fa-lg fa-fw fa-eye"></i>
            </a>
            <a href="'.route('users.edit', ['user'=>$user]).'" class="btn btn-xs btn-default text-primary mx-1 shadow p-2" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </a>
            <button class="btn btn-xs btn-default text-danger mx-1 shadow p-2" data-toggle="modal" data-target="#modalDelete'.$user->id.'">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
            </nobr>',
        ]);

        $heads = [
            ['label' => 'ID', 'width' => 5],
            'Name',
            'Username',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'data' => $users->all(),
            'columns' => [null, null, null, ['orderable' => false]],
            'lengthMenu' => [10, 25, 50, 100],
        ];

        return view('users.index', [
            'title' => "Users",
            'config' => $config,
            'heads' => $heads,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->cannot(Permission::CAN_ACCESS_USERS)){
            return redirect()->route('home')->with('warning', "Not Authorized");
        }

        $roles = Role::all();
        $options = $roles->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();

        return view('users.create', [
            'title' => "Create New User",
            'options' => $options,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->safe()->only(['name', 'username']);

        $validated['password'] = Hash::make($request->password);

        $newUser = User::create($validated);
        $newUser->assignRole($request->role);

        if (!$newUser) {
            return back()->withInput()->with('danger', "Failed to save new user");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if ($request->user()->cannot(Permission::CAN_ACCESS_USERS)){
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        return view('users.show', [
            'title' => "User Detail",
            'user' => $user,
            'role' => $user->role(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        if($request->user()->cannot(Permission::CAN_ACCESS_USERS)){
            return redirect()->route('home')->with('warning', "Not Authorized");
        }

        $roles = Role::all();
        $options = $roles->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();

        return view('users.edit', [
            'title' => "Edit User",
            'user' => $user,
            'options' => $options,
            'user_role' => $user->role()->id ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        if($request->username == $user->username){
            $validated = $request->safe()->except(['username']);
        } 
        $user->update($validated);
        
        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->cannot(Permission::CAN_ACCESS_USERS)){
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        $user->syncRoles();
        $user->delete();

        return redirect()->route('users.index')->with('success', "User Deleted Successfully");
    }
}
