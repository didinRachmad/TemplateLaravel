<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Produksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $produksiList = Produksi::all();
        return view('profile.edit', [
            'user' => $request->user(),
            'produksiList' => $produksiList,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Jika email berubah, atur kembali verifikasi email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Update field contact (karena field produksi sudah tidak ada di tabel users)
        $user->contact = $request->input('contact');
        $user->save();

        // Update relasi many-to-many produksi
        // Pastikan input produksi_id berupa array ID produksi
        $produksiIds = $request->input('produksi_id');
        if ($produksiIds) {
            $user->produksis()->sync($produksiIds);
        } else {
            // Jika tidak ada produksi yang dipilih, detach semua relasi
            $user->produksis()->detach();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
