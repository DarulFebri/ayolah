@extends('layouts.kaprodi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Notifikasi Anda</h4>
                </div>
                <div class="card-body">
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

                    @if($notifications->isEmpty())
                        <p>Tidak ada notifikasi.</p>
                    @else
                        <form action="{{ route('kaprodi.notifications.markAllAsRead') }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Tandai Semua Sudah Dibaca</button>
                        </form>

                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read_at ? 'list-group-item-secondary' : '' }}">
                                    <div>
                                        <strong>{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</strong>
                                        <p class="mb-0">{{ $notification->data['message'] ?? 'Tidak ada detail pesan.' }}</p>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    @unless($notification->read_at)
                                        <form action="{{ route('kaprodi.notifications.markAsRead', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm">Tandai Sudah Dibaca</button>
                                        </form>
                                    @endunless
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
