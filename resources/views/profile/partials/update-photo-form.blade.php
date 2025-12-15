{{-- Profile Picture Section --}}
    <header>
        <h2 class="text-lg font-medium text-gray-100">
            {{ __('Profile Picture') }}
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            {{ __("Manage your profile photo.") }}
        </p>
    </header>

<div class="mt-8 space-y-4">
    <!-- <x-input-label :value="__('Profile Picture')" /> -->

    <div class="flex items-center gap-6">
        {{-- Profile Image Preview --}}
        <img
            id="profile-picture-preview"
            src="{{ auth()->user()->profile_picture_url }}"
            alt="Profile Picture"
            class="w-36 h-36 rounded-full object-cover border border-gray-300"
        />

        <div class="flex flex-col gap-3">

            {{-- Selected File Name --}}
            <span id="profile-picture-filename" class="text-sm text-gray-500 hidden">
                hh - No image selected
            </span>

            {{-- Hidden File Input --}}
            <input
                id="profile-picture-input"
                type="file"
                accept="image/*"
                class="hidden"
                onchange="previewProfilePicture(event)"
            />

            {{-- Main Action Button --}}
            <button
                id="profile-picture-action-btn"
                type="button"
                class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-700 transition"
                onclick="document.getElementById('profile-picture-input').click();"
            >
                Choose New Image
            </button>

            {{-- Status Message --}}
            <span id="profile-picture-status" class="text-sm"></span>

        </div>
    </div>
</div>


{{-- JS --}}
<script>
let selectedProfileFile = null;

function previewProfilePicture(event) {
    const file = event.target.files[0];
    if (!file) return;

    selectedProfileFile = file;

    // Update filename
    document.getElementById('profile-picture-filename').textContent = file.name;

    // Preview image
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('profile-picture-preview').src = e.target.result;
    };
    reader.readAsDataURL(file);

    // Update action button -> Save mode
    const btn = document.getElementById('profile-picture-action-btn');
    btn.textContent = "Save Profile Image";
    btn.classList.remove("bg-gray-600", "hover:bg-gray-700");
    btn.classList.add("bg-red-600", "hover:bg-red-700");
    btn.onclick = saveProfilePicture;

    // Clear status
    document.getElementById('profile-picture-status').textContent = "";
}


async function saveProfilePicture() {
    if (!selectedProfileFile) return;

    const status = document.getElementById('profile-picture-status');
    const btn = document.getElementById('profile-picture-action-btn');

    status.textContent = "Uploading...";
    status.className = "text-sm text-blue-600";

    const formData = new FormData();
    formData.append('photo', selectedProfileFile);

    try {
        const response = await fetch('/profile/photo', {
            method: 'POST',
            credentials: 'same-origin', // REQUIRED for session cookie
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            status.textContent = data.message || "Upload failed.";
            status.className = "text-sm text-red-600";
            return;
        }

        // Update preview URL to actual stored URL
        document.getElementById('profile-picture-preview').src = data.url;

        // Show success message
        status.textContent = "Profile image saved successfully!";
        status.className = "text-sm text-green-600";

        // Reset button back to "Choose New Image"
        btn.textContent = "Choose New Image";
        btn.classList.remove("bg-red-600", "hover:bg-red-700");
        btn.classList.add("bg-gray-600", "hover:bg-gray-700");
        btn.onclick = () => document.getElementById('profile-picture-input').click();

        // Reset selected file
        selectedProfileFile = null;
        // document.getElementById('profile-picture-filename').textContent = "No image selected";
        document.getElementById('profile-picture-filename').textContent = "hh";

    } catch (error) {
        status.textContent = "Upload error.";
        status.className = "text-sm text-red-600";
        console.error(error);
    }
}
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
