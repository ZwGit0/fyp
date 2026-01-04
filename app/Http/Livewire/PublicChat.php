<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class PublicChat extends Component
{
    public $message = '';
    public $messages = [];
    public $receiverId;
    public $admins;

    public function mount()
    {
        // Load all admins (from the admins table)
        $this->admins = Admin::all(); // Adjust if you need specific admins
        $this->receiverId = $this->admins->first()->id ?? 1; // Default to first admin or ID 1
        $this->loadMessages();
    }

    public function updatedReceiverId($value)
    {
        $this->receiverId = $value;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $user = Auth::guard('web')->user();
        $userId = $user ? $user->id : 1;

        //'Sender' and 'receiver' are morphs and sending messages between User and Admin
        $this->messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)->where('sender_type', User::class)
                ->where('receiver_id', $this->receiverId)->where('receiver_type', Admin::class);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $this->receiverId)->where('sender_type', Admin::class)
                ->where('receiver_id', $userId)->where('receiver_type', User::class);
        })->orderBy('created_at', 'asc')->get();

        // Preload senders for performance
        $senderIds = $this->messages->pluck('sender_id')->unique();
        $users = User::whereIn('id', $senderIds)->get()->keyBy('id');
        $admins = Admin::whereIn('id', $senderIds)->get()->keyBy('id');

        $this->messages->each(function ($msg) use ($users, $admins) {
            if ($msg->sender_type === User::class) {
                $msg->sender = $users->get($msg->sender_id);
            } else {
                $msg->sender = $admins->get($msg->sender_id);
            }
        });
    }

    public function sendMessage()
    {
        if (empty($this->message)) {
            return;
        }

        $user = Auth::guard('web')->user();
        $userId = $user ? $user->id : 1;

        // Here, assume sender is always a User, and receiver is always an Admin.
        $receiver = Admin::find($this->receiverId);

        Message::create([
            'sender_id' => $userId,
            'sender_type' => User::class, // Set sender type as User
            'receiver_id' => $this->receiverId,
            'receiver_type' => Admin::class, // Set receiver type as Admin
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->loadMessages();
    }


    public function render()
    {
        $this->loadMessages();
        return view('livewire.public-chat')->layout('layouts.main');
    }
}