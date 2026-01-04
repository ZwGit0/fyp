<div class="max-w-2xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Customer Support</h1>
    <br>
    <div class="mb-4">
        <label for="receiverId" class="block text-sm font-medium text-gray-700">Chat with:</label>
        <select wire:model.change="receiverId" id="receiverId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name ?? 'User ' . $user->id }}</option>
            @endforeach
        </select>
    </div>
    <div wire:poll.3s class="border p-4 overflow-y-auto mb-4 relative" style="max-height: 31rem; overflow-y: auto;">
        @foreach ($messages as $msg)
            @php
                // Get current admin's ID
                $currentAdminId = Auth::guard('admin')->user()->id ?? null;

                // Check if sender exists
                $sender = $msg->sender ?? null;
            @endphp
            <div class="{{ $msg->sender_type === \App\Models\Admin::class && $msg->sender_id == $currentAdminId ? 'text-right' : 'text-left' }} mb-2">
                <p class="inline-block {{ $msg->sender_type === \App\Models\Admin::class && $msg->sender_id == $currentAdminId ? 'bg-blue-100' : 'bg-gray-200' }} rounded p-2">
                    {{ $sender ? ($sender->name ?? ($msg->sender_type === \App\Models\Admin::class ? 'Admin ' . $sender->id : 'User ' . $sender->id)) : 'Guest' }}: {{ $msg->message }}
                </p>
                <small class="block text-gray-500">{{ $msg->created_at->diffForHumans() }}</small>
            </div>
        @endforeach
    </div>
    <form wire:submit.prevent="sendMessage" class="grid grid-cols-[1fr_auto] gap-0">
        <input wire:model="message" type="text" class="flex-1 border p-2 rounded-l" placeholder="Type your message...">
        <button type="submit" class="bg-blue-500 text-white p-2 rounded-r">Send</button>
    </form>
</div>