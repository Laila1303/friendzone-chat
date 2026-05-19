<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Tampilkan halaman chat utama.
     */
    public function index()
    {
        // Untuk tahap ini, kita cukup return view chat terlebih dahulu
        return view('chat');
    }

    /**
     * Tambah percakapan/room chat baru berdasarkan username.
     */
    public function addContact(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $username = strtolower($request->username);

        // 1. Cari user berdasarkan username
        $contact = User::where('username', $username)->first();

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'User dengan username tersebut tidak ditemukan.'
            ], 404);
        }

        $currentUser = Auth::user();

        // 2. Cegah menambah diri sendiri
        if ($contact->id === $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menambah diri sendiri.'
            ], 400);
        }

        // 3. Cek apakah percakapan pribadi (single chat) sudah ada
        $conversation = $currentUser->conversations()
            ->where('is_group', false)
            ->whereHas('users', function ($query) use ($contact) {
                $query->where('users.id', $contact->id);
            })
            ->first();

        // 4. Jika belum ada, buat percakapan baru
        if (!$conversation) {
            $conversation = Conversation::create([
                'is_group' => false,
                'name' => null, // null untuk percakapan single
            ]);

            // Hubungkan kedua user ke percakapan tersebut
            $conversation->users()->attach([$currentUser->id, $contact->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kontak berhasil ditambahkan!',
            'data' => $conversation->load('users')
        ]);
    }
}
