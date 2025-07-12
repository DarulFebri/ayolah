@extends('layouts.dosen_base')

@section('title', 'Edit Profil Dosen')
@section('page_title', 'Edit Profil Dosen')

@section('content')
    <div class="main-card">
        <h2 class="form-title"><i class="fas fa-user-edit"></i> Edit Profil Dosen</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('dosen.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST') {{-- Use POST for form submission, but Laravel will interpret it as PUT/PATCH due to @method('PUT') --}}

            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_lengkap"><i class="fas fa-user"></i> Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $dosen->nama) }}" required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nidn"><i class="fas fa-id-badge"></i> NIDN</label>
                    <input type="text" id="nidn" name="nidn" class="form-input @error('nidn') is-invalid @enderror" value="{{ old('nidn', $dosen->nidn) }}">
                    @error('nidn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', $dosen->user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nomor_hp"><i class="fas fa-phone"></i> Nomor HP</label>
                    <input type="text" id="nomor_hp" name="nomor_hp" class="form-input @error('nomor_hp') is-invalid @enderror" value="{{ old('nomor_hp', $dosen->nomor_hp) }}">
                    @error('nomor_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto_profil"><i class="fas fa-image"></i> Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil" class="form-input @error('foto_profil') is-invalid @enderror">
                    @error('foto_profil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($dosen->foto_profil)
                        <div style="margin-top: 10px;" id="currentProfilePicContainer">
                            <img src="{{ asset('storage/' . $dosen->foto_profil) }}" alt="Foto Profil Saat Ini" style="max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                            <p style="font-size: 0.85em; color: #666; margin-top: 5px;">Foto profil saat ini</p>
                        </div>
                    @endif
                    <div style="margin-top: 10px; display: none;" id="croppedImagePreviewContainer">
                        <p style="font-size: 0.85em; color: #666; margin-bottom: 5px;">Preview Foto Profil Baru:</p>
                        <img id="croppedImagePreview" src="" alt="Preview Foto Profil Baru" style="max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
    <div class="cropper-modal-backdrop" id="cropperModalBackdrop">
        <div class="cropper-modal-content">
            <div class="img-container">
                <img id="imageToCrop" src="" alt="Image to crop">
            </div>
            <div class="cropper-modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelCropBtn"><i class="fas fa-times"></i> Batal</button>
                <button type="button" class="btn btn-primary" id="cropImageBtn"><i class="fas fa-crop-alt"></i> Potong Gambar</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        const fotoProfilInput = document.getElementById('foto_profil');
        const cropperModalBackdrop = document.getElementById('cropperModalBackdrop');
        const imageToCrop = document.getElementById('imageToCrop');
        const cancelCropBtn = document.getElementById('cancelCropBtn');
        const cropImageBtn = document.getElementById('cropImageBtn');
        const croppedImagePreviewContainer = document.getElementById('croppedImagePreviewContainer');
        const croppedImagePreview = document.getElementById('croppedImagePreview');
        const currentProfilePicContainer = document.getElementById('currentProfilePicContainer');
        let cropper;

        fotoProfilInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                const reader = new FileReader();

                reader.onload = (event) => {
                    imageToCrop.src = event.target.result;
                    cropperModalBackdrop.classList.add('show');

                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1, // For square profile pictures
                        viewMode: 1, // Restrict the crop box to not exceed the canvas
                        autoCropArea: 0.8, // 80% of the image
                        // Add more options as needed
                    });

                    // Hide current profile pic and previous cropped preview when new file is selected
                    if (currentProfilePicContainer) {
                        currentProfilePicContainer.style.display = 'none';
                    }
                    croppedImagePreviewContainer.style.display = 'none';
                    croppedImagePreview.src = '';
                };
                reader.readAsDataURL(file);
            }
        });

        cancelCropBtn.addEventListener('click', () => {
            cropperModalBackdrop.classList.remove('show');
            if (cropper) {
                cropper.destroy();
            }
            fotoProfilInput.value = ''; // Clear the file input

            // Show current profile pic if it exists, hide cropped preview
            if (currentProfilePicContainer) {
                currentProfilePicContainer.style.display = 'block';
            }
            croppedImagePreviewContainer.style.display = 'none';
            croppedImagePreview.src = '';
        });

        cropImageBtn.addEventListener('click', () => {
            if (cropper) {
                const croppedCanvas = cropper.getCroppedCanvas({
                    width: 250, // Desired width for the cropped image
                    height: 250, // Desired height for the cropped image
                });

                croppedCanvas.toBlob((blob) => {
                    // Create a new File object from the blob
                    const croppedFile = new File([blob], "cropped_profile.png", { type: "image/png" });

                    // Create a DataTransfer object to simulate file input change
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    fotoProfilInput.files = dataTransfer.files;

                    // Display the cropped image preview
                    croppedImagePreview.src = URL.createObjectURL(blob);
                    croppedImagePreviewContainer.style.display = 'block';

                    cropperModalBackdrop.classList.remove('show');
                    if (cropper) {
                        cropper.destroy();
                    }
                }, 'image/png'); // Specify image format
            }
        });
    </script>
@endsection
