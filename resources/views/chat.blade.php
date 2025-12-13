<x-app-layout>

    <!-- The Image Upload Modal -->
    <form id="imageUploadForm" enctype="multipart/form-data">
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
        <!--    <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Upload Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> -->
                <div class="modal-body">
                    <!-- Title -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Select Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Image file input -->    
                    <div class="modal-body">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="form-control" onchange="previewImage(event)">
                        <div class="mt-3">
                            <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px; display: none;">
                        </div>
                    </div>
                </div>

                <!-- Modal Buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="postImageBtn" class="btn btn-primary" data-bs-dismiss="modal">Post Image</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

 
    <div class="flex h-[91vh]">

    <!-- Sidebar -->
        <aside class="w-68 bg-gray-800 border-r border-gray-700 flex flex-col">
            <ul id="sidebar-users" class="flex-1 overflow-y-auto">
                {{-- Connections will be injected here --}}
            </ul>
        </aside>

       <!-- Hidden form field to store the image path -->
        <input type="hidden" id="imagePath" name="image_path">


    <!-- Chat area -->
        <section class="flex-1 flex flex-col min-w-0">
            @if($otherUser)
                <!-- Header -->
                <div class="border-b flex items-center text-xl font-semibold text-gray-300">


                    <!-- Text container -->
                    <div class="p-4">
                        Chat with {{ $otherUser->name }}
                    </div>
                </div>

                <!-- Scrollable message area -->
                <div id="messages"
                     class="flex-1 overflow-y-auto space-y-2 bg-gray-900 p-3 rounded-md">
                    <p class="text-gray-500">Loading messages...</p>
                </div>

                <!-- Input bar -->
                <div class="p-4 flex space-x-2 border-t bg-gray-800">
                    <!-- </button> -->
                    <input id="msgInput"
                        type="text"
                        placeholder="Type your message..."
                        class="flex-grow rounded-lg border-gray-600 bg-gray-700 text-gray-100 focus:ring focus:ring-indigo-200 min-w-0" />
                    <button id="sendBtn"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        📩
                    </button>
                    <!-- Button to trigger encryption modal -->
                    <button type="button" id="encryptBtn" class="btn btn-primary  bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition" data-bs-toggle="modal" data-bs-target="#myModal">
                    🔒
                    </button>   
                    <!-- Button to trigger the image uplaod modal -->
                    <button type="button" id="imgUploadBtn" class="btn btn-primary  bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition" data-bs-toggle="modal" data-bs-target="#myModal">
                    📁
                    </button>                    
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-500">
                    Select a user from the sidebar to start chatting.
                </div>
            @endif
        </section>
    </div>

    <div id="lightbox" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:1000;">
        <img id="lightbox-img" src="" style="max-width:90%; max-height:90%; border-radius:8px;" />
    </div>

    @if($otherUser)
        <script>
            window.otherUserId = {{ $otherUser->id }};
            window.currentUserId = {{ auth()->id() }};
        </script>
    @endif

    
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const sidebar = document.getElementById('sidebar-users');
        const authId = {{ auth()->id() }};

        // ------------------------------------------------------------
        // 📌 LOAD CONNECTIONS INTO SIDEBAR
        // ------------------------------------------------------------
        async function loadConnections() {
            try {
                const res = await fetch('/api/connections', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!res.ok) throw new Error('Failed to load connections');

                const connections = await res.json();
                sidebar.innerHTML = '';

                // Order for visual grouping
                const statusOrder = { requested: 1, active: 2, refused: 3, deactivated: 4 };
                connections.sort((a, b) => statusOrder[a.status] - statusOrder[b.status]);


                connections.forEach(conn => {

                    // Skip connections the user removed
                    if (
                        (conn.user_a_id === authId && conn.removed_by_a) ||
                        (conn.user_b_id === authId && conn.removed_by_b)
                    ) {
                        return;
                    }

                    const other = conn.user_a_id === authId ? conn.user_b : conn.user_a;
                    if (!other) return;

                    // Helper: render profile image
                    const profileImg = other.profile_picture
                        ? `<img src="/uploads/profile/${other.profile_picture}" class="w-9 h-16 rounded-lg object-cover border border-gray-400 mr-3">`
                        : `<img src="/uploads/profile/default-profile.jpg" class="w-9 h-16 rounded-lg object-cover border border-gray-400 mr-3">`;

                    let liHtml = '';

                    if (conn.status === 'requested') {

                        if (conn.requester_id === authId) {
                            // Outgoing request — waiting
                            liHtml = `
                            <li>
                                <a href="#" class="flex items-start p-3 text-lg bg-yellow-900/30 hover:bg-yellow-900/40 text-yellow-200 cursor-not-allowed">
                                    ${profileImg}
                                    <div class="flex flex-col">
                                        <span>${other.name}</span>
                                        <span class="text-xs" style="color:#d4b34b">${other.email}</span>
                                        <p class="text-xs italic text-yellow-400 mt-1">Waiting for confirmation...</p>
                                    </div>
                                </a>
                            </li>`;
                        } else {
                            // Incoming request
                            liHtml = `
                            <li class="p-3 hover:bg-gray-700">
                                <div class="flex items-start">
                                    ${profileImg}
                                    <div class="flex flex-col flex-1">
                                        <span class="text-white font-medium">${other.name}</span>
                                        <span class="text-xs text-gray-400">${other.email}</span>
                                        <div class="mt-2 flex gap-2">
                                            <button type="button"
                                                class="px-2 py-1 bg-green-700 text-xs rounded hover:bg-green-600 text-white"
                                                onclick="respondToRequest(${conn.id}, 'accept')">Accept</button>
                                            <button type="button"
                                                class="px-2 py-1 bg-red-700 text-xs rounded hover:bg-red-600 text-white"
                                                onclick="respondToRequest(${conn.id}, 'refuse')">Refuse</button>
                                        </div>
                                    </div>
                                </div>
                            </li>`;
                        }

                    } else if (conn.status === 'active') {

                        liHtml = `
                        <li>
                            <a href="/chat/${other.id}" class="flex items-start p-3 hover:bg-gray-700 text-white">
                                ${profileImg}
                                <div class="flex flex-col">
                                    <span class="text-lg font-medium">${other.name}</span>
                                    <span class="text-xs text-gray-300">${other.email}</span>
                                </div>
                            </a>
                        </li>`;

                    } else if (conn.status === 'refused' || conn.status === 'deactivated') {

                        liHtml = `
                        <li>
                            <a href="#" class="flex items-start p-3 text-lg bg-red-900/30 hover:bg-red-900/40 text-red-300 cursor-not-allowed">
                                ${profileImg}
                                <div class="flex flex-col">
                                    <span>${other.name}</span>
                                    <span class="text-xs" style="color:#e57373">${other.email}</span>
                                    <p class="text-xs italic text-red-400 mt-1">Connection removed</p>
                                </div>
                            </a>
                        </li>`;
                    }

                    sidebar.insertAdjacentHTML('beforeend', liHtml);
                });
                

                // connections.forEach(conn => {

                //     // Skip connections the user removed
                //     if (
                //         (conn.user_a_id === authId && conn.removed_by_a) ||
                //         (conn.user_b_id === authId && conn.removed_by_b)
                //     ) {
                //         return;
                //     }

                //     const other = conn.user_a_id === authId ? conn.user_b : conn.user_a;
                //     if (!other) return;

                //     const profileImg = other.profile_picture_url
                //     ? `<img src="${other.profile_picture_url}" class="w-8 h-8 rounded-full object-cover border border-gray-400 mr-2">`
                //     : `<img src="/uploads/profile/default-profile.jpg" class="w-8 h-8 rounded-full object-cover border border-gray-400 mr-2">`;

                //     let liHtml = '';

                //     if (conn.status === 'requested') {

                //         if (conn.requester_id === authId) {
                //             // Outgoing request — waiting
                //             liHtml = `
                //             <li>
                //                 ${profileImg}
                //                 <a href="#" class="block p-3 text-lg bg-yellow-900/30 hover:bg-yellow-900/40 text-yellow-200 cursor-not-allowed">
                //                     ${other.name}<br>
                //                     <span class="text-xs" style="color:#d4b34b">${other.email}</span>
                //                     <p class="text-xs italic text-yellow-400 mt-1">Waiting for confirmation...</p>
                //                 </a>
                //             </li>`;
                //         } else {
                //             // Incoming request
                //             liHtml = `
                //             <li class="p-3 hover:bg-gray-700">
                //                 <div class="flex flex-col">
                //                 ${profileImg}
                //                     <span class="text-white font-medium">${other.name}</span>
                //                     <span class="text-xs text-gray-400">${other.email}</span>
                //                     <div class="mt-2 flex gap-2">
                //                         <button type="button"
                //                             class="px-2 py-1 bg-green-700 text-xs rounded hover:bg-green-600 text-white"
                //                             onclick="respondToRequest(${conn.id}, 'accept')">Accept</button>
                //                         <button type="button"
                //                             class="px-2 py-1 bg-red-700 text-xs rounded hover:bg-red-600 text-white"
                //                             onclick="respondToRequest(${conn.id}, 'refuse')">Refuse</button>
                //                     </div>
                //                 </div>
                //             </li>`;
                //         }

                //     } else if (conn.status === 'active') {

                //         liHtml = `
                //         <li>
                //         ${profileImg}
                //             <a href="/chat/${other.id}" class="block p-3 hover:bg-gray-700 text-lg text-white">
                //                 ${other.name}<br>
                //                 <span class="text-xs" style="color:#4d769d">${other.email}</span>
                //             </a>
                //         </li>`;

                //     } else if (conn.status === 'refused' || conn.status === 'deactivated') {

                //         liHtml = `${profileImg}
                //         <li>
                //             <a href="#" class="block p-3 text-lg bg-red-900/30 hover:bg-red-900/40 text-red-300 cursor-not-allowed">
                //                 ${other.name}<br>
                //                 <span class="text-xs" style="color:#e57373">${other.email}</span>
                //                 <p class="text-xs italic text-red-400 mt-1">Connection removed</p>
                //             </a>
                //         </li>`;
                //     }

                //     sidebar.insertAdjacentHTML('beforeend', liHtml);
                // });

            } catch (err) {
                console.error(err);
            }
        }

        // ------------------------------------------------------------
        // 📌 RESPOND TO INCOMING REQUESTS
        // ------------------------------------------------------------
        window.respondToRequest = async function (id, action) {
            try {
                const res = await fetch(`/api/connections/${id}/respond`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ action })
                });

                const data = await res.json();
                alert(data.message);
                loadConnections(); // refresh sidebar

            } catch (err) {
                console.error(err);
                alert('Error responding to request.');
            }
        };

        // ------------------------------------------------------------
        // 📌 IMAGE PREVIEW
        // ------------------------------------------------------------
        window.previewImage = function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                const preview = document.getElementById('imagePreview');
                preview.src = reader.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        };

        // ------------------------------------------------------------
        // 📌 INITIAL LOAD
        // ------------------------------------------------------------
        loadConnections();

    });
    </script>
   

</x-app-layout>
