<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\User;
use App\Support\SitrepCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    // fix 2026-08-22: SHA1 hash gate dipindah ke password.ini (di-gitignore).
    // Sumber: App\Support\SitrepCredentials::manageUserSha1()
    public function gate(): View
    {
        return view('input.users.gate');
    }

    public function verifyGate(Request $request)
    {
        $request->validate([
            'manage_password' => ['required', 'string', 'regex:/^[a-f0-9]{40}$/i'],
        ], [
            'manage_password.regex' => 'Password Manage User harus berupa SHA1 hash (40 karakter hex).',
        ]);

        $submitted = strtolower(trim($request->input('manage_password')));
        if (!hash_equals(SitrepCredentials::manageUserSha1(), $submitted)) {
            return back()->withErrors(['manage_password' => 'SHA1 hash tidak cocok.'])->withInput();
        }
        $request->session()->put('manage_user_unlocked', true);
        return redirect()->route('users.index');
    }

    public function index(Request $request): View
    {
        if (!$request->session()->get('manage_user_unlocked')) {
            return redirect()->route('users.gate');
        }
        $users = User::orderBy('id')->get(['id', 'username', 'name', 'email', 'created_at']);
        $apiKeys = ApiKey::orderBy('id', 'desc')->get();
        return view('input.users.index', compact('users', 'apiKeys'));
    }

    public function create(Request $request): View
    {
        if (!$request->session()->get('manage_user_unlocked')) {
            return redirect()->route('users.gate');
        }
        return view('input.users.create');
    }

    public function store(Request $request)
    {
        if (!$request->session()->get('manage_user_unlocked')) {
            return redirect()->route('users.gate');
        }
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:64', 'unique:users,username'],
            'name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:6|confirmed',
        ]);
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);
        User::create($data);
        return redirect()->route('users.index')->with('status', "User '{$data['username']}' berhasil ditambahkan.");
    }

    public function destroy(Request $request, User $user)
    {
        if (!$request->session()->get('manage_user_unlocked')) {
            return redirect()->route('users.gate');
        }
        if (Auth::id() === $user->id) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus user yang sedang login.']);
        }
        $username = $user->username;
        $user->delete();
        return redirect()->route('users.index')->with('status', "User '{$username}' berhasil dihapus.");
    }

    public function lock(Request $request)
    {
        $request->session()->forget('manage_user_unlocked');
        return redirect()->route('input.index')->with('status', 'Manage User dikunci kembali.');
    }

    public function storeApiKey(Request $request)
    {
        if (!$request->session()->get('manage_user_unlocked')) {
            return redirect()->route('users.gate');
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
        ]);
        $user = $request->user();
        $userId = $user ? (int) $user->getKey() : null;
        $result = ApiKey::generate($data['name'], $userId);
        $request->session()->flash('new_api_key', [
            'name' => $data['name'],
            'plain' => $result['plain'],
        ]);
        return redirect()->route('users.index')->with('status', "API key '{$data['name']} berhasil dibuat. SALIN SEKARANG — key hanya ditampilkan sekali.");
    }

    public function destroyApiKey(Request $request, ApiKey $apiKey)
    {
        if (!$request->session()->get('manage_user_unlocked')) {
            return redirect()->route('users.gate');
        }
        $name = $apiKey->name;
        $apiKey->delete();
        return redirect()->route('users.index')->with('status', "API key '{$name}' berhasil dihapus.");
    }
}
