<?php

namespace App\Http\Controllers\Front;

use App\Models\Lead;
use App\Models\PropertyUnit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeadController extends BaseFrontController
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'salutation'   => 'required|in:Bapak,Ibu',
            'fullname'     => 'required|string|max:70',
            'phone_number' => 'required|string|max:15',
            'email'        => 'nullable|email|max:50',
            'enquiry'      => 'nullable|string|max:50',
            'property_id'  => 'required|integer',
        ]);

        // Load unit once — used for both township data and SEO redirect
        $unit = PropertyUnit::with([
            'township',
            'translations'              => fn($q) => $q->where('locale', 'id'),
            'condition.translations'    => fn($q) => $q->where('locale', 'id'),
            'propertyType.translations' => fn($q) => $q->where('locale', 'id'),
            'kota',
        ])->find($request->property_id);

        $lead = Lead::create([
            'salutation'       => $request->salutation,
            'fullname'         => $request->fullname,
            'email'            => $request->email,
            'phone_number'     => $request->phone_number,
            'enquiry'          => $request->enquiry,
            'property_id'      => $request->property_id,
            'township_id'      => $unit?->township_id,
            'cluster_id'       => $unit?->cluster_id,
            'sumber_informasi' => $request->get('sumber_informasi', 'web_form_detail'),
            'contact_form_id'  => $request->get('contact_form_id',  'promo_detail'),
            'hutk'             => $request->get('hubspotutk'),
            'remote_addr'      => $request->ip(),
            'url_form'         => $request->header('referer'),
            'url_origin'       => $request->fullUrl(),
            'hubspot_submit'   => false,
            'params'           => json_encode($request->only([
                'utm_source', 'utm_campaign', 'utm_medium',
                'utm_content', 'utm_term', 'gclid',
            ])),
            'created_at'       => now(),
        ]);

        $this->submitToHubspot($lead, $request, $unit);

        // Build WA redirect URL — key phone overrides property phone if key is active
        $waUrl    = '';
        $keyData  = \App\Services\EmbedKeyService::resolve();
        $rawPhone = ($keyData['no_hp'] ?? '') ?: ($unit?->no_hp ?? '');
        if ($rawPhone) {
            $waPhone   = ltrim($this->formatPhone($rawPhone), '+');
            $propTitle = $unit?->translations->first()?->title
                      ?? $unit?->translations->first()?->property_name
                      ?? '';
            $greeting  = trim(($request->salutation ? $request->salutation . ' ' : '') . $request->fullname);
            $waText    = 'Halo, Saya ' . $greeting . ' ingin informasi lengkap tentang ' . $propTitle . ', Mohon kirimkan detailnya.';
            $waUrl     = 'https://api.whatsapp.com/send/?phone=' . $waPhone
                       . '&text=' . rawurlencode($waText)
                       . '&type=phone_number&app_absent=0';
        }

        // Build SEO thank-you URL: /{condition}/{type}/{kota}/{township}/{slug}/thankyou
        if ($unit?->slug) {
            $condSlug = Str::slug($unit->condition?->translations->first()?->condition_name ?? '');
            $typeSlug = Str::slug($unit->propertyType?->translations->first()?->type_name ?? '');
            $kotaSlug = Str::slug($unit->kota?->nama_kota ?? '');
            $twnSlug  = Str::slug($unit->township?->township_name ?? '');

            if ($condSlug && $typeSlug && $kotaSlug && $twnSlug) {
                $thankyouUrl = "/{$condSlug}/{$typeSlug}/{$kotaSlug}/{$twnSlug}/{$unit->slug}/thankyou";
                if ($waUrl) $thankyouUrl .= '?wa_url=' . urlencode($waUrl);
                return redirect($thankyouUrl);
            }
        }

        return redirect()->route('front.thankyou', array_filter([
            'property_id' => $request->property_id,
            'wa_url'      => $waUrl ?: null,
        ]));
    }

    /** SEO route: /{condition}/{type}/{kota}/{township}/{slug}/thankyou */
    public function thankyouSeo(
        string $condition,
        string $type,
        string $kota,
        string $township,
        string $slug
    ): View {
        $raw   = request()->get('wa_url', '');
        $waUrl = str_starts_with($raw, 'https://api.whatsapp.com/') ? $raw : '';
        $unit  = PropertyUnit::where('slug', $slug)->first();
        return $this->renderThankyou($unit, $waUrl);
    }

    /** Fallback route: /thankyou?property_id=X&wa_url=... */
    public function thankyou(Request $request): View
    {
        $raw   = $request->get('wa_url', '');
        $waUrl = str_starts_with($raw, 'https://api.whatsapp.com/') ? $raw : '';

        $unit = $request->filled('property_id')
            ? PropertyUnit::find((int) $request->property_id)
            : null;
        return $this->renderThankyou($unit, $waUrl);
    }

    private function renderThankyou(?PropertyUnit $unit, string $waUrl = ''): View
    {
        if ($unit) {
            $unit->load([
                'interiors'    => fn($q) => $q->where('order', 1)->where('is_active', 1),
                'translations' => fn($q) => $q->where('locale', 'id'),
            ]);
        }

        $firstImage = $unit?->interiors->first()?->image;
        $image      = $firstImage
            ? asset('storage/' . $firstImage)
            : asset('stock-image/rekomendasi-property.jpg');
        $title      = $unit?->translations->first()?->title
                   ?? $unit?->translations->first()?->property_name;

        return view('front.layout.thankyou', compact('image', 'title', 'waUrl'));
    }

    // =========================================================================
    // HubSpot integration
    // =========================================================================

    private function submitToHubspot(Lead $lead, Request $request, ?PropertyUnit $unit): void
    {
        $townshipName = $unit?->township?->township_name ?? '';
        $productTitle = $unit?->translations->first()?->title
                     ?? $unit?->translations->first()?->property_name
                     ?? '';

        $params = [
            'fullname'      => $lead->fullname,
            'salutation'    => $lead->salutation,
            'email'         => $lead->email,
            'phone_number'  => $lead->phone_number,
            'Township'      => $townshipName,
            'product_tipe'  => $productTitle,
            'unit'          => '',
            'enquiry'       => $lead->enquiry ?? 'Kirimkan Informasi Properti',
            'hubspotutk'    => $lead->hutk,
            'ipaddress'     => $lead->remote_addr,
            'url_form'      => $lead->url_form,
            'url_origin'    => $lead->url_origin,
            'utm_campaign'  => $request->get('utm_campaign'),
            'utm_source'    => $request->get('utm_source'),
            'utm_medium'    => $request->get('utm_medium'),
            'utm_content'   => $request->get('utm_content'),
            'utm_term'      => $request->get('utm_term'),
            'gclid'         => $request->get('gclid'),
            'reengage_form' => false,
            'prospect_id'   => $lead->id,
            'table'         => 'm_leads',
            'rencana_beli'  => $lead->rencana_beli,
            'jumlah_kamar'  => $lead->jumlah_kamar,
            'berminat_cari' => $lead->berminat_cari,
        ];

        try {
            $this->processHubspot($params);
        } catch (\Throwable $e) {
            Log::error('HubSpot submit error', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
        }
    }

    private function processHubspot(array $params): void
    {
        date_default_timezone_set('UTC');

        $phone     = $this->formatPhone($params['phone_number']);
        $email     = $params['email'];
        $name      = trim(ucwords(strtolower($params['fullname'])));
        $nameParts = explode(' ', $name);
        $firstname = $nameParts[0];
        $lastname  = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        $salutation  = $this->mapSalutation($params['salutation']); // Pak / Ibu
        $nameForMail = trim("$salutation $firstname");
        $townData    = $this->mapTownship($params['Township']);
        $timestamp   = strtotime('today midnight') * 1000;

        Log::info('HubSpot: processHubspot start', [
            'lead_id'     => $params['prospect_id'],
            'phone_raw'   => $params['phone_number'],
            'phone_fmt'   => $phone,
            'is_indo'     => $this->isIndoPhone($phone),
            'email'       => $email,
            'township'    => $params['Township'],
            'town_code'   => $townData['code'],
        ]);

        $hsContext = json_encode([
            'hutk'      => $params['hubspotutk'],
            'ipAddress' => $params['ipaddress'],
            'pageUrl'   => $params['url_form'],
            'pageName'  => 'Contact Form',
        ]);

        $postData = [
            'firstname'                              => $firstname,
            'lastname'                               => $lastname,
            'phone'                                  => $phone,
            'email'                                  => $email,
            'mobilephone'                            => $phone,
            'wa_phone_number'                        => str_replace('+', '', $phone),
            'salutation'                             => $salutation,
            'nomor_hp'                               => $phone,
            'name_for_mail'                          => $nameForMail,
            'first_project_website'                  => $townData['code'],
            'first_project_product'                  => $params['product_tipe'],
            'reengage_last_cluster'                  => $params['unit'],
            'reengage_last_date_pricelist_requested' => $timestamp,
            'webform_note'                           => $params['enquiry'],
            'is_intercom_migrated'                   => 'false',
            'sales_team'                             => 'Telesales',
            'tanggal_email_signup'                   => $timestamp,
            'project_category'                       => 'residential',
            'hs_context'                             => $hsContext,
        ];

        Log::info('HubSpot: postData fields', array_diff_key($postData, ['hs_context' => '']));

        $contactUpdate = $postData;
        unset($contactUpdate['hs_context']);

        // Optional lead-quality fields
        $optionalFields = [
            'rencana_beli'  => 'rencana_beli_rumah',
            'jumlah_kamar'  => 'kebutuhan_jumlah_kamar',
            'berminat_cari' => 'berminat_cari_rumah__',
        ];
        foreach ($optionalFields as $key => $postKey) {
            if (!empty($params[$key])) {
                $postData[$postKey]      = $params[$key];
                $contactUpdate[$postKey] = $params[$key];
            }
        }

        // UTM / tracking fields
        $utmFields = ['utm_campaign', 'utm_medium', 'utm_source', 'utm_content', 'gclid', 'utm_term'];
        foreach ($utmFields as $field) {
            $postData[$field]      = $params[$field] ?? '';
            $contactUpdate[$field] = $params[$field] ?? '';
        }

        $portalId     = config('services.hubspot.portal_id');
        $formNew      = config('services.hubspot.form_new');
        $formReengage = config('services.hubspot.form_reengage');
        $formGuid     = $params['reengage_form'] ? $formReengage : $formNew;
        $formUrl      = $formGuid
            ? "https://forms.hubspot.com/uploads/form/v2/{$portalId}/{$formGuid}"
            : null;
        $noteUrl      = 'https://api.hubapi.com/engagements/v1/engagements';

        if (!$formUrl) {
            Log::warning('HubSpot: HUBSPOT_FORM_NEW not configured — will use CRM API fallback to create contacts directly.');
        }

        if ($this->isIndoPhone($phone)) {
            $contact = $this->searchContact(['email' => $email, 'phone_number' => $phone]);
            Log::info('HubSpot: searchContact result', [
                'lead_id'        => $params['prospect_id'],
                'total_results'  => $contact['total'] ?? 0,
            ]);

            if ($contact && !empty($contact['results'])) {
                $contactData  = $contact['results'][0];
                $contactId    = $contactData['id'];
                $statusOld    = $contactData['properties']['status_prospect'] ?? '';
                $firstnameOld = $contactData['properties']['firstname'] ?? '';
                $lastnameOld  = $contactData['properties']['lastname'] ?? '';
                $hpOld        = $contactData['properties']['nomor_hp'] ?? '';
                $workflow     = $contactData['properties']['currentlyinworkflow'] ?? 'false';

                Log::info('HubSpot: existing contact found', [
                    'lead_id'    => $params['prospect_id'],
                    'contact_id' => $contactId,
                    'status_old' => $statusOld,
                    'workflow'   => $workflow,
                ]);

                $lastDatePricelistEmailed = $contactData['properties']['reengage_last_date_pricelist_emailed'] ?? '';
                $repeatReengage = true;
                if ($lastDatePricelistEmailed) {
                    $days = Carbon::now()->diffInDays(Carbon::parse($lastDatePricelistEmailed));
                    $repeatReengage = $days < 180;
                }

                $noteBody = collect([
                    'Repeat Visitor (No Automated Action Taken)', '',
                    "Salutation = $salutation",
                    "First Name = $firstname",
                    "Last Name = $lastname",
                    "Mobile Phone = $phone",
                    "Email = $email",
                    "First Project Website = {$townData['code']}",
                    "First Project Product = {$params['product_tipe']}",
                    "Web Form Note = {$params['enquiry']}",
                    'Prospek ini melakukan registrasi email form untuk meminta informasi properti.',
                ])->map(fn($line) => "<p>$line</p>")->implode('');

                $jsonNote = [
                    'engagement'   => ['active' => true, 'type' => 'NOTE', 'timestamp' => now()->getTimestampMs()],
                    'associations' => ['contactIds' => [$contactId]],
                    'metadata'     => ['body' => $noteBody],
                ];

                if ($params['reengage_form'] && !$repeatReengage) {
                    $postData['status_prospect']         = '00B-Prospek Kembali & Belum Dihubungi';
                    $postData['reengage_follow_up']      = 'true';
                    $postData['repeat_follow_up']        = 'false';
                    $contactUpdate['status_prospect']    = '00B-Prospek Kembali & Belum Dihubungi';
                    $contactUpdate['reengage_follow_up'] = true;
                    $contactUpdate['repeat_follow_up']   = false;
                    $res = $email
                        ? $this->submitFormOrCrm($formUrl, $postData, $contactUpdate)
                        : $this->patchContact($contactId, $contactUpdate);
                    Log::info('HubSpot: reengage submit result', ['lead_id' => $params['prospect_id'], 'response' => $res]);
                } elseif ($workflow === 'true') {
                    $res = $this->submitNote($noteUrl, $jsonNote);
                    Log::info('HubSpot: note (workflow active)', ['lead_id' => $params['prospect_id'], 'response' => $res]);
                } elseif (in_array($statusOld, ['00A-Baru & Belum Dihubungi', '30-Reserved'])) {
                    $res = $this->submitNote($noteUrl, $jsonNote);
                    Log::info('HubSpot: note (status protected)', ['lead_id' => $params['prospect_id'], 'response' => $res]);
                } else {
                    $postData['repeat_follow_up']        = 'true';
                    $postData['reengage_follow_up']      = 'false';
                    $contactUpdate['reengage_follow_up'] = false;
                    $contactUpdate['repeat_follow_up']   = true;
                    $res = $email
                        ? $this->submitFormOrCrm($formUrl, $postData, $contactUpdate)
                        : $this->patchContact($contactId, $contactUpdate);
                    Log::info('HubSpot: repeat follow-up result', ['lead_id' => $params['prospect_id'], 'response' => $res]);

                    // If name or phone changed, add a note
                    if ($firstnameOld !== $firstname || $lastnameOld !== $lastname || $hpOld !== $phone) {
                        $jsonNote['metadata']['body'] = collect([
                            'Repeat Visitor (Name/HP Changed)', '',
                            "First Name = $firstnameOld",
                            "Last Name = $lastnameOld",
                            "Mobile Phone = $hpOld", '',
                            'Prospek ini melakukan registrasi, Name / HP berubah.',
                        ])->map(fn($line) => "<p>$line</p>")->implode('');
                        $this->submitNote($noteUrl, $jsonNote);
                    }
                }
            } else {
                // New contact
                Log::info('HubSpot: new contact branch', ['lead_id' => $params['prospect_id']]);
                $postData['status_prospect']    = '00A-Baru & Belum Dihubungi';
                $postData['repeat_follow_up']   = 'false';
                $postData['reengage_follow_up'] = 'false';
                $res = $this->submitFormOrCrm($formUrl, $postData, $contactUpdate);
                Log::info('HubSpot: new contact result', ['lead_id' => $params['prospect_id'], 'response' => $res]);
            }
        } else {
            Log::warning('HubSpot: phone is not Indo — skipping HubSpot submission', [
                'lead_id' => $params['prospect_id'],
                'phone'   => $phone,
            ]);
        }

        DB::table($params['table'])
            ->where('id', $params['prospect_id'])
            ->update(['hubspot_submit' => 1]);
    }

    /**
     * Submit via HubSpot Forms API if a form GUID is configured,
     * otherwise fall back to CRM API to create/update the contact directly.
     */
    private function submitFormOrCrm(?string $formUrl, array $postData, array $contactProperties): array
    {
        if ($formUrl) {
            return $this->submitForm($formUrl, $postData);
        }

        // CRM API fallback: upsert by email
        $email = $contactProperties['email'] ?? null;
        if ($email) {
            return $this->upsertContactByEmail($email, $contactProperties);
        }

        return $this->createContact($contactProperties);
    }

    // =========================================================================
    // HubSpot API helpers
    // =========================================================================

    private function submitForm(string $url, array $data): array
    {
        // Forms v2 API does not use Bearer token auth — sending it causes 204 without contact creation
        $response = Http::timeout(10)
            ->asForm()
            ->post($url, $data);

        $level = $response->failed() ? 'error' : 'info';
        Log::$level('HubSpot: submitForm response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return $response->json() ?? [];
    }

    private function patchContact(string $contactId, array $data): array
    {
        $response = Http::withToken(config('services.hubspot.token'))
            ->timeout(10)
            ->patch("https://api.hubapi.com/crm/v3/objects/contacts/{$contactId}", [
                'properties' => $data,
            ]);

        $level = $response->failed() ? 'error' : 'info';
        Log::$level('HubSpot: patchContact response', [
            'contact_id' => $contactId,
            'status'     => $response->status(),
            'body'       => $response->body(),
        ]);

        return $response->json() ?? [];
    }

    private function submitNote(string $url, array $data): array
    {
        $response = Http::withToken(config('services.hubspot.token'))
            ->timeout(10)
            ->post($url, $data);

        $level = $response->failed() ? 'error' : 'info';
        Log::$level('HubSpot: submitNote response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return $response->json() ?? [];
    }

    private function createContact(array $properties): array
    {
        $response = Http::withToken(config('services.hubspot.token'))
            ->timeout(10)
            ->post('https://api.hubapi.com/crm/v3/objects/contacts', [
                'properties' => $properties,
            ]);

        $level = $response->failed() ? 'error' : 'info';
        Log::$level('HubSpot: createContact response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return $response->json() ?? [];
    }

    private function upsertContactByEmail(string $email, array $properties): array
    {
        $url = 'https://api.hubapi.com/crm/v3/objects/contacts/' . urlencode($email) . '?idProperty=email';
        $response = Http::withToken(config('services.hubspot.token'))
            ->timeout(10)
            ->patch($url, ['properties' => $properties]);

        // 404 means contact doesn't exist yet — create it
        if ($response->status() === 404) {
            return $this->createContact($properties);
        }

        $level = $response->failed() ? 'error' : 'info';
        Log::$level('HubSpot: upsertContact response', [
            'email'  => $email,
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return $response->json() ?? [];
    }

    private function searchContact(array $params): array
    {
        $filters = [];
        if (!empty($params['email'])) {
            $filters[] = ['filters' => [['propertyName' => 'email', 'operator' => 'EQ', 'value' => $params['email']]]];
        }
        if (!empty($params['phone_number'])) {
            $filters[] = ['filters' => [['propertyName' => 'mobilephone', 'operator' => 'EQ', 'value' => $params['phone_number']]]];
            $filters[] = ['filters' => [['propertyName' => 'phone',       'operator' => 'EQ', 'value' => $params['phone_number']]]];
        }

        $response = Http::withToken(config('services.hubspot.token'))
            ->timeout(10)
            ->post('https://api.hubapi.com/crm/v3/objects/contacts/search', [
                'properties'   => [
                    'email', 'firstname', 'lastname', 'nomor_hp', 'status_prospect',
                    'hubspot_owner_id', 'currentlyinworkflow',
                    'reengage_last_date_pricelist_emailed', 'notes_last_updated',
                ],
                'filterGroups' => $filters,
                'sorts'        => [['propertyName' => 'notes_last_updated', 'direction' => 'DESCENDING']],
            ]);

        return $response->json() ?? [];
    }

    // =========================================================================
    // Utility helpers
    // =========================================================================

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        if (str_starts_with($phone, '+62'))  return $phone;
        if (str_starts_with($phone, '62'))   return '+' . $phone;
        if (str_starts_with($phone, '0'))    return '+62' . substr($phone, 1);
        if (preg_match('/^8[1-9][0-9]{7,10}$/', $phone)) return '+62' . $phone;
        return '+' . $phone;
    }

    private function isIndoPhone(string $phone): bool
    {
        return str_starts_with($this->formatPhone($phone), '+628');
    }

    private function mapSalutation(string $salutation): string
    {
        return match (strtolower($salutation)) {
            'bapak', 'mr.' => 'Pak',
            'ibu',   'ms.' => 'Ibu',
            default         => $salutation,
        };
    }

    private function mapTownship(string $township): array
    {
        $map = config('readystock.hubspot_township_map', []);
        return $map[$township] ?? ['code' => $township, 'project' => Str::slug($township)];
    }
}
