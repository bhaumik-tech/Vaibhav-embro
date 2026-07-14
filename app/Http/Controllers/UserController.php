<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:users,edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:users,remove', only: ['destroy']),
        ];
    }

    public function index()
    {
        $users = User::orderBy('updated_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $firms = Firm::getPermitted();
        return view('users.create', compact('firms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4',
            'primary_firm_name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:20',
            'post' => 'nullable|string|max:255',
            'second_mobile_no' => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
            'page_permissions' => 'nullable|array',
        ]);

        $permissionsString = $request->permissions ? implode(',', $request->permissions) : null;

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'primary_firm_name' => $request->primary_firm_name,
            'mobile_no' => $request->mobile_no,
            'post' => $request->post,
            'second_mobile_no' => $request->second_mobile_no,
            'permission' => $permissionsString,
            'page_permissions' => $request->page_permissions,
        ]);

        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }

    public function edit(User $user)
    {
        $firms = Firm::getPermitted();
        return view('users.edit', compact('user', 'firms'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:4', // Password is optional on update
            'primary_firm_name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:20',
            'post' => 'nullable|string|max:255',
            'second_mobile_no' => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
            'page_permissions' => 'nullable|array',
        ]);

        $data = $request->except(['password', 'permissions', 'page_permissions']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['permission'] = $request->permissions ? implode(',', $request->permissions) : null;
        $data['page_permissions'] = $request->page_permissions;

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
