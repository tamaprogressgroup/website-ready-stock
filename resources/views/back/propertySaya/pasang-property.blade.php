@extends('back.layout.app')



@section('content')



<style>

    /* Styling Dasar & Utilities */

    .bg-light-blue { background-color: #f8fbff; }

    .cursor-pointer { cursor: pointer; }



    /* Widget Sidebar Tahapan */

    .sidebar-widget { background: #fff; border-radius: 8px; padding: 25px 0; }

    .sidebar-title { font-size: 14px; font-weight: 800; color: #212529; padding: 0 25px 15px; text-transform: uppercase; letter-spacing: 0.5px; }

    .step-list { list-style: none; padding: 0; margin: 0; }

    .step-list li { padding: 14px 25px; font-size: 14px; font-weight: 600; color: #6c757d; display: flex; align-items: center; position: relative; cursor: pointer; transition: all 0.2s; }

    .step-list li:hover { background-color: #f8f9fa; }

    .step-list li.active { background-color: #f4f8ff; color: #1a56db; }

    .step-list li.active::after { content: ''; position: absolute; right: 0; top: 0; height: 100%; width: 4px; background-color: #1a56db; }

    .step-number { width: 26px; height: 26px; border-radius: 50%; background: #f3f4f6; color: #4b5563; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 12px; font-weight: bold; transition: all 0.2s; }

    .step-list li.active .step-number { background: #1a56db; color: #fff; }



    .btn-simpan { background-color: #f3f4f6; color: #9ca3af; font-weight: 600; border-radius: 6px; }



    /* Custom Radio Box */

    .radio-card input[type="radio"] { display: none; }

    .radio-card label { border: 1px solid #ced4da; border-radius: 6px; padding: 8px 16px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; color: #4b5563; font-size: 13px; transition: all 0.2s; background-color: #fff; margin-bottom: 0; }

    .radio-card input[type="radio"]:checked+label { border-color: #0d6efd; background-color: #f4f8ff; color: #0d6efd; font-weight: 600; }

    .radio-card label i { color: #8fa0b5; font-size: 16px; }

    .radio-card input[type="radio"]:checked+label i { color: #0d6efd; }



    /* Badge & Tag Input */

    .badge-wajib { background-color: #e6f7f0; color: #00a651; font-size: 10px; padding: 4px 8px; border-radius: 4px; font-weight: 600; margin-left: 6px; }

    .tag-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }

    .custom-tag { background-color: #e5e7eb; color: #4b5563; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; }

    .custom-tag.tag-highlight { background-color: #ef4444; color: #fff; font-weight: 600; }

    .custom-tag .remove-tag { cursor: pointer; font-size: 11px; opacity: 0.7; transition: opacity 0.2s; }

    .custom-tag .remove-tag:hover { opacity: 1; }



    /* Dynamic Inputs Styling */

    .dynamic-input-group .form-control { border-radius: 4px; border: 1px solid #ced4da; }

    .dynamic-input-group .form-control:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); z-index: 1; }

    .btn-remove-row { border-radius: 4px; border: 1px solid #ced4da; color: #dc3545; background: #fff; }

    .btn-remove-row:hover { background-color: #dc3545; color: #fff; border-color: #dc3545; }

    .btn-add-dynamic { background-color: #f8f9fa; border: 1px solid #ced4da; color: #212529; font-weight: 600; font-size: 13px; padding: 8px 16px; transition: all 0.2s; border-radius: 4px; }

    .btn-add-dynamic:hover { background-color: #e2e6ea; }



    /* Upload Box Styling */

    .upload-box-cover { border: 2px dashed #0d6efd; background-color: #f4f8ff; border-radius: 8px; text-align: center; padding: 40px 20px; transition: all 0.3s ease; }

    .upload-box-cover:hover { background-color: #e9f0ff; }

    .upload-box-bulk { border: 2px dashed #ced4da; background-color: #f8f9fa; border-radius: 8px; text-align: center; padding: 50px 20px; transition: all 0.3s ease; }

    .upload-box-bulk:hover { border-color: #6c757d; background-color: #e9ecef; }



    /* Wizard Step Transition */

    .wizard-step { animation: fadeIn 0.4s ease-in-out; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

</style>



<div class="container-fluid py-4">

    <div class="row">



        <div class="col-lg-3 pe-lg-4 mb-4">

            <div class="sidebar-widget border border-light shadow-sm mb-4">

                <div class="sidebar-title">Tahapan Pasang Iklan</div>

                <ul class="step-list" id="wizard-sidebar">

                    <li class="active" data-step="1"><span class="step-number">1</span> Kategori</li>

                    <li data-step="2"><span class="step-number">2</span> Spesifikasi & Fasilitas</li>

                    <li data-step="3"><span class="step-number">3</span> Harga & Media</li>

                </ul>

            </div>

        </div>



        <div class="col-lg-9">

            @if ($errors->any())

                <div class="alert alert-danger shadow-sm border-0 mb-4 rounded">

                    <div class="font-weight-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Gagal menyimpan iklan. Mohon periksa kembali:</div>

                    <ul class="mb-0 text-2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            <form action="{{ route('properti.store') }}" method="POST" enctype="multipart/form-data">

                @csrf



                <div id="step-content-1" class="wizard-step bg-white p-4 p-lg-5 rounded-lg shadow-sm border border-light">

                    <h3 class="font-weight-bold text-dark mb-4 border-bottom pb-3">1. Kategori Properti</h3>



                    <div class="mb-4">

                        <label class="form-label text-dark font-weight-semibold mb-3">Tipe Properti <span class="badge-wajib">Wajib</span></label>

                        <div class="d-flex flex-wrap gap-2">

                            @foreach ($property_type as $key => $type)

                            <div class="radio-card">

                                <input type="radio" name="tipe_properti" value="{{ $type['property_type_id'] ?? $key }}" id="tipe_properti_{{$key}}" {{ old('tipe_properti', $loop->first ? ($type['property_type_id'] ?? $key) : '') == ($type['property_type_id'] ?? $key) ? 'checked' : '' }}>

                                <label for="tipe_properti_{{$key}}"><i class="{{ $type['icon_class'] }}"></i> {{ $type['translations'][0]['type_name'] }}</label>

                            </div>

                            @endforeach

                        </div>

                    </div>



                    <div class="mb-5">

                        <label class="form-label text-dark font-weight-semibold mb-3">Kategori <span class="badge-wajib">Wajib</span></label>

                        <div class="d-flex flex-wrap gap-2">

                            @foreach($propertyConditions as $key => $condition)

                            <div class="radio-card">

                                <input type="radio" name="property_condition" id="property_condition_{{ $key }}" value="{{ $condition['property_condition_id'] }}" {{ old('property_condition', $loop->first ? $condition['property_condition_id'] : '') == $condition['property_condition_id'] ? 'checked' : '' }}>

                                <label for="property_condition_{{ $key }}">

                                    <i class="{{ $condition['icon_class'] }}"></i> {{ $condition['translations'][0]['condition_name'] }}

                                </label>

                            </div>

                            @endforeach

                        </div>

                    </div>



                    <div class="d-flex justify-content-end mt-5 pt-3 border-top">

                        <button type="button" class="btn btn-primary px-4 py-2 font-weight-semibold btn-next" data-next="2">

                            Selanjutnya <i class="fas fa-arrow-right ms-2"></i>

                        </button>

                    </div>

                </div>



                <div id="step-content-2" class="wizard-step d-none bg-white p-4 p-lg-5 rounded-lg shadow-sm border border-light">

                    <h3 class="font-weight-bold text-dark mb-4 border-bottom pb-3">2. Spesifikasi & Fasilitas</h3>



                    <div class="mb-4">

                        <label class="form-label text-dark font-weight-semibold">Judul Properti <span class="badge-wajib">Wajib</span></label>

                        <input type="text" class="form-control py-2" name="title" value="{{ old('title') }}" placeholder="Contoh: Rumah Modern 2 Lantai di Serpong Siap Huni">

                    </div>



                    <div class="mb-4">

                        <label class="form-label text-dark font-weight-semibold">Deskripsi Properti <span class="badge-wajib">Wajib</span></label>

                        <textarea class="form-control" name="description" rows="5" placeholder="Ceritakan detail menarik tentang properti ini...">{{ old('description') }}</textarea>

                    </div>



                    <div class="mb-5 p-4 rounded bg-light border border-light">

                        <h5 class="font-weight-bold text-dark mb-2">Tag & Label Iklan</h5>

                        <p class="text-muted text-2 mb-3">Gunakan tag untuk menonjolkan fitur unik iklanmu (misal: Extended Area, Rumah Sudut).</p>



                        <div class="input-group mb-2">

                            <input type="text" class="form-control" id="tag-input" placeholder="Ketik label lalu tekan Enter">

                            <button class="btn btn-primary" type="button" id="btn-add-tag"><i class="fas fa-plus me-1"></i> Tambah</button>

                        </div>



                        <div class="form-check mb-3">

                            <input class="form-check-input" type="checkbox" id="tag-is-highlight">

                            <label class="form-check-label text-danger font-weight-semibold text-2" for="tag-is-highlight">

                                Jadikan sebagai Highlight Merah (Cth: DISKON, HOT ITEM)

                            </label>

                        </div>



                        <div class="tag-container" id="tag-container">

                            @if(is_array(old('highlight_tags')))

                                @foreach(old('highlight_tags') as $hTag)

                                    <span class="custom-tag tag-highlight">{{ $hTag }} <i class="fas fa-times ms-1 remove-tag"></i><input type="hidden" name="highlight_tags[]" value="{{ $hTag }}"></span>

                                @endforeach

                            @endif



                            @if(is_array(old('normal_tags')))

                                @foreach(old('normal_tags') as $nTag)

                                    <span class="custom-tag">{{ $nTag }} <i class="fas fa-times ms-1 remove-tag"></i><input type="hidden" name="normal_tags[]" value="{{ $nTag }}"></span>

                                @endforeach

                            @endif

                            

                            @if(!old('highlight_tags') && !old('normal_tags'))

                                <span class="custom-tag tag-highlight">DISKON <i class="fas fa-times ms-1 remove-tag"></i><input type="hidden" name="highlight_tags[]" value="DISKON"></span>

                                <span class="custom-tag">Extended Building Area <i class="fas fa-times ms-1 remove-tag"></i><input type="hidden" name="normal_tags[]" value="Extended Building Area"></span>

                            @endif

                        </div>

                    </div>



                    <h5 class="font-weight-bold text-dark mb-3 mt-4">Dimensi Utama</h5>

                    <div class="row mb-5">

                        <div class="col-md-3 mb-3"><label class="form-label text-dark">Kamar Tidur</label><input type="number" class="form-control" name="bedrooms" value="{{ old('bedrooms') }}" placeholder="0"></div>

                        <div class="col-md-3 mb-3"><label class="form-label text-dark">Kamar Mandi</label><input type="number" class="form-control" name="bathrooms" value="{{ old('bathrooms') }}" placeholder="0"></div>

                        <div class="col-md-3 mb-3"><label class="form-label text-dark">Luas Tanah</label>

                            <div class="input-group"><input type="number" class="form-control" name="land_area" value="{{ old('land_area') }}" placeholder="0"><span class="input-group-text">m²</span></div>

                        </div>

                        <div class="col-md-3 mb-3"><label class="form-label text-dark">Luas Bangunan</label>

                            <div class="input-group"><input type="number" class="form-control" name="building_area" value="{{ old('building_area') }}" placeholder="0"><span class="input-group-text">m²</span></div>

                        </div>

                    </div>



                    <div class="row mb-5">

                        <div class="col-md-12 mb-5">

                            <h5 class="font-weight-bold text-dark mb-2">Spesifikasi Detail <span class="text-muted font-weight-normal text-1 ms-2">(Opsional)</span></h5>

                            <div class="d-flex gap-2 mb-2 px-1">

                                <div class="w-50 text-muted text-1 font-weight-bold text-uppercase">Nama Spesifikasi</div>

                                <div class="w-50 text-muted text-1 font-weight-bold text-uppercase">Nilai / Keterangan</div>

                                <div style="width: 42px;"></div>

                            </div>

                            <div id="container-spesifikasi">

                                @php 

                                    $oldSpecKeys = old('spec_keys', ['']); 

                                    $oldSpecValues = old('spec_values', ['']); 

                                @endphp

                                @foreach($oldSpecKeys as $index => $oldKey)

                                <div class="d-flex mb-2 dynamic-input-group dynamic-row gap-2">

                                    <input type="text" class="form-control py-2 w-50" name="spec_keys[]" value="{{ $oldKey }}" placeholder="Misal: Carport">

                                    <input type="text" class="form-control py-2 w-50" name="spec_values[]" value="{{ $oldSpecValues[$index] ?? '' }}" placeholder="Misal: 1 Mobil">

                                    <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>

                                </div>

                                @endforeach

                            </div>

                            <button type="button" class="btn btn-add-dynamic mt-2" id="btn-add-spesifikasi"><i class="fas fa-plus me-1"></i> Tambah Spesifikasi</button>

                        </div>



                        <div class="col-md-12 mb-5">

                            <h5 class="font-weight-bold text-dark mb-2">Fasilitas Properti</h5>

                            <div id="container-fasilitas">

                                @php $oldFasilitas = old('fasilitas', ['']); @endphp

                                @foreach($oldFasilitas as $fas)

                                <div class="d-flex mb-2 dynamic-input-group gap-2">

                                    <input type="text" class="form-control py-2 w-100" name="fasilitas[]" value="{{ $fas }}" placeholder="Contoh: Kolam Renang">

                                    <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>

                                </div>

                                @endforeach

                            </div>

                            <button type="button" class="btn btn-add-dynamic mt-2" id="btn-add-fasilitas"><i class="fas fa-plus me-1"></i> Tambah Fasilitas</button>

                        </div>

                    </div>



                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">

                        <button type="button" class="btn btn-light border px-4 py-2 font-weight-semibold btn-prev" data-prev="1">

                            <i class="fas fa-arrow-left me-2"></i> Kembali

                        </button>

                        <button type="button" class="btn btn-primary px-4 py-2 font-weight-semibold btn-next" data-next="3">

                            Selanjutnya <i class="fas fa-arrow-right ms-2"></i>

                        </button>

                    </div>

                </div>



                <div id="step-content-3" class="wizard-step d-none bg-white p-4 p-lg-5 rounded-lg shadow-sm border border-light">

                    <h3 class="font-weight-bold text-dark mb-4 border-bottom pb-3">3. Harga & Media</h3>



                    <div class="row mb-5">

                        <div class="col-md-6 mb-3">

                            <label class="form-label text-dark font-weight-semibold">Harga Properti <span class="badge-wajib">Wajib</span></label>

                            <div class="input-group">

                                <span class="input-group-text bg-light">Rp</span>

                                <input type="text" class="form-control py-2" name="price" value="{{ old('price') }}" placeholder="Misal: 1.100.000.000">

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label text-dark font-weight-semibold">Diskon</label>

                            <div class="input-group">

                                <span class="input-group-text bg-light">Rp</span>

                                <input type="text" class="form-control py-2" name="discount" value="{{ old('discount') }}" placeholder="Misal: 1.100.000.000">

                            </div>

                        </div>

                    </div>



                    <h5 class="font-weight-bold text-dark mb-4 border-bottom pb-2">Media & Foto Properti</h5>



                    <div class="row mb-4">

                        <div class="col-md-6 mb-4">

                            <label class="form-label text-dark font-weight-semibold">Main Thumbnail (Utama) <span class="badge-wajib">Wajib</span></label>

                            <p class="text-muted text-1 mb-2">Muncul paling besar di hasil pencarian.</p>

                            <label for="upload-main" class="w-100 cursor-pointer m-0">

                                <div class="upload-box-cover py-4" id="preview-container-main">

                                    <div class="upload-placeholder">

                                        <i class="fas fa-image fs-2 text-primary mb-2"></i>

                                        <h6 class="font-weight-bold text-dark mb-1">Upload Main Cover</h6>

                                        <span class="text-primary text-1 font-weight-semibold">Klik untuk pilih file</span>

                                    </div>

                                    <img src="" class="img-fluid d-none rounded" style="max-height: 200px;" id="img-preview-main">

                                </div>

                            </label>

                            <input type="file" id="upload-main" name="main_thumbnail" class="d-none preview-input" data-target="main" accept="image/*">

                        </div>



                        <div class="col-md-6 mb-4">

                            <label class="form-label text-dark font-weight-semibold">Mini Thumbnail <span class="badge-wajib">Wajib</span></label>

                            <p class="text-muted text-1 mb-2">Digunakan untuk preview kecil di dashboard.</p>

                            <label for="upload-mini" class="w-100 cursor-pointer m-0">

                                <div class="upload-box-cover py-4 border-secondary" id="preview-container-mini" style="background-color: #fcfcfc;">

                                    <div class="upload-placeholder">

                                        <i class="fas fa-th fs-2 text-secondary mb-2"></i>

                                        <h6 class="font-weight-bold text-dark mb-1">Upload Mini Photo</h6>

                                        <span class="text-secondary text-1 font-weight-semibold">Klik untuk pilih file</span>

                                    </div>

                                    <img src="" class="img-fluid d-none rounded" style="max-height: 200px;" id="img-preview-mini">

                                </div>

                            </label>

                            <input type="file" id="upload-mini" name="mini_thumbnail" class="d-none preview-input" data-target="mini" accept="image/*">

                        </div>

                    </div>



                    <div class="mb-4">

                        <label class="form-label text-dark font-weight-semibold">Foto Interior & Ruangan</label>

                        <div id="container-interior-gallery">

                            <div class="row mb-3 dynamic-row border-bottom pb-3 align-items-center">

                                <div class="col-md-2">

                                    <div class="border rounded bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; overflow: hidden;">

                                        <i class="fas fa-camera text-muted gallery-icon"></i>

                                        <img src="" class="img-cover d-none gallery-preview" style="width: 100%; height: 100%; object-fit: cover;">

                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <input type="file" name="interior_images[]" class="form-control-file gallery-input" accept="image/*">

                                </div>

                                <div class="col-md-4">

                                    <select class="form-control" name="interior_labels[]">

                                        <option value="">-- Pilih Area Interior --</option>

                                        <option value="Ruang Tamu">Ruang Tamu</option>

                                        <option value="Kamar Tidur">Kamar Tidur</option>

                                        <option value="Dapur">Dapur</option>

                                        <option value="Lainnya">Lainnya</option>

                                    </select>

                                </div>

                                <div class="col-md-2">

                                    <button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>

                                </div>

                            </div>

                        </div>

                        <button type="button" class="btn btn-add-dynamic mt-2 w-100" id="btn-add-interior">

                            <i class="fas fa-plus-circle me-1"></i> Tambah Foto Ruangan Lainnya

                        </button>

                    </div>



                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">

                        <button type="button" class="btn btn-light border px-4 py-2 font-weight-semibold btn-prev" data-prev="2">

                            <i class="fas fa-arrow-left me-2"></i> Kembali

                        </button>

                        <button type="submit" class="btn btn-success px-5 py-2 font-weight-bold shadow-sm" style="font-size: 15px;">

                            <i class="fas fa-paper-plane me-2"></i> Terbitkan Iklan

                        </button>

                    </div>

                </div>



            </form>

        </div>

    </div>

</div>



<script>

    document.addEventListener('DOMContentLoaded', function() {



        // --- 1. WIZARD NAVIGATION LOGIC ---

        const sidebarItems = document.querySelectorAll('#wizard-sidebar li');

        const stepContents = document.querySelectorAll('.wizard-step');

        const nextBtns = document.querySelectorAll('.btn-next');

        const prevBtns = document.querySelectorAll('.btn-prev');



        // Jika ada error validasi, otomatis scroll ke pesan error

        @if ($errors->any())

            window.scrollTo({ top: 0, behavior: 'smooth' });

        @endif



        function goToStep(stepNum) {

            sidebarItems.forEach(item => item.classList.remove('active'));

            stepContents.forEach(content => content.classList.add('d-none'));



            document.querySelector(`#wizard-sidebar li[data-step="${stepNum}"]`).classList.add('active');

            document.getElementById(`step-content-${stepNum}`).classList.remove('d-none');



            window.scrollTo({ top: 0, behavior: 'smooth' });

        }



        sidebarItems.forEach(item => { item.addEventListener('click', function() { goToStep(this.dataset.step); }); });

        nextBtns.forEach(btn => { btn.addEventListener('click', function() { goToStep(this.dataset.next); }); });

        prevBtns.forEach(btn => { btn.addEventListener('click', function() { goToStep(this.dataset.prev); }); });



        // --- 2. TAG INPUT LOGIC ---

        const tagInput = document.getElementById('tag-input');

        const btnAddTag = document.getElementById('btn-add-tag');

        const tagContainer = document.getElementById('tag-container');

        const highlightCheck = document.getElementById('tag-is-highlight');



        function addTag() {

            const val = tagInput.value.trim();

            if (!val) return;



            const isHighlight = highlightCheck.checked;

            const span = document.createElement('span');

            span.className = `custom-tag ${isHighlight ? 'tag-highlight' : ''}`;

            span.innerHTML = `${val} <i class="fas fa-times ms-1 remove-tag"></i>`;



            const hidden = document.createElement('input');

            hidden.type = 'hidden';

            hidden.name = isHighlight ? 'highlight_tags[]' : 'normal_tags[]';

            hidden.value = val;

            span.appendChild(hidden);



            tagContainer.appendChild(span);

            tagInput.value = '';

            highlightCheck.checked = false;

        }



        btnAddTag.addEventListener('click', addTag);

        tagInput.addEventListener('keypress', function(e) {

            if (e.key === 'Enter') { e.preventDefault(); addTag(); }

        });

        tagContainer.addEventListener('click', function(e) {

            if (e.target.classList.contains('remove-tag')) { e.target.closest('.custom-tag').remove(); }

        });



        // --- 3. DYNAMIC INPUTS LOGIC ---

        const containerSpec = document.getElementById('container-spesifikasi');

        document.getElementById('btn-add-spesifikasi').addEventListener('click', function() {

            const row = document.createElement('div');

            row.className = 'd-flex mb-2 dynamic-input-group dynamic-row gap-2';

            row.innerHTML = `

                <input type="text" class="form-control py-2 w-50" name="spec_keys[]" placeholder="Misal: Dinding">

                <input type="text" class="form-control py-2 w-50" name="spec_values[]" placeholder="Misal: Bata Plester Aci">

                <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>

            `;

            containerSpec.appendChild(row);

            row.querySelector('input').focus();

        });



        const containerFasilitas = document.getElementById('container-fasilitas');

        document.getElementById('btn-add-fasilitas').addEventListener('click', function() {

            const row = document.createElement('div');

            row.className = 'd-flex mb-2 dynamic-input-group dynamic-row gap-2';

            row.innerHTML = `

                <input type="text" class="form-control py-2 w-100" name="fasilitas[]" placeholder="Contoh: Smart Home System">

                <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>

            `;

            containerFasilitas.appendChild(row);

            row.querySelector('input').focus();

        });



        document.body.addEventListener('click', function(e) {

            const deleteBtn = e.target.closest('.btn-delete');

            if (deleteBtn) {

                const container = deleteBtn.closest('[id^="container-"]');

                const rows = container.querySelectorAll('.dynamic-row');

                if (rows.length > 1) { deleteBtn.closest('.dynamic-row').remove(); } 

                else {

                    const inputs = deleteBtn.closest('.dynamic-row').querySelectorAll('input');

                    inputs.forEach(input => input.value = '');

                }

            }

        });

    });



    // --- 4. IMAGE PREVIEW LOGIC ---

    document.body.addEventListener('change', function(e) {

        if (e.target.classList.contains('preview-input')) {

            const target = e.target.dataset.target;

            const file = e.target.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function(event) {

                    const imgTag = document.getElementById(`img-preview-${target}`);

                    const placeholder = document.querySelector(`#preview-container-${target} .upload-placeholder`);

                    imgTag.src = event.target.result;

                    imgTag.classList.remove('d-none');

                    placeholder.classList.add('d-none');

                }

                reader.readAsDataURL(file);

            }

        }



        if (e.target.classList.contains('gallery-input')) {

            const file = e.target.files[0];

            const row = e.target.closest('.dynamic-row');

            const imgPreview = row.querySelector('.gallery-preview');

            const icon = row.querySelector('.gallery-icon');

            if (file) {

                const reader = new FileReader();

                reader.onload = function(event) {

                    imgPreview.src = event.target.result;

                    imgPreview.classList.remove('d-none');

                    icon.classList.add('d-none');

                }

                reader.readAsDataURL(file);

            }

        }

    });



    const containerInterior = document.getElementById('container-interior-gallery');

    document.getElementById('btn-add-interior').addEventListener('click', function() {

        const newRow = document.createElement('div');

        newRow.className = 'row mb-3 dynamic-row border-bottom pb-3 align-items-center animate__animated animate__fadeIn';

        newRow.innerHTML = `

        <div class="col-md-2">

            <div class="border rounded bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; overflow: hidden;">

                <i class="fas fa-camera text-muted gallery-icon"></i>

                <img src="" class="img-cover d-none gallery-preview" style="width: 100%; height: 100%; object-fit: cover;">

            </div>

        </div>

        <div class="col-md-4">

            <input type="file" name="interior_images[]" class="form-control-file gallery-input" accept="image/*">

        </div>

        <div class="col-md-4">

            <select class="form-control" name="interior_labels[]">

                <option value="">-- Pilih Area Interior --</option>

                <option value="Ruang Tamu">Ruang Tamu</option>

                <option value="Kamar Tidur">Kamar Tidur</option>

                <option value="Dapur">Dapur</option>

                <option value="Lainnya">Lainnya</option>

            </select>

        </div>

        <div class="col-md-2">

            <button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>

        </div>

    `;

        containerInterior.appendChild(newRow);

    });

</script>



@endsection