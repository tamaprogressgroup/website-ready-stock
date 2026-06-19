@php
    $embedShareUrl = url()->current() . '?key=' . rawurlencode(request('key') ?? '');
    $isAllProducts = request()->routeIs('front.all-products');
@endphp

<div id="embed-nav" style="position:fixed;top:0;left:0;right:0;z-index:9999;height:56px;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.10);display:flex;align-items:center;padding:0 12px;">

    @if($isAllProducts)
    {{-- Spacer left to balance share button and keep logo centred --}}
    <div style="width:44px;flex-shrink:0;"></div>
    @else
    {{-- Back button --}}
    <button onclick="history.back()" aria-label="Kembali"
        style="background:none;border:none;padding:8px 10px 8px 4px;cursor:pointer;color:#333;flex-shrink:0;display:flex;align-items:center;-webkit-tap-highlight-color:transparent;">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    @endif

    {{-- Logo — centred between left and right elements --}}
    <div style="flex:1;display:flex;justify-content:center;align-items:center;">
        <a href="{{ url('/all-products') }}?embed=1&key={{ rawurlencode(request('key') ?? '') }}"
           style="display:flex;align-items:center;">
            <img src="{{ asset('stock-image/progress-logo-colored.png') }}" alt="Paradise Ready Stock"
                 style="height:30px;max-width:160px;object-fit:contain;">
        </a>
    </div>

    {{-- Share button — always visible --}}
    <div style="position:relative;flex-shrink:0;">
        <button class="embed-share-btn" onclick="toggleEmbedShare(event)" aria-label="Bagikan"
            style="background:none;border:none;padding:8px 4px 8px 10px;cursor:pointer;color:#333;display:flex;align-items:center;-webkit-tap-highlight-color:transparent;">
            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="18" cy="5" r="3"></circle>
                <circle cx="6" cy="12" r="3"></circle>
                <circle cx="18" cy="19" r="3"></circle>
                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
            </svg>
        </button>
        <div id="embed-share-panel"
             style="display:none;position:absolute;top:46px;right:0;background:#fff;border-radius:12px;
                    box-shadow:0 4px 24px rgba(0,0,0,0.16);padding:8px;min-width:175px;z-index:1000;">
            <a href="whatsapp://send?text={{ rawurlencode($embedShareUrl) }}"
               style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;
                      color:#333;text-decoration:none;font-size:14px;font-weight:600;">
                <i class="fab fa-whatsapp" style="color:#25D366;font-size:18px;width:20px;text-align:center;"></i>
                WhatsApp
            </a>
            <button onclick="copyEmbedLink('{{ addslashes($embedShareUrl) }}')"
                style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;
                       color:#333;background:none;border:none;font-size:14px;font-weight:600;
                       width:100%;cursor:pointer;text-align:left;">
                <i class="fas fa-link" style="color:#3b5998;font-size:15px;width:20px;text-align:center;"></i>
                Salin Link
            </button>
        </div>
    </div>

</div>
{{-- Push page content below fixed bar --}}
<div style="height:56px;"></div>

<script>
function toggleEmbedShare(e) {
    e.stopPropagation();
    var p = document.getElementById('embed-share-panel');
    if (p) p.style.display = p.style.display === 'none' ? 'block' : 'none';
}
function copyEmbedLink(url) {
    var p = document.getElementById('embed-share-panel');
    if (p) p.style.display = 'none';
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(showCopyToast);
    } else {
        var ta = document.createElement('textarea');
        ta.value = url; document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); showCopyToast(); } catch(e) {}
        document.body.removeChild(ta);
    }
}
function showCopyToast() {
    var t = document.createElement('div');
    t.textContent = 'Link berhasil disalin!';
    t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#333;color:#fff;padding:10px 20px;border-radius:8px;font-size:14px;z-index:10001;pointer-events:none;';
    document.body.appendChild(t);
    setTimeout(function() { document.body.removeChild(t); }, 2200);
}
document.addEventListener('click', function() {
    var p = document.getElementById('embed-share-panel');
    if (p) p.style.display = 'none';
});
</script>
