@extends('errors.layout')

@section('code', '429')
@section('icon', 'fas fa-gauge-high')
@section('title', 'Terlalu Banyak Permintaan')
@section('description')
    Anda telah mengirimkan terlalu banyak permintaan dalam waktu singkat.
    Mohon tunggu sebentar sebelum mencoba kembali.
@endsection
