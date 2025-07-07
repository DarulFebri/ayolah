@component('mail::message')
# Pembaruan Status Pengajuan Anda

Status pengajuan **{{ $jenis_pengajuan }}** dengan judul **{{ $judul_pengajuan }}** Anda telah diperbarui menjadi: **{{ $status }}**.

Terima kasih telah menggunakan layanan kami.

Salam hormat,
{{ config('app.name') }}
@endcomponent
