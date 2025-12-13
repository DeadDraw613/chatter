<section>
    <header>
        <h2 class="text-lg font-medium text-gray-100">
            {{ __('Chat Connections') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Manage your chat connections, send new requests, or remove existing ones.") }}
        </p>
    </header>

    {{-- Add Connection Form --}}
    <form id="add-connection-form" class="mt-6 space-y-6" onsubmit="event.preventDefault(); sendConnectionRequest();">
        <div>
            <x-input-label for="connection_email" :value="__('Add Connection by Email')" />
            <div class="flex items-center gap-2 mt-1">
                <x-text-input id="connection_email" name="connection_email" type="email"
                    class="block w-full" placeholder="friend@example.com" required />
                <x-primary-button type="submit">
                    {{ __('Send Request') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    {{-- Connections List --}}
    <div class="mt-10">
        <h3 class="text-md font-semibold text-gray-100 mb-2">{{ __('Your Connections') }}</h3>
        <ul id="connections-list" class="divide-y divide-gray-700">
            <li class="text-gray-400 italic py-2">Loading connections...</li>
        </ul>
    </div>

    {{-- CSRF Meta --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ------------------------------
    // CONSTANTS
    // ------------------------------

    const list = document.querySelector('#connections-list');
    const userId = {{ auth()->id() }};


    // ------------------------------
    // FUNCTIONS
    // ------------------------------

    // 🧩 Load connections from API
    async function loadConnections() {
        list.innerHTML = `<li class="text-gray-400 italic py-2">Loading connections...</li>`;

        try {
            const res = await fetch('/web/connections', { 
                headers: { 'Accept': 'application/json' } 
            });

            if (!res.ok) throw new Error('Failed to load connections');

            const connections = await res.json();
            list.innerHTML = '';

            if (connections.length === 0) {
                list.innerHTML = `<li class="text-gray-500 italic py-2">No connections yet</li>`;
                return;
            }

            // Sort by status
            const statusOrder = { active: 1, requested: 2, refused: 3, deactivated: 4 };
            connections.sort((a, b) => statusOrder[a.status] - statusOrder[b.status]);

            connections.forEach(conn => {
                const other = (conn.user_a_id === userId) ? conn.user_b : conn.user_a;

                const li = document.createElement('li');
                li.className = "flex items-center justify-between py-2 border-b border-gray-700";

                let content = `
                    <div>
                        <p class="text-gray-200 font-medium">${other.name}</p>
                        <p class="text-xs text-gray-400 capitalize">Status: ${conn.status}</p>
                    </div>
                `;

                // Show/hide "remove from list"
                let showRemoveButton = !(
                    (conn.user_a_id === userId && conn.removed_by_a === userId) ||
                    (conn.user_b_id === userId && conn.removed_by_b === userId)
                );

                if (conn.status === 'active') {
                    content += `
                        <button class="remove-btn px-3 py-1 bg-red-700 text-xs rounded hover:bg-red-600 text-white"
                                data-id="${conn.id}">
                            Remove
                        </button>
                    `;
                } 
                else if (conn.status === 'requested') {
                    content += `<p class="text-xs text-yellow-400">Awaiting confirmation</p>`;
                } 
                else if (conn.status === 'refused') {
                    content += `<p class="text-xs italic text-red-400 mt-1">Connection ended</p>`;

                    const showRemove = !(
                        (conn.user_a_id === userId && conn.removed_by_a === 1) ||
                        (conn.user_b_id === userId && conn.removed_by_b === 1)
                    );

                    if (showRemove) {
                        content += `
                            <button class="remove-from-list-btn px-3 py-1 bg-gray-600 text-xs rounded hover:bg-gray-500 text-white"
                                    data-id="${conn.id}">
                                Remove from list
                            </button>
                        `;
                    }
                } 
                else if (conn.status === 'deactivated') {
                    content += `<p class="text-xs text-gray-500 italic">Connection ended</p>`;

                    const showRemove = !(
                        (conn.user_a_id === userId && conn.removed_by_a === 1) ||
                        (conn.user_b_id === userId && conn.removed_by_b === 1)
                    );

                    if (showRemove) {
                        content += `
                            <button class="remove-from-list-btn px-3 py-1 bg-gray-600 text-xs rounded hover:bg-gray-500 text-white"
                                    data-id="${conn.id}">
                                Remove from list
                            </button>
                        `;
                    }
                }

                li.innerHTML = content;
                list.appendChild(li);
            });

        } catch (error) {
            console.error(error);
            list.innerHTML = `<li class="text-red-400 italic py-2">Error loading connections</li>`;
        }
    }


    // 🧩 Send Connection Request
    async function sendConnectionRequest() {
        const email = document.getElementById('connection_email').value.trim();
        if (!email) return alert('Please enter an email.');

        try {
            const response = await fetch('/api/connections/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (response.status === 201) {
                alert('✅ Connection request sent successfully.');
            } else if (response.status === 404 || data.message?.includes('exists')) {
                alert('❌ Email not found.');
            } else if (response.status === 409) {
                alert('⚠️ ' + data.message);
            } else if (response.status === 400) {
                alert('⚠️ ' + data.message);
            } else {
                alert('❌ Unexpected error: ' + (data.message || 'Unknown error.'));
            }

            // refresh list
            document.getElementById('connection_email').value = '';
            await loadConnections();

        } catch (error) {
            console.error(error);
            alert('Network error. Please try again.');
        }
    }

    // make it callable from HTML form
    window.sendConnectionRequest = sendConnectionRequest;


    // ------------------------------
    // EVENT LISTENERS
    // ------------------------------

    // Remove-from-list (refused/deactivated)
    list.addEventListener('click', async e => {
        if (!e.target.classList.contains('remove-from-list-btn')) return;

        const id = e.target.dataset.id;
        if (!confirm("Remove this connection from your sidebar?")) return;

        try {
            const res = await fetch(`/web/connections/${id}/remove-sidebar`, {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            });

            if (!res.ok) throw new Error("Failed to remove");

            e.target.remove();

        } catch (err) {
            alert("Error removing connection.");
            console.error(err);
        }
    });


    // Deactivate a connection (active → deactivated)
    list.addEventListener('click', async e => {
        if (!e.target.classList.contains('remove-btn')) return;

        const id = e.target.dataset.id;
        if (!confirm('Are you sure you want to remove this connection?')) return;

        try {
            const res = await fetch(`/web/connections/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) throw new Error('Delete failed');

            await loadConnections(); 

        } catch (error) {
            console.error(error);
            alert('Failed to remove connection');
        }
    });


    // ------------------------------
    // INITIAL LOAD
    // ------------------------------
    loadConnections();

});
</script>

  
</section>
