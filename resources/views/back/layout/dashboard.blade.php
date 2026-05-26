@extends('back.layout.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold mb-0 text-dark">Dashboard</h2>
        <div>
            <button class="btn btn-primary rounded-pill font-weight-semibold px-4">
                + Pasang Iklan Baru
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase text-1 font-weight-semibold">Kredit</span>
                        <h4 class="mb-0 font-weight-bold text-dark">200</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase text-1 font-weight-semibold">Premier</span>
                        <h4 class="mb-0 font-weight-bold text-dark">0</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase text-1 font-weight-semibold">Featured</span>
                        <h4 class="mb-0 font-weight-bold text-dark">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm rounded-lg mt-2">
        <div class="card-body p-5 text-center">
            <h4 class="font-weight-bold text-dark mb-3">Selamat Datang di Portal Properti</h4>
            <p class="text-muted">Mulai pasang iklan properti Anda untuk mendapatkan calon pembeli yang potensial hari ini.</p>
        </div>
    </div>
@endsection