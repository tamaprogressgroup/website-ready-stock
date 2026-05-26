<div class="card border-0 bg-transparent h-100">
    <div class="position-relative">
        {{-- Badges from property condition --}}
        @if(!empty($prop['badges']))
        <div class="position-absolute pt-2 ps-2 z-index-1" style="top: 0; left: 0;">
            @foreach ($prop['badges'] as $badge)
                <span class="badge poppins-medium px-3 py-1 me-1"
                      style="background-color: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border-radius: 15px; font-size: 11px;">
                    {{ $badge['text'] }}
                </span>
            @endforeach
        </div>
        @endif
        <img src="{{ $prop['image'] }}" alt="{{ $prop['title'] }}" class="property-card-img">
    </div>
    <div class="card-body px-0 py-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="property-card-price poppins-bold" style="font-size: 16.8px;" >{{ $prop['price'] }}</span>
            <a href="{{ $prop['detail_url'] }}" class="text-decoration-none"
               style="color: #3b5998;">
                <i class="fas fa-arrow-right" style="font-size: 15px;"></i>
            </a>
        </div>
        <div style="border-bottom: 1px solid #eaeaea; margin-bottom: 10px;"></div>
        <p class="property-card-title mb-1 poppins-semibold" style="font-size: 14px;" >{{ $prop['title'] }}</p>
        <p class="property-card-loc poppins-medium" style="font-size: 11px;" >{{ $prop['location'] }}</p>
        <div class="d-flex justify-content-between property-card-specs mb-4">
            <span class="poppins-semibold" style="font-size: 11px;"><i class="fas fa-bed me-1 " style="color: #777777;"></i>{{ $prop['beds'] }}</span>
            <span class="poppins-semibold" style="font-size: 11px;"><i class="fas fa-bath me-1" style="color: #777777;"></i>{{ $prop['baths'] }}</span>
            <span class="poppins-semibold" style="font-size: 11px;">LT <strong class="poppins-semibold">{{ $prop['lt'] }}</strong>m²</span>
            <span class="poppins-semibold" style="font-size: 11px;">LB <strong class="poppins-semibold">{{ $prop['lb'] }}</strong>m²</span>
        </div>
       <a href="{{ $prop['detail_url'] }}"
   class="btn w-100 font-weight-bold py-2 text-white poppins-bold d-flex align-items-center justify-content-center"
   style="background-color: #43CB83; border-radius: 8px; border: none; font-size: 15px;">
    <i class="fab fa-whatsapp me-2" style="font-size: 26px;"></i> WhatsApp
</a>
    </div>
</div>
