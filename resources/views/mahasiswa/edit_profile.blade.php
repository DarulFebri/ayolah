@extends('layouts.mahasiswa')

@section('title', 'Data Mahasiswa')
@section('page_title', 'Data Mahasiswa')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <style>
        /* Modern Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1050; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
            display: flex; /* Use flexbox for centering */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            animation: fadeIn 0.3s ease-out; /* Fade in animation */
        }
        .modal-dialog { /* Changed from .modal-content to .modal-dialog for consistency with editprofilmahasiswa.blade.php */
            background-color: var(--white);
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); /* Deeper shadow */
            width: 90%; /* Responsive width */
            max-width: 500px; /* Max width */
            animation: fadeIn 0.4s ease-out; /* Slide in animation */
            overflow: hidden; /* Ensure content stays within rounded corners */
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px; /* Adjusted padding */
            border-bottom: 1px solid var(--border-color);
        }
        .modal-header h2 {
            margin: 0;
            font-size: 18px; /* Adjusted font size */
            font-weight: 600; /* Added font weight */
            color: var(--primary-700);
            display: flex;
            align-items: center;
        }
        .modal-header h2 i { /* Icon in modal title */
            margin-right: 10px;
            color: var(--primary-500);
        }

        .close-button { /* Changed from .close to .close-button for consistency */
            background: none;
            border: none;
            font-size: 24px; /* Adjusted font size */
            color: #9ca3af; /* Muted color */
            cursor: pointer;
            transition: color 0.2s;
            font-weight: normal; /* Override bold */
        }
        .close-button:hover,
        .close-button:focus {
            color: var(--danger);
            transform: rotate(90deg);
        }
        #image-to-crop {
            max-width: 100%;
            display: block; /* Ensures image takes full width of its container */
            margin: 0 auto; /* Center the image */
        }
        .cropper-container { /* This is the main area for the cropper image */
            height: 280px; /* Fixed height for the cropper area */
            width: 100%;
            background-color: #f0f0f0; /* Light background for cropper */
            border-radius: 8px;
            margin-bottom: 20px; /* Space below cropper */
        }

        /* Styles for the live preview within the modal */
        .image-preview-container { /* Renamed for clarity */
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .cropper-preview { /* This is the actual preview element */
            width: 50px; /* Fixed size for the preview */
            height: 50px;
            overflow: hidden;
            border-radius: 50%; /* Circular preview */
            border: 2px solid var(--primary-200); /* Border color */
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .cropper-preview-container p { /* Text for preview */
            font-weight: 500;
            color: var(--text-color);
            margin: 0;
            font-size: 14px;
        }

        .modal-controls { /* New section for controls */
            padding: 20px 25px;
            background-color: var(--light-gray);
            border-top: 1px solid #e2e8f0;
        }
        .controls-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping for responsive layout */
            gap: 15px;
        }
        .cropper-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-crop-action { /* Styling for individual crop control buttons */
            background-color: var(--primary-100);
            color: var(--primary-600);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-crop-action:hover {
            background-color: var(--primary-200);
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .modal-footer { /* Footer for action buttons */
            padding: 15px 25px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            background-color: #fdfdfd; /* Slightly different background */
        }

        /* Custom form card styling to match main-card */
        .custom-form-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 30px;
            animation: fadeIn 0.5s 0.2s both;
            border-top: 3px solid var(--primary-500);
        }

        /* New layout for form and profile image */
        .profile-form-layout {
            display: grid;
            grid-template-columns: 2fr 1fr; /* Form takes 2/3, image takes 1/3 */
            gap: 30px; /* Space between columns */
            align-items: flex-start; /* Align items to the top */
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr; /* Single column for inputs within the form section */
            gap: 20px;
            margin-bottom: 0; /* Remove bottom margin as gap handles spacing */
        }

        .form-group {
            margin-bottom: 0; /* Handled by grid gap */
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-700);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2);
        }

        .form-input[readonly] {
            background-color: var(--light-gray);
            cursor: not-allowed;
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 13px;
            margin-top: 5px;
        }

        .profile-image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px; /* Add some top padding */
            /* Removed bottom margin and border as it's now a column in a grid */
        }

        .profile-image-preview {
            width: 150px; /* Slightly larger preview */
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-500);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .profile-image-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .upload-button-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin-top: 10px;
        }

        .upload-button-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        /* Alert styling from mahasiswa.blade.php */
        .alert {
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            animation: fadeIn 0.5s both;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-warning {
            color: #664d03;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        .alert-info { /* Added for consistency with previous updates */
            background-color: #e0f7fa;
            color: #00796b;
            border: 1px solid #b2ebf2;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
        }

        /* Specific animation for alerts */
        .success-animation {
            animation: slideInTop 0.5s ease-out;
        }
        .error-animation {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        @media (max-width: 992px) { /* Adjust breakpoint for layout change */
            .profile-form-layout {
                grid-template-columns: 1fr; /* Stack columns on smaller screens */
            }
            .profile-image-section {
                padding-bottom: 25px;
                border-bottom: 1px dashed var(--border-color);
            }
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-label {
                width: 100%;
                margin-bottom: 5px;
            }
            .form-actions {
                flex-direction: column;
            }
            .modal-dialog { /* Changed from .modal-content */
                padding: 20px;
            }
            .modal-header h2 {
                font-size: 20px;
            }
            .controls-group {
                flex-direction: column; /* Stack controls vertically */
                align-items: flex-start;
            }
            .cropper-preview-container {
                margin-bottom: 15px;
            }
            .cropper-buttons {
                width: 100%;
                justify-content: space-around;
            }
            .btn-crop-action {
                flex-grow: 1; /* Make buttons fill width */
            }
        }
    </style>
@endpush

@section('content')
    <div class="main-card custom-form-card">
        @if (session('success'))
            <div class="alert alert-success success-animation">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger error-animation">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (! $profileEdited)
            <div class="alert alert-warning mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Perhatian:</strong> Halaman ini hanya dapat diubah sekali. Mohon isi data dengan benar dan teliti.
            </div>
        @endif

        @if ($profileEdited)
            <div class="alert alert-info"> {{-- Changed to alert-info for a less aggressive warning --}}
                <i class="fas fa-info-circle mr-2"></i>
                <h2>Profil Anda telah berhasil diperbarui sebelumnya. Data di bawah ini tidak dapat diubah lagi.</h2>
            </div>
        @endif

        <div class="profile-form-layout"> {{-- New wrapper for side-by-side layout --}}
            <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form" id="profile-form">
                @csrf
                @method('POST')
                <input type="hidden" name="cropped_image" id="cropped_image">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_lengkap"><i class="fas fa-user"></i> Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required class="form-input @error('nama_lengkap') is-invalid @enderror" readonly>
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nim"><i class="fas fa-id-card"></i> NIM</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required class="form-input @error('nim') is-invalid @enderror" readonly>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="prodi"><i class="fas fa-graduation-cap"></i> Program Studi</label>
                        <input type="text" id="prodi" name="prodi" value="{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}" class="form-input" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->user->email) }}" required class="form-input @error('email') is-invalid @enderror" {{ $profileEdited ? 'readonly' : '' }}>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nomor_hp"><i class="fas fa-phone"></i> Nomor HP</label>
                        <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp', $mahasiswa->nomor_hp) }}" class="form-input @error('nomor_hp') is-invalid @enderror" {{ $profileEdited ? 'readonly' : '' }}>
                        @error('nomor_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-actions">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if (! $profileEdited)
                        <button type="submit" class="btn btn-primary" id="save-button">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    @endif
                </div>
            </form>

            <div class="profile-image-section">
                <img id="image-preview" src="{{ $mahasiswa->foto_profil ? asset('storage/' . $mahasiswa->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Pengguna') . '&background=1a88ff&color=fff' }}"
                     alt="Foto Profil" class="profile-image-preview">
                @if (! $profileEdited)
                <div class="upload-button-wrapper">
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-upload mr-2"></i> Unggah Foto
                    </button>
                    <input type="file" id="foto_profil" name="foto_profil" accept="image/*">
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- The Modern Cropping Modal -->
    <div id="cropModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2><i class="fas fa-crop-alt"></i> Atur Foto Profil</h2>
                <button type="button" class="close-button">&times;</button>
            </div>
            <div class="modal-body">
                <div class="cropper-container">
                    <img id="image-to-crop" src="">
                </div>
            </div>
            <div class="modal-controls">
                <div class="controls-group">
                    <div class="image-preview-container">
                        <p>Pratinjau:</p>
                        <div class="cropper-preview"></div>
                    </div>
                    <div class="cropper-buttons">
                        <button type="button" class="btn-crop-action" id="zoom-in-button" title="Perbesar"><i class="fas fa-search-plus"></i></button>
                        <button type="button" class="btn-crop-action" id="zoom-out-button" title="Perkecil"><i class="fas fa-search-minus"></i></button>
                        <button type="button" class="btn-crop-action" id="rotate-left-button" title="Putar Kiri"><i class="fas fa-undo"></i></button>
                        <button type="button" class="btn-crop-action" id="rotate-right-button" title="Putar Kanan"><i class="fas fa-redo"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancel-crop-button">Batal</button>
                <button class="btn btn-primary" id="crop-button">Potong dan Simpan</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the crop modal is hidden on page load
            const cropModal = document.getElementById('cropModal');
            if (cropModal) {
                cropModal.style.display = 'none';
            }
        });

        const modal = document.getElementById('cropModal');
        const image = document.getElementById('image-to-crop');
        const cropButton = document.getElementById('crop-button');
        const cancelCropButton = document.getElementById('cancel-crop-button');
        const fileInput = document.getElementById('foto_profil');
        const imagePreview = document.getElementById('image-preview');
        const croppedImageInput = document.getElementById('cropped_image');
        const form = document.getElementById('profile-form');
        const saveButton = document.getElementById('save-button');
        const closeButton = document.querySelector('.close-button');

        // Cropper control buttons
        const zoomInButton = document.getElementById('zoom-in-button');
        const zoomOutButton = document.getElementById('zoom-out-button');
        const rotateLeftButton = document.getElementById('rotate-left-button');
        const rotateRightButton = document.getElementById('rotate-right-button');

        let cropper;

        // Function to show the modal
        function showModal() {
            modal.style.display = 'flex'; // Use flex to center
        }

        // Function to hide the modal
        function hideModal() {
            modal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null; // Clear cropper instance
            }
            fileInput.value = ''; // Reset file input
        }

        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    showModal(); // Show the modal
                    if (cropper) { // Destroy previous cropper instance if exists
                        cropper.destroy();
                    }
                    cropper = new Cropper(image, {
                        aspectRatio: 1, // Square aspect ratio for profile picture
                        viewMode: 1, // Restrict the crop box to not exceed the canvas
                        preview: '.cropper-preview', // Live preview element
                        background: false, // Hide the background grid
                        autoCropArea: 0.8 // Automatically crop 80% of the image
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        cropButton.addEventListener('click', function() {
            if (!cropper) return; // Ensure cropper exists

            const canvas = cropper.getCroppedCanvas({
                width: 250, // Desired width (from editprofilmahasiswa.blade.php)
                height: 250, // Desired height (from editprofilmahasiswa.blade.php)
            });

            canvas.toBlob(function(blob) {
                const reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    const base64data = reader.result;
                    croppedImageInput.value = base64data; // Set hidden input value
                    imagePreview.src = base64data; // Update preview image
                    imagePreview.style.display = 'block'; // Ensure preview is visible
                    hideModal(); // Hide the modal
                }
            }, 'image/png'); // Specify image format
        });

        // Event listener for the new cancel button
        cancelCropButton.addEventListener('click', function() {
            hideModal();
        });

        // Event listener for the new close button (X icon)
        closeButton.addEventListener('click', function() {
            hideModal();
        });

        // Cropper control button event listeners
        zoomInButton.addEventListener('click', () => cropper.zoom(0.1));
        zoomOutButton.addEventListener('click', () => cropper.zoom(-0.1));
        rotateLeftButton.addEventListener('click', () => cropper.rotate(-45));
        rotateRightButton.addEventListener('click', () => cropper.rotate(45));

        // Close modal if clicked outside
        window.onclick = function(event) {
            if (event.target == modal) {
                hideModal();
            }
        }

        // Handle form submission (optional: add loading state to save button)
        form.addEventListener('submit', function() {
            if (saveButton) {
                saveButton.classList.add('loading');
                saveButton.disabled = true;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            }
        });
    </script>
@endpush
