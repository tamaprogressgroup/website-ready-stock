@php
    $keyPhone = (isset($keyData) && is_array($keyData))
        ? \App\Services\EmbedKeyService::normalizePhone($keyData['no_hp'])
        : '';
@endphp

{{-- ===== WhatsApp Lead Modal ===== --}}
<div id="wa-modal-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:10000;align-items:center;justify-content:center;padding:16px;"
     onclick="if(event.target===this)closeWaModal()">
    <div style="background:#fff;border-radius:16px;padding:28px 24px;max-width:380px;width:100%;box-shadow:0 8px 40px rgba(0,0,0,0.18);">
        <h5 class="text-center poppins-bold" style="color:#3b5998;font-size:18px;margin-bottom:20px;">
            Dapatkan Promo Sekarang
        </h5>

        <div class="mb-3">
            <select id="wa-salutation" class="form-select py-3"
                style="background:#f8f9fa;border:none;border-radius:8px;font-size:14px;">
                <option value="">Title</option>
                <option>Bapak</option>
                <option>Ibu</option>
            </select>
            <div id="wa-err-salutation" style="display:none;color:#dc3545;font-size:12px;margin-top:4px;padding-left:2px;">Silakan pilih title.</div>
        </div>
        <div class="mb-3">
            <input id="wa-nama" type="text" placeholder="Nama" class="form-control py-3"
                style="background:#f8f9fa;border:none;border-radius:8px;font-size:14px;">
            <div id="wa-err-nama" style="display:none;color:#dc3545;font-size:12px;margin-top:4px;padding-left:2px;">Nama wajib diisi.</div>
        </div>
        <div class="mb-3">
            <input id="wa-phone-visitor" type="number" inputmode="numeric" placeholder="No. Telepon" class="form-control py-3"
                style="background:#f8f9fa;border:none;border-radius:8px;font-size:14px;">
            <div id="wa-err-phone" style="display:none;color:#dc3545;font-size:12px;margin-top:4px;padding-left:2px;">No. Telepon wajib diisi.</div>
        </div>
        <div class="mb-4">
            <input id="wa-email" type="email" placeholder="Email" class="form-control py-3"
                style="background:#f8f9fa;border:none;border-radius:8px;font-size:14px;">
            <div id="wa-err-email" style="display:none;color:#dc3545;font-size:12px;margin-top:4px;padding-left:2px;">Email wajib diisi.</div>
        </div>

        <button onclick="submitWaModal()"
            class="btn w-100 poppins-bold d-flex align-items-center justify-content-center"
            style="background:#43CB83;color:#fff;border-radius:8px;padding:14px;font-size:15px;border:none;">
            <i class="fab fa-whatsapp me-2" style="font-size:22px;"></i> WhatsApp
        </button>
        <button onclick="closeWaModal()"
            style="background:none;border:none;color:#aaa;font-size:13px;margin-top:10px;width:100%;cursor:pointer;padding:6px;">
            Tutup
        </button>
    </div>
</div>

<script>
(function () {
    var _waTargetPhone = '';
    var _waPropTitle   = '';
    var _waPropId      = '';
    var _waPropUrl     = '';
    var _waKeyPhone    = '{{ $keyPhone }}';

    var _waFields = [
        { elId: 'wa-salutation',    errId: 'wa-err-salutation', msg: 'Silakan pilih title.' },
        { elId: 'wa-nama',          errId: 'wa-err-nama',       msg: 'Nama wajib diisi.' },
        { elId: 'wa-phone-visitor', errId: 'wa-err-phone',      msg: 'No. Telepon wajib diisi.' },
        { elId: 'wa-email',         errId: 'wa-err-email',      msg: 'Email wajib diisi.' },
    ];

    function setFieldError(f, hasError) {
        var el  = document.getElementById(f.elId);
        var err = document.getElementById(f.errId);
        err.style.display        = hasError ? 'block' : 'none';
        el.style.border          = hasError ? '1.5px solid #dc3545' : '';
        el.style.backgroundColor = hasError ? '#fff5f5' : '';
    }

    function clearAllErrors() {
        _waFields.forEach(function (f) { setFieldError(f, false); });
    }

    // Attach live clear-on-input listeners once DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        _waFields.forEach(function (f) {
            var el = document.getElementById(f.elId);
            if (!el) return;
            ['input', 'change'].forEach(function (ev) {
                el.addEventListener(ev, function () { setFieldError(f, false); });
            });
        });
    });

    window.openWaModal = function (propPhone, propTitle, propId, propUrl) {
        _waTargetPhone = _waKeyPhone || propPhone;
        _waPropTitle   = propTitle;
        _waPropId      = propId || '';
        _waPropUrl     = propUrl || '';
        var o = document.getElementById('wa-modal-overlay');
        o.style.display = 'flex';
        window.scrollTo({top: window.scrollY, behavior: 'instant'});
        ['wa-salutation', 'wa-nama', 'wa-phone-visitor', 'wa-email'].forEach(function (id) {
            document.getElementById(id).value = '';
        });
        clearAllErrors();
    };

    window.closeWaModal = function () {
        document.getElementById('wa-modal-overlay').style.display = 'none';
    };

    window.submitWaModal = function () {
        var allOk = true;
        _waFields.forEach(function (f) {
            var val = document.getElementById(f.elId).value.trim();
            var hasErr = val === '';
            setFieldError(f, hasErr);
            if (hasErr) allOk = false;
        });
        if (!allOk) return;

        var s = document.getElementById('wa-salutation').value;
        var n = document.getElementById('wa-nama').value.trim();
        var p = document.getElementById('wa-phone-visitor').value.trim();
        var e = document.getElementById('wa-email').value.trim();

        closeWaModal();

        // Collect UTM params from current URL
        var urlParams = new URLSearchParams(window.location.search);
        var hutkMatch = document.cookie.match(/hubspotutk=([^;]+)/);

        // POST to /lead so data is saved to DB and HubSpot
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("front.lead.store") }}';
        form.style.display = 'none';

        var data = {
            '_token':           '{{ csrf_token() }}',
            'salutation':       s,
            'fullname':         n,
            'phone_number':     p,
            'email':            e,
            'property_id':      _waPropId,
            'sumber_informasi': 'web_form_card',
            'contact_form_id':  'promo_card',
            'hubspotutk':       hutkMatch ? hutkMatch[1] : '',
            'utm_source':       urlParams.get('utm_source')   || '',
            'utm_medium':       urlParams.get('utm_medium')   || '',
            'utm_campaign':     urlParams.get('utm_campaign') || '',
            'utm_content':      urlParams.get('utm_content')  || '',
            'utm_term':         urlParams.get('utm_term')     || '',
            'gclid':            urlParams.get('gclid')        || '',
        };

        // Pass key param if present so backend can resolve key-phone override
        var keyParam = urlParams.get('key');
        if (keyParam) data['key'] = keyParam;

        Object.keys(data).forEach(function (k) {
            var inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = k;
            inp.value = data[k];
            form.appendChild(inp);
        });

        document.body.appendChild(form);
        form.submit();
    };
})();
</script>
