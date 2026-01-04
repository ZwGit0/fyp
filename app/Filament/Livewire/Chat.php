<?php
namespace App\Filament\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use App\Models\Admin; 
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $message = '';
    public $messages = [];
    public $receiverId;
    public $users;

    public function mount()
    {
        // Load all regular users (from the users table)
        $this->users = User::all();
        $this->receiverId = $this->users->first()->id ?? 1; // Default to first user or ID 1
        $this->loadMessages();
    }

    public function updatedReceiverId($value)
    {
        $this->receiverId = $value;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $admin = Auth::guard('admin')->user();
        $adminId = $admin ? $admin->id : 1;

        //polymorphic relationships (sender_type, receiver_type)
        $this->messages = Message::where(function ($query) use ($adminId) {
            $query->where('sender_id', $adminId)
                ->where('sender_type', \App\Models\Admin::class)  // Sender type is Admin
                ->where('receiver_id', $this->receiverId)
                ->where('receiver_type', \App\Models\User::class); // Receiver type is User
        })->orWhere(function ($query) use ($adminId) {
            $query->where('sender_id', $this->receiverId)
                ->where('sender_type', \App\Models\User::class)  // Sender type is User
                ->where('receiver_id', $adminId)
                ->where('receiver_type', \App\Models\Admin::class); // Receiver type is Admin
        })->orderBy('created_at', 'asc')->get();

        // Preload senders for performance (User and Admin senders)
        $senderIds = $this->messages->pluck('sender_id')->unique();
        $users = User::whereIn('id', $senderIds)->get()->keyBy('id');
        $admins = Admin::whereIn('id', $senderIds)->get()->keyBy('id');

        $this->messages->each(function ($msg) use ($users, $admins) {
            if ($msg->sender_type === \App\Models\User::class) {
                $msg->sender = $users->get($msg->sender_id);
            } elseif ($msg->sender_type === \App\Models\Admin::class) {
                $msg->sender = $admins->get($msg->sender_id);
            }
        });
    }

    public function sendMessage()
    {
        if (empty($this->message)) {
            return;
        }
    
        // Get the current admin's ID
        $admin = Auth::guard('admin')->user();
        $adminId = $admin ? $admin->id : 1;
    
        // Assuming the receiver is always a User
        $receiver = User::find($this->receiverId);  // Find the receiver user by ID
    
        // Save the message with the correct sender_type
        Message::create([
            'sender_id' => $adminId,              // Sender ID is the admin's ID
            'sender_type' => \App\Models\Admin::class, // Sender type is Admin
            'receiver_id' => $this->receiverId,    // Receiver ID is the user ID
            'receiver_type' => \App\Models\User::class, // Receiver type is User
            'message' => $this->message,
        ]);
    
        // Clear the message input and reload the messages
        $this->message = '';
        $this->loadMessages();
    }
    
    public function render()
    {
        $this->loadMessages();
        return view('filament.livewire.chat');
    }
}