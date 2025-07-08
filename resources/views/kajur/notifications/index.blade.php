@extends('layouts.kajur')

@section('content')
<div class="container">
    <h1>Notifikasi</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($notifications->isEmpty())
        <p>Tidak ada notifikasi.</p>
    @else
        <div class="mb-3">
            <form action="{{ route('kajur.notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">Tandai Semua Sudah Dibaca</button>
            </form>
        </div>
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item {{ $notification->read_at ? 'list-group-item-secondary' : '' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</strong>
                            <p>{{ $notification->data['message'] ?? '' }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @unless($notification->read_at)
                            <form action="{{ route('kajur.notifications.markAsRead', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">Tandai Sudah Dibaca</button>
                            </form>
                        @endunless
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
