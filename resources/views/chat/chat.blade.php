@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">

    <!-- Chat Header -->
    <div class="bg-gradient-to-r from-[#3F1A2B] to-[#B2183A] px-6 py-4 flex items-center justify-between">
        <h2 class="text-xl md:text-2xl font-bold text-white flex items-center gap-2">
            icon EasePrint Chat
        </h2>
        <span class="text-sm text-pink-200">Online Support</span>
    </div>

    <!-- Chat Messages -->
    <div id="chatBox" class="p-4 h-96 overflow-y-auto bg-gray-50 space-y-3">
        <!-- Messages + system notes will appear here -->
    </div>

    <!-- Chat Input -->
    <div class="flex border-t border-gray-200">
        <input 
            type="text" 
            id="messageInput" 
            class="flex-1 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#B2183A]"
            placeholder="Type your message..." 
        />
        <button 
            onclick="sendMessage()" 
            class="bg-[#B2183A] hover:bg-[#ED4A69] text-white px-6 py-3 transition font-medium"
        >
            Send
        </button>
    </div>
</div>

<script>
let lastMessageId = null;
let firstLoad = true;
let waitTimeout = null; // For wait note timer

document.addEventListener('DOMContentLoaded', () => {
    if (Notification.permission !== 'granted') {
        Notification.requestPermission();
    }
    fetchMessages();
    setInterval(fetchMessages, 3000);
    markMessagesAsRead();
});

const userId = {{ Auth::id() }};
const adminId = 0;
const apiUrl = `http://127.0.0.1:8000/api`;

function formatTimestamp(dateString) {
    const date = new Date(dateString);
    const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const day = date.toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' });
    return `${day} at ${time}`;
}

async function fetchMessages() {
    const res = await fetch(`${apiUrl}/messages/${userId}`);
    const messages = await res.json();

    const box = document.getElementById('chatBox');
    const isAtBottom = box.scrollTop + box.clientHeight >= box.scrollHeight - 50;

    // Append only new messages (don’t wipe chatBox)
    messages.forEach(msg => {
        if (!document.getElementById(`msg-${msg.id}`)) {
            const isAdmin = msg.is_admin == 1;
            const sender = isAdmin ? 'EasePrint' : 'You';
            const timestamp = formatTimestamp(msg.created_at);

            box.innerHTML += `
                <div id="msg-${msg.id}" class="flex ${isAdmin ? 'justify-start' : 'justify-end'}">
                    <div class="max-w-xs md:max-w-sm">
                        <div class="text-xs text-gray-400 mb-1">${timestamp}</div>
                        <div class="px-4 py-2 rounded-lg shadow ${isAdmin ? 'bg-white border border-gray-200 text-gray-800' : 'bg-[#B2183A] text-white'}">
                            <strong>${sender}:</strong> ${msg.message}
                        </div>
                    </div>
                </div>
            `;
        }
    });

    if (isAtBottom) {
        box.scrollTop = box.scrollHeight;
    }

    // Notifications + Reset wait timer if admin replies
    if (messages.length > 0) {
        const latestMsg = messages[messages.length - 1];
        if (latestMsg.id !== lastMessageId) {
            if (!firstLoad && latestMsg.is_admin == 1) {
                notifyUser(latestMsg.message);
                resetWaitTimer(); // Stop wait timer when admin replies
            }
            lastMessageId = latestMsg.id;
        }
    }
    firstLoad = false;
}

async function sendMessage() {
    const input = document.getElementById('messageInput');
    const msg = input.value.trim();
    if (!msg) return;

    await fetch(`${apiUrl}/messages`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            sender_id: userId,
            receiver_id: adminId,
            message: msg,
            is_admin: false
        })
    });

    input.value = '';
    fetchMessages();

    // Start wait note timer after sending
    startWaitTimer();
}

// Wait note logic
function startWaitTimer() {
    clearTimeout(waitTimeout); 
    waitTimeout = setTimeout(() => {
        showWaitNote();
    }, 15000); // 15 seconds (change to 20000 for 20s)
}

function resetWaitTimer() {
    clearTimeout(waitTimeout);
    // Notes will stay (stack), not cleared
}

function showWaitNote() {
    const box = document.getElementById('chatBox');
    const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    box.innerHTML += `
        <div class="flex justify-center">
            <div class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-xs italic shadow mt-2">
                ${timestamp} - Please wait, EasePrint will reply soon...
            </div>
        </div>
    `;
    box.scrollTop = box.scrollHeight;
}

function notifyUser(message) {
    if (Notification.permission === 'granted') {
        new Notification("icon New message", {
            body: message,
            icon: '/favicon.ico'
        });
    }
}

async function markMessagesAsRead() {
    try {
        await fetch(`${apiUrl}/messages/mark-as-read/${userId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
    } catch (error) {
        console.error('Failed to mark messages as read:', error);
    }
}
</script>
@endsection
