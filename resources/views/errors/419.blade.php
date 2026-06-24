@extends('errors.layout')

@section('code', '419')
@section('icon', 'fas fa-clock-rotate-left')
@section('title', 'Sesi Telah Berakhir')
@section('description')
    Sesi Anda telah habis atau token keamanan tidak valid. Silakan muat ulang halaman
    dan coba lagi. Pastikan browser Anda mengizinkan cookies.
@endsection

@section('extra_action')
    <a href="{{ url()->previous() }}" class="btn-outline-err" style="background:#fff8ec; border-color:#F9A61A; color:#c07700;">
        <i class="fas fa-rotate-right"></i> Muat Ulang
    </a>
@endsection
