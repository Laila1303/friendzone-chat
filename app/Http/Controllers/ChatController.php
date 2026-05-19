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
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        // Ambil percakapan yang diikuti oleh user, diurutkan dari yang terbaru
        $conversations = $currentUser->conversations()
            ->with(['users', 'messages' => function ($query) {
                $query->latest();
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeConversation = null;

        // Jika ada chat_id di URL, ambil percakapan aktif tersebut
        if ($request->has('chat_id')) {
            $activeConversation = $currentUser->conversations()
                ->with(['users', 'messages.user'])
                ->where('conversations.id', $request->chat_id)
                ->first();
        }

        return view('chat', compact('conversations', 'activeConversation'));
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

    /**
     * Kirim pesan baru ke percakapan tertentu.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $currentUser = Auth::user();

        // 1. Cek apakah user tergabung di percakapan tersebut
        $conversation = $currentUser->conversations()->where('conversations.id', $request->conversation_id)->first();

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke percakapan ini.'
            ], 403);
        }

        // 2. Buat pesan baru
        $message = $conversation->messages()->create([
            'user_id' => $currentUser->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // 3. Update 'updated_at' percakapan agar naik ke daftar teratas di sidebar
        $conversation->touch();

        // 4. Siarkan event MessageSent secara real-time
        broadcast(new \App\Events\MessageSent($message->load('user')))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim!',
            'data' => [
                'id' => $message->id,
                'message' => $message->message,
                'conversation_id' => $message->conversation_id,
                'user_id' => $message->user_id,
                'created_at' => $message->created_at->timezone('Asia/Jakarta')->format('H.i'),
                'user_name' => $currentUser->name
            ]
        ]);
    }

    /**
     * Buat percakapan grup baru.
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'usernames' => 'required|string',
        ]);

        $currentUser = Auth::user();

        // 1. Ekstrak username menjadi array dan trim spasi
        $usernamesArray = array_map('trim', explode(',', $request->usernames));
        $usernamesArray = array_filter($usernamesArray); // Hapus string kosong

        if (empty($usernamesArray)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan minimal satu username anggota.'
            ], 400);
        }

        // 2. Cari ID user berdasarkan username-username tersebut
        $users = User::whereIn('username', $usernamesArray)->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota dengan username tersebut tidak ditemukan.'
            ], 404);
        }

        // 3. Gabungkan ID anggota kelompok dengan ID pembuat grup (current user)
        $userIds = $users->pluck('id')->push($currentUser->id)->unique()->toArray();

        // 4. Buat Conversation baru tipe Group
        $conversation = Conversation::create([
            'is_group' => true,
            'name' => $request->name,
        ]);

        // 5. Hubungkan semua anggota ke grup tersebut
        $conversation->users()->attach($userIds);

        // 6. Buat pesan sistem pembuka
        $messageText = "Grup \"{$request->name}\" dibuat oleh {$currentUser->name}.";
        $conversation->messages()->create([
            'user_id' => $currentUser->id,
            'message' => $messageText,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Grup berhasil dibuat!',
            'data' => $conversation
        ]);
    }
}
