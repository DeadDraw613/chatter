// resources/js/chat.js
document.addEventListener('DOMContentLoaded', () => {
    // console.log('🧠 DOMContentLoaded fired for chat.js');

    const sendBtn = document.getElementById('sendBtn');
    const msgInput = document.getElementById('msgInput');
    const messagesDiv = document.getElementById('messages');
    if (!sendBtn || !msgInput || !messagesDiv) return;

    // Image upload buttons
    // const sendBtn = document.getElementById('sendBtn');
    const openImageModalBtn = document.getElementById('openImageModalBtn');
    const postImageBtn = document.getElementById('postImageBtn');
    const cancelImageBtn = document.getElementById('cancelModal');
    const imageFileInput = document.getElementById('imageFile');

    const token = document.querySelector('meta[name="csrf-token"]').content;

    const receiverId = window.otherUserId;
    const currentUserId = window.currentUserId;
    
    sendBtn.addEventListener('click', sendMessage);
    msgInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

    // Image Modal EVENT LISTENERS (each button gets a handler)
    if (sendBtn) sendBtn.addEventListener('click', sendMessage);
    if (openImageModalBtn) openImageModalBtn.addEventListener('click', openImageModal);
    if (cancelImageBtn) cancelImageBtn.addEventListener('click', closeImageModal);
    if (postImageBtn) postImageBtn.addEventListener('click', uploadImage);

    let lastMessageId = 0;
    let pollingTimer = null;

    // Append new messages only
    function appendMessages(messages) {
        // console.error('  🧠🧠 appendMessages Data', messages);
        messages.forEach(m => {
            const div = document.createElement('div');
            div.className = `flex ${m.sender_id === currentUserId ? 'justify-end' : 'justify-start'}`;
            
            let innerHTML = '';
            if (m.image_path) {
                //image bubble
// ------>
                innerHTML = `
                    <div class="px-3 py-2 rounded-lg max-w-xs ${
                        m.sender_id === currentUserId ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-white'
                    }">
                        <img src="${m.image_path}" class="chat-thumbnail cursor-pointer" style="max-height:150px; border-radius:6px;" />
                    </div>
                `;                
            } else {
                //text bubble
                innerHTML = `
                    <div class="px-3 py-2 rounded-lg max-w-xs ${
                        m.sender_id === currentUserId ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-white'
                    }">
                        ${m.message}
                    </div>
                `;
            }
            div.innerHTML = innerHTML;
            messagesDiv.appendChild(div);
        });

        // scroll to bottom
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        // Add click listeners for lightbox
        document.querySelectorAll('.chat-thumbnail').forEach(img => {
            img.addEventListener('click', () => {
                openLightbox(img.src);
            });
        });
    }

    //----- Image Upload -------------------------------------------------------- 
    async function uploadImage() {
        const fileInput = document.getElementById('imageInput');
        const file = fileInput.files[0];

        // console.log('DEBUG 1: Selected file:', file);

        if (!file) {
            alert("Please select a file.");
            return;
        }

        // console.log('DEBUG: Receiver ID being sent:', window.otherUserId);

        // 1️⃣ Create FormData and append the file
        const formData = new FormData();
        formData.append('image', file);
        formData.append('receiver_id', window.otherUserId);

        
        // 3️⃣ Get the CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        try {
            // 4️⃣ Send the POST request
            const res = await fetch('/api/messages/image-upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                body: formData,
                credentials: 'include', // send session cookie for Breeze auth
            });

            // 5️⃣ Safe JSON parse
            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error("Failed to parse JSON from server:", text);
                return;
            }

            console.log("STATUS: Upload response:", data.success); //<-------
            // STATUS: Upload response: undefined

            if (data.success === true) {
                console.log("✅ Upload succeeded, waiting for poller to display image.");
                fileInput.value = '';
                return; // ✅ DO NOT append a bubble manually
            }

        } catch (err) {
            console.error("Network/server error:", err);
            alert("Upload error. See console.");
        }
    }



    // Fetch new messages since lastMessageId
    async function fetchMessages() {
        //First "Chat bubble" loading happening before here
        try {
            const res = await fetch(`/api/messages/${receiverId}`, { credentials: 'include' });
            if (!res.ok) throw new Error('Error fetching messages');
            const data = await res.json();

            // Filter only new messages
            const newMessages = data.filter(m => m.id > lastMessageId);
            // console.error('🧠🧠🧠 fetchMessages Data', newMessages);
            if (newMessages.length > 0) {
                appendMessages(newMessages);
                lastMessageId = Math.max(...data.map(m => m.id));
            }
        } catch (err) {
            console.error('Fetch error', err);
        }
    }

    // Send message
    async function sendMessage() {
        const text = msgInput.value.trim();
        if (!text) return;

        try {
            await fetch(`/api/messages`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                credentials: 'include',
                body: JSON.stringify({ receiver_id: receiverId, message: text })
            });

            msgInput.value = '';
            fetchMessages(); // fetch immediately after sending
        } catch (err) {
            console.error('Send message error', err);
        }
    }

    function openLightbox(src) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        lightboxImg.src = src;
        lightbox.style.display = 'flex';
    }

    // Close lightbox when clicked
    document.getElementById('lightbox').addEventListener('click', e => {
        if (e.target.id === 'lightbox' || e.target.id === 'lightbox-img') {
            e.target.id === 'lightbox-img' ? document.getElementById('lightbox').style.display = 'none' : e.target.style.display = 'none';
        }
    });

    // Initial load
    // Immediately Invoked Function Expression (IIFE)
    // the async IIFE immediately executes, fetches data from an API, and logs 
    // it to the console, all while maintaining a clear, sequential flow thanks to 'await'.
    // The final pair of "()" in this statement "(async () => {...})();" is what makes it an IIFE
    (async () => {
        try {
            const res = await fetch(`/api/messages/${receiverId}`, { credentials: 'include' });
            const data = await res.json();
            console.log('Fetched data:', data);
            appendMessages(data);
            if (data.length > 0) lastMessageId = Math.max(...data.map(m => m.id));
            // Start polling
            pollingTimer = setInterval(fetchMessages, 3000);
        } catch (err) {
            console.error('Initial fetch error', err);
        }
    })();


    
});

