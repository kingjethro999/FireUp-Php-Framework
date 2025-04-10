<?php
$room_id = $_GET['id'] ?? null;
$room_name = $_GET['name'] ?? 'General Chat';
?>

<div class="flex h-[calc(100vh-4rem)]">
    <!-- Chat Room Sidebar -->
    <div class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
        <div class="p-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($room_name) ?></h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Room ID: <?= htmlspecialchars($room_id) ?></p>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Online Users</h3>
            <div id="online-users" class="space-y-2">
                <!-- Online users will be populated here -->
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col">
        <!-- Chat Messages -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
            <!-- Messages will be populated here -->
        </div>

        <!-- Message Input -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <form id="message-form" class="flex space-x-4">
                <input type="text" 
                       id="message-input" 
                       class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                       placeholder="Type your message...">
                <button type="submit" 
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary">
                    Send
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const chatMessages = document.getElementById('chat-messages');
    const onlineUsers = document.getElementById('online-users');
    
    // WebSocket connection
    const ws = new WebSocket(`ws://${window.location.host}/ws/chat/room/<?= $room_id ?>`);
    
    ws.onmessage = function(event) {
        const data = JSON.parse(event.data);
        
        if (data.type === 'message') {
            // Add new message to chat
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start space-x-3';
            
            const avatar = document.createElement('div');
            avatar.className = 'flex-shrink-0';
            avatar.innerHTML = `<div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">${data.username[0].toUpperCase()}</div>`;
            
            const content = document.createElement('div');
            content.className = 'flex-1';
            content.innerHTML = `
                <div class="flex items-center space-x-2">
                    <span class="font-medium text-gray-900 dark:text-white">${data.username}</span>
                    <span class="text-sm text-gray-500">${new Date(data.timestamp).toLocaleTimeString()}</span>
                </div>
                <p class="text-gray-700 dark:text-gray-300">${data.message}</p>
            `;
            
            messageElement.appendChild(avatar);
            messageElement.appendChild(content);
            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        } else if (data.type === 'users') {
            // Update online users list
            onlineUsers.innerHTML = data.users.map(user => `
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">${user}</span>
                </div>
            `).join('');
        }
    };
    
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        
        if (message) {
            ws.send(JSON.stringify({
                type: 'message',
                message: message,
                room_id: '<?= $room_id ?>'
            }));
            messageInput.value = '';
        }
    });
});
</script> 