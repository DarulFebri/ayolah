@extends('layouts.admin')

@section('title', 'Detail Mahasiswa')

@section('header_title', 'Detail Mahasiswa')

@section('content')
<div class="welcome-box" style="margin-top: 0;">
    <h2 class="welcome-title" style="margin-bottom: 20px; text-align: center;">Detail Data Mahasiswa</h2>

    <div style="text-align: center; margin-bottom: 30px;">
        @if ($mahasiswa->foto_profil)
            <img id="profileImage" src="{{ asset('storage/' . $mahasiswa->foto_profil) }}" alt="Foto Profil Mahasiswa" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-500); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); cursor: pointer;">
        @else
            <img id="profileImage" src="{{ asset('images/default-profile.png') }}" alt="Foto Profil Default" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-500); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); cursor: pointer;">
        @endif
    </div>

    <!-- The Modal -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <div class="card-body">
        <p class="detail-item"><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p class="detail-item"><strong>Nama Lengkap:</strong> {{ $mahasiswa->nama_lengkap }}</p>
        <p class="detail-item"><strong>Email:</strong> {{ $mahasiswa->user->email ?? '-' }}</p>
        <p class="detail-item"><strong>Prodi:</strong> {{ $mahasiswa->prodi->nama_prodi }}</p>
        <p class="detail-item"><strong>Jenis Kelamin:</strong> {{ $mahasiswa->jenis_kelamin }}</p>
        <p class="detail-item"><strong>Kelas:</strong> {{ $mahasiswa->kelas->nama_kelas ?? '-' }}</p>
    </div>

    <div style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 15px;">
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Data
        </a>

        {{-- Form untuk tombol hapus --}}
        <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            @method('DELETE') {{-- Penting untuk memberitahu Laravel bahwa ini adalah permintaan DELETE --}}
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .detail-item {
        font-size: 1.1rem;
        margin-bottom: 12px;
        padding-bottom: 5px;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }

    .detail-item strong {
        color: var(--primary-600);
        margin-right: 8px;
        min-width: 150px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn i {
        margin-right: 8px;
    }

    .btn-primary {
        background-color: var(--primary-500);
        color: white;
        box-shadow: 0 4px 10px rgba(26, 136, 255, 0.2);
    }

    .btn-primary:hover {
        background-color: var(--primary-600);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(26, 136, 255, 0.3);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-danger {
        background-color: var(--danger); /* Menggunakan variabel CSS --danger dari admin.blade.php */
        color: white;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2); /* Shadow merah */
    }

    .btn-danger:hover {
        background-color: #c82333; /* Warna merah sedikit lebih gelap saat hover */
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.3);
    }

    .welcome-box {
        padding: 40px;
        max-width: 800px;
        margin: 30px auto !important;
    }

    .card-body {
        padding: 20px;
        background-color: var(--light-gray);
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    /* Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: hidden; /* Prevent scroll */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .modal-content, #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {-webkit-transform:scale(0)}
        to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Get the modal
    var modal = document.getElementById("imageModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("profileImage");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal content, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection