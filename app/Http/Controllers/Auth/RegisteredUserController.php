<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view for the initial admin setup.
     */
    public function create(): View
    {
        abort_if(User::query()->exists(), 403);

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request for the initial admin.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_if(User::query()->exists(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'role' => User::ROLE_ADMIN,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
