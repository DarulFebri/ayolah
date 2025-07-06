@extends('layouts.mahasiswa')

@section('title', 'Data Mahasiswa')
@section('page_title', 'Data Mahasiswa')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        #image-to-crop {
            max-width: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="main-card custom-form-card">
        @if (session('success'))
            <div class="alert alert-success success-animation">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger error-animation">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form" id="profile-form">
            @csrf
            @method('POST')
            <input type="hidden" name="cropped_image" id="cropped_image">

            <div class="form-grid">
                <div class="form-group">
                    <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required class="form-input @error('nama') is-invalid @enderror" readonly>
                    @error('nama')
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
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required class="form-input @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="prodi"><i class="fas fa-graduation-cap"></i> Program Studi</label>
                    <input type="text" id="prodi" name="prodi" value="{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}" class="form-input" readonly>
                </div>

                <div class="form-group">
                    <label for="nomor_hp"><i class="fas fa-phone"></i> Nomor HP</label>
                    <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp', $mahasiswa->nomor_hp) }}" class="form-input @error('nomor_hp') is-invalid @enderror">
                    @error('nomor_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto_profil"><i class="fas fa-image"></i> Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil" class="form-input @error('foto_profil') is-invalid @enderror" accept="image/*">
                    @error('foto_profil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <img id="image-preview" src="{{ $mahasiswa->foto_profil ? asset('storage/' . $mahasiswa->foto_profil) : '' }}" alt="Image Preview" class="mt-2" style="max-width: 150px; {{ $mahasiswa->foto_profil ? '' : 'display:none;' }}">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary action-btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary action-btn-save">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- The Modal -->
    <div id="cropModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Potong Gambar</h2>
            <div>
                <img id="image-to-crop" src="">
            </div>
            <br>
                                <br>
                                <button id="crop-button" class="btn btn-primary">Potong dan Simpan</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        const modal = document.getElementById('cropModal');
        const image = document.getElementById('image-to-crop');
        const cropButton = document.getElementById('crop-button');
        const fileInput = document.getElementById('foto_profil');
        const imagePreview = document.getElementById('image-preview');
        const croppedImageInput = document.getElementById('cropped_image');
        const form = document.getElementById('profile-form');
        let cropper;

        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    modal.style.display = 'block';
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        preview: '.preview'
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        cropButton.addEventListener('click', function() {
            const canvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200,
            });

            canvas.toBlob(function(blob) {
                const reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    const base64data = reader.result;
                    croppedImageInput.value = base64data;
                    imagePreview.src = base64data;
                    imagePreview.style.display = 'block';
                    modal.style.display = 'none';
                    cropper.destroy();
                }
            });
        });

        document.querySelector('.close').onclick = function() {
            modal.style.display = 'none';
            cropper.destroy();
            fileInput.value = ''; // Reset file input
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                cropper.destroy();
                fileInput.value = ''; // Reset file input
            }
        }
    </script>
@endpush
