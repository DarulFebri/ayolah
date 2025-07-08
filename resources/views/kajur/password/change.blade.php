@extends('layouts.kajur')

@section('content')
<div class="container">
    <h1>Ganti Password</h1>
    <form action="{{ route('kajur.password.change') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="current_password">Password Saat Ini</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
            @error('current_password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password">Password Baru</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
            @error('new_password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">Ganti Password</button>
    </form>
</div>
@endsection
