<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\Seller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class SellerChat extends Component
{
    public $message = '';
    public $messages = [];
    public $receiverId;
    public $admins;

    public function mount()
    {
        // Load all admins (from the admins table)
        $this->admins = Admin::all(); // Adjust if need specific admins
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
        $seller = Auth::guard('seller')->user();
        $sellerId = $seller ? $seller->id : 1;

        //'Sender' and 'receiver' are morphs and sending messages between Seller and Admin
        $this->messages = Message::where(function ($query) use ($sellerId) {
            $query->where('sender_id', $sellerId)->where('sender_type', Seller::class)
                ->where('receiver_id', $this->receiverId)->where('receiver_type', Admin::class);
        })->orWhere(function ($query) use ($sellerId) {
            $query->where('sender_id', $this->receiverId)->where('sender_type', Admin::class)
                ->where('receiver_id', $sellerId)->where('receiver_type', Seller::class);
        })->orderBy('created_at', 'asc')->get();

        // Preload senders for performance
        $senderIds = $this->messages->pluck('sender_id')->unique();
        $sellers = Seller::whereIn('id', $senderIds)->get()->keyBy('id');
        $admins = Admin::whereIn('id', $senderIds)->get()->keyBy('id');

        $this->messages->each(function ($msg) use ($sellers, $admins) {
            if ($msg->sender_type === Seller::class) {
                $msg->sender = $sellers->get($msg->sender_id);
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

        $seller = Auth::guard('seller')->user();
        $sellerId = $seller ? $seller->id : 1;

        // Here, assume sender is always a Seller, and receiver is always an Admin.
        $receiver = Admin::find($this->receiverId);

        Message::create([
            'sender_id' => $sellerId,
            'sender_type' => Seller::class, // Set sender type as Seller
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
        return view('livewire.seller-chat')->layout('seller.sellerMain');
    }
}