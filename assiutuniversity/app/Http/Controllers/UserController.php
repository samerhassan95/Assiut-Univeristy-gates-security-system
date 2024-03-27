<?php

// UserController.php

namespace App\Http\Controllers;
use App\Http\Controllers\UserController;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }   
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }
        if ($request->filled('role')) {
            $query->where('role', 'like', '%' . $request->input('role') . '%');
        }

        $users = $query->get();
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validation can be added here if needed
        $userData = $request->only(['name', 'email', 'password']); // Fetch user data

        // Create a new user
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']), // Hash the password
        ]);
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        // Validation can be added here if needed
        $user->update($request->all());
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }


    public function toggleRole(User $user)
    {
        \Log::info('Inside toggleRole');
    
        $loggedInUser = auth()->user();
    
        // Log the user before the update
        \Log::info('User before update:', ['user' => $user->toArray(), 'role' => $user->role]);
    
        // Check if the logged-in user is an admin and not the user being updated
        if ($loggedInUser->role === 'admin' && $user->id !== $loggedInUser->id) {
            $newRole = ($user->role === 'user') ? 'admin' : 'user';
            $user->role = $newRole;
            $user->save();
    
            // Log the user after the update
            \Log::info('User after update:', ['user' => $user->fresh()->toArray(), 'newRole' => $newRole]);
        }
    
        return back();
    }
    
    public function approve(User $user)
    {
        $user->update(['approved' => true,'frozen' => false]);

        return back();
    }

    public function freeze(User $user)
    {
        $user->update(['frozen' => true,'approved'=>false]);
    
        return back();
    }  
     
    
    

}
