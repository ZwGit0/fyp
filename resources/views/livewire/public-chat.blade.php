@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection

@auth
    <div class="mt-20"> 
        <div class="max-w-2xl mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Customer Support Chat</h1>
            <br>
            <div class="mb-4">
                <label for="receiverId" class="block text-sm font-medium text-gray-700">Chat with:</label>
                <select wire:model.change="receiverId" id="receiverId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm py-3 px-4 text-base">
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name ?? 'Admin ' . $admin->id }}</option>
                    @endforeach
                </select>
            </div>
            <div wire:poll.3s class="border p-4 h-96 overflow-y-auto mb-4">
                @forelse ($messages as $msg)
                    @php
                        $isAdminSender = $msg->sender instanceof \App\Models\Admin;
                    @endphp
                    <div class="{{ $isAdminSender ? 'text-left' : 'text-right' }} mb-2">
                        <p class="inline-block {{ $isAdminSender ? 'bg-gray-200' : 'bg-blue-100' }} rounded p-2">
                            {{ $msg->sender ? ($msg->sender->name ?? ($isAdminSender ? 'Admin ' . $msg->sender->id : 'User ' . $msg->sender->id)) : 'Guest' }}:
                            {{ $msg->message }}
                        </p>
                        <small class="block text-gray-500">{{ $msg->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">No messages yet. Start the conversation!</p>
                @endforelse
            </div>
            <form wire:submit.prevent="sendMessage" class="flex">
                <input wire:model="message" id="message" name="message" type="text" class="flex-1 border p-2 rounded-l" placeholder="Type your message..." autocomplete="off">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-r">Send</button>
            </form>
        </div>
    </div>
@endauth