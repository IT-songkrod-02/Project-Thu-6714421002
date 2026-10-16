<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Reel;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /** กล่องข้อความ: รวมบทสนทนาทั้งหมด */
    public function index(Request $request)
    {
        $me = $request->user()->id;

        $messages = Message::with(['reel:id,title', 'sender:id,name', 'receiver:id,name'])
            ->where(fn ($q) => $q->where('sender_id', $me)->orWhere('receiver_id', $me))
            ->latest()->get();

        $conversations = $messages
            ->groupBy(fn ($m) => $m->reel_id . '-' . ($m->sender_id == $me ? $m->receiver_id : $m->sender_id))
            ->map(function ($group) use ($me) {
                $last = $group->first();
                return [
                    'reel' => $last->reel,
                    'other' => $last->sender_id == $me ? $last->receiver : $last->sender,
                    'last' => $last,
                    'unread' => $group->where('receiver_id', $me)->where('is_read', false)->count(),
                ];
            })->values();

        return view('chat.index', compact('conversations'));
    }

    /** ห้องแชท 1-on-1 (ผูกกับสินค้า) */
    public function show(Request $request, Reel $reel, User $user)
    {
        $me = $request->user();
        abort_if($user->id === $me->id, 404);
        abort_unless($reel->user_id === $me->id || $reel->user_id === $user->id, 403);

        Message::where('reel_id', $reel->id)->where('sender_id', $user->id)
            ->where('receiver_id', $me->id)->update(['is_read' => true]);

        $history = Message::where('reel_id', $reel->id)
            ->where(fn ($q) => $q
                ->where(fn ($w) => $w->where('sender_id', $me->id)->where('receiver_id', $user->id))
                ->orWhere(fn ($w) => $w->where('sender_id', $user->id)->where('receiver_id', $me->id)))
            ->orderBy('created_at')->get(['id', 'sender_id', 'receiver_id', 'message_text', 'created_at']);

        // Token สำหรับยืนยันตัวตนกับ Node.js (หมดอายุใน 2 ชั่วโมง)
        $payload = $me->id . '.' . (time() + 7200);
        $token = $payload . '.' . hash_hmac('sha256', $payload, config('services.chat.secret'));

        return view('chat.show', [
            'reel' => $reel->load('brand', 'primaryImage'),
            'other' => $user,
            'history' => $history,
            'token' => $token,
            'chatUrl' => config('services.chat.url'),
        ]);
    }
}