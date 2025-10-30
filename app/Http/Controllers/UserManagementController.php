<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(): View
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = User::availableRoles();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('status', 'Compte créé avec succès.');
    }

    public function edit(User $user): View
    {
        $roles = User::availableRoles();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validatedData($request, $user->id, false);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('status', 'Compte mis à jour.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('status', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('status', 'Compte supprimé.');
    }

    private function validatedData(Request $request, ?int $userId = null, bool $requirePassword = true): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'role' => ['required', 'in:' . implode(',', array_keys(User::availableRoles()))],
            'phone' => ['nullable', 'string', 'max:30'],
            'title' => ['nullable', 'string', 'max:255'],
            'assigned_zone' => ['nullable', 'string', 'max:255'],
        ];

        if ($requirePassword || $request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $request->validate($rules);
    }
}
