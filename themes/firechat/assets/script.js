// Typing indicator
let typingTimeout;
const messageInput = document.getElementById('message-input');

if (messageInput) {
    messageInput.addEventListener('input', function() {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;

        ws.send(JSON.stringify({
            type: 'typing',
            room_id: currentRoomId,
            user_id: currentUserId
        }));

        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            ws.send(JSON.stringify({
                type: 'stop_typing',
                room_id: currentRoomId,
                user_id: currentUserId
            }));
        }, 1000);
    });
}

// Message read receipts
function markMessageAsRead(messageId) {
    if (!ws || ws.readyState !== WebSocket.OPEN) return;

    ws.send(JSON.stringify({
        type: 'read_receipt',
        message_id: messageId,
        room_id: currentRoomId,
        user_id: currentUserId
    }));
}

// Scroll to bottom of messages
function scrollToBottom() {
    const messagesContainer = document.getElementById('messages');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

// Handle WebSocket reconnection
function setupWebSocketReconnection() {
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 5;
    const reconnectDelay = 1000;

    function reconnect() {
        if (reconnectAttempts >= maxReconnectAttempts) {
            console.error('Max reconnection attempts reached');
            return;
        }

        reconnectAttempts++;
        console.log(`Attempting to reconnect (${reconnectAttempts}/${maxReconnectAttempts})...`);

        setTimeout(() => {
            if (ws.readyState === WebSocket.CLOSED) {
                ws = new WebSocket('ws://' + window.location.hostname + ':8080');
                setupWebSocketHandlers();
            }
        }, reconnectDelay);
    }

    ws.onclose = function() {
        console.log('WebSocket connection closed');
        reconnect();
    };

    ws.onerror = function(error) {
        console.error('WebSocket error:', error);
    };
}

// Setup WebSocket event handlers
function setupWebSocketHandlers() {
    ws.onopen = function() {
        console.log('WebSocket connected');
        reconnectAttempts = 0;
        
        // Rejoin current room
        if (currentRoomId) {
            ws.send(JSON.stringify({
                type: 'join',
                room_id: currentRoomId,
                user_id: currentUserId
            }));
        }
    };

    ws.onmessage = function(event) {
        const data = JSON.parse(event.data);
        
        switch (data.type) {
            case 'message':
                appendMessage(data);
                break;
            case 'typing':
                showTypingIndicator(data.user_id);
                break;
            case 'stop_typing':
                hideTypingIndicator(data.user_id);
                break;
            case 'read_receipt':
                updateMessageStatus(data.message_id, 'read');
                break;
            case 'user_status':
                updateUserStatus(data.user_id, data.status);
                break;
        }
    };
}

// Initialize WebSocket connection
if (typeof ws !== 'undefined') {
    setupWebSocketHandlers();
    setupWebSocketReconnection();
}

// Handle room creation
function createRoom() {
    const modal = document.getElementById('create-room-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

// Close modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Handle file uploads
function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('room_id', currentRoomId);
    formData.append('user_id', currentUserId);

    fetch('/upload', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            ws.send(JSON.stringify({
                type: 'message',
                room_id: currentRoomId,
                user_id: currentUserId,
                message: data.file_url,
                is_file: true
            }));
        }
    })
    .catch(error => console.error('Upload error:', error));
}

// Handle emoji picker
function toggleEmojiPicker() {
    const picker = document.getElementById('emoji-picker');
    if (picker) {
        picker.classList.toggle('hidden');
    }
}

// Handle message reactions
function addReaction(messageId, reaction) {
    if (!ws || ws.readyState !== WebSocket.OPEN) return;

    ws.send(JSON.stringify({
        type: 'reaction',
        message_id: messageId,
        user_id: currentUserId,
        reaction: reaction
    }));
} 