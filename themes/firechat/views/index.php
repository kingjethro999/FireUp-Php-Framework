<?php $layout = 'layout'; $title = 'Chat Dashboard - FireChat'; ?>

<div class="flex h-[calc(100vh-4rem)]">
    <!-- Sidebar -->
    <div class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
        <div class="p-4">
            <button onclick="createRoom()" class="w-full bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-dark">
                Create New Room
            </button>
        </div>
        
        <!-- Rooms List -->
        <div class="overflow-y-auto h-[calc(100vh-8rem)]">
            <div class="px-4 py-2">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Your Rooms
                </h3>
            </div>
            <div class="mt-2">
                <?php foreach ($rooms as $room): ?>
                    <a href="/chat/<?= $room['id'] ?>" 
                       class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 <?= $currentRoom && $currentRoom['id'] === $room['id'] ? 'bg-gray-50 dark:bg-gray-700' : '' ?>">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                <span class="text-lg font-medium text-gray-600 dark:text-gray-300">
                                    <?= strtoupper(substr($room['name'], 0, 1)) ?>
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <?= htmlspecialchars($room['name']) ?>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <?= $room['member_count'] ?> members
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-white dark:bg-gray-900">
        <?php if ($currentRoom): ?>
            <!-- Chat Header -->
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <span class="text-lg font-medium text-gray-600 dark:text-gray-300">
                                <?= strtoupper(substr($currentRoom['name'], 0, 1)) ?>
                            </span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                            <?= htmlspecialchars($currentRoom['name']) ?>
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <?= $currentRoom['member_count'] ?> members
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                <?php foreach ($messages as $message): ?>
                    <div class="flex <?= $message['sender_id'] === $_SESSION['user_id'] ? 'justify-end' : 'justify-start' ?>">
                        <div class="max-w-xs md:max-w-md px-4 py-2 rounded-lg <?= $message['sender_id'] === $_SESSION['user_id'] ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' ?>">
                            <p class="text-sm"><?= htmlspecialchars($message['message']) ?></p>
                            <p class="text-xs mt-1 <?= $message['sender_id'] === $_SESSION['user_id'] ? 'text-primary-100' : 'text-gray-500 dark:text-gray-400' ?>">
                                <?= date('g:i A', strtotime($message['created_at'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Message Input -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
                <form id="message-form" class="flex space-x-4">
                    <input type="text" id="message-input" 
                        class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary sm:text-sm" 
                        placeholder="Type your message...">
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Send
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- Welcome Screen -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Welcome to FireChat</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Select a room from the sidebar or create a new one to start chatting.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// WebSocket connection
const ws = new WebSocket('ws://' + window.location.hostname + ':8080');
const messageForm = document.getElementById('message-form');
const messageInput = document.getElementById('message-input');
const messagesContainer = document.getElementById('messages');

ws.onmessage = function(event) {
    const data = JSON.parse(event.data);
    if (data.type === 'message') {
        appendMessage(data);
    }
};

ws.onopen = function() {
    ws.send(JSON.stringify({
        type: 'join',
        room_id: <?= $currentRoom ? $currentRoom['id'] : 'null' ?>,
        user_id: <?= $_SESSION['user_id'] ?>
    }));
};

messageForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const message = messageInput.value.trim();
    if (message) {
        ws.send(JSON.stringify({
            type: 'message',
            room_id: <?= $currentRoom ? $currentRoom['id'] : 'null' ?>,
            user_id: <?= $_SESSION['user_id'] ?>,
            message: message
        }));
        messageInput.value = '';
    }
});

function appendMessage(data) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${data.sender_id === <?= $_SESSION['user_id'] ?> ? 'justify-end' : 'justify-start' }`;
    messageDiv.innerHTML = `
        <div class="max-w-xs md:max-w-md px-4 py-2 rounded-lg ${data.sender_id === <?= $_SESSION['user_id'] ?> ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white'}">
            <p class="text-sm">${data.message}</p>
            <p class="text-xs mt-1 ${data.sender_id === <?= $_SESSION['user_id'] ?> ? 'text-primary-100' : 'text-gray-500 dark:text-gray-400'}">
                ${new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true })}
            </p>
        </div>
    `;
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function createRoom() {
    // Implement room creation logic
    window.location.href = '/rooms/create';
}
</script> 