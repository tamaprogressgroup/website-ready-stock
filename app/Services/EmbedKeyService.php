<?php

namespace App\Services;

class EmbedKeyService
{
    private const SECRET = 'PdC0nn3ct2026Sec3tKey32BytesXz1!';

    /**
     * Read request('key'), decrypt, validate, return data array or null.
     */
    public static function resolve(): ?array
    {
        $raw = request('key');
        if (!$raw) return null;
        return static::decrypt($raw);
    }

    /**
     * Decrypt the key param using AES-256-CBC.
     *
     * Supported formats:
     *   A) Paradise Connect app format: base64(IV) + ":" + base64(ciphertext)
     *      Regular base64, "+" may arrive as space due to URL form-decoding.
     *   B) Internal format: URL-safe base64 of (IV . ciphertext) concatenated.
     *
     * Validates required keys: nama_sales, no_hp.
     */
    public static function decrypt(string $encoded): ?array
    {
        try {
            // URL form-encoding converts '+' to space — restore before decoding
            $encoded = str_replace(' ', '+', $encoded);

            if (str_contains($encoded, ':')) {
                // Format A: base64(IV):base64(ciphertext)
                [$ivB64, $cipherB64] = explode(':', $encoded, 2);
                $iv         = base64_decode($ivB64, true);
                $ciphertext = base64_decode($cipherB64, true);
                if ($iv === false || $ciphertext === false || strlen($iv) !== 16) return null;
            } else {
                // Format B: URL-safe base64, IV prepended to ciphertext
                $binary = base64_decode(strtr($encoded, '-_', '+/'), true);
                if ($binary === false || strlen($binary) < 17) return null;
                $iv         = substr($binary, 0, 16);
                $ciphertext = substr($binary, 16);
            }

            $plain = openssl_decrypt(
                $ciphertext,
                'AES-256-CBC',
                static::SECRET,
                OPENSSL_RAW_DATA,
                $iv
            );
            if ($plain === false) return null;

            $data = json_decode($plain, true);
            if (!is_array($data)) return null;

            // Normalise: accept flat {"nama_sales":..,"no_hp":..}
            // or nested  {"body":{"nama_sales":..,"no_hp":..},...}
            $payload = $data;
            if (isset($data['body']) && is_array($data['body'])) {
                $payload = array_merge($data, $data['body']);
            }

            if (empty($payload['nama_sales']) || empty($payload['no_hp'])) return null;

            return ['nama_sales' => $payload['nama_sales'], 'no_hp' => $payload['no_hp']];
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Normalize Indonesian phone to international format (62xxx).
     */
    public static function normalizePhone(string $phone): string
    {
        $p = preg_replace('/\D/', '', $phone);
        if (str_starts_with($p, '0')) {
            $p = '62' . substr($p, 1);
        }
        return $p;
    }

    /**
     * Encrypt an associative array to the same URL-safe base64 format.
     * Useful for generating test keys.
     */
    public static function encrypt(array $data): string
    {
        $iv         = random_bytes(16);
        $ciphertext = openssl_encrypt(
            json_encode($data),
            'AES-256-CBC',
            static::SECRET,
            OPENSSL_RAW_DATA,
            $iv
        );
        return rtrim(strtr(base64_encode($iv . $ciphertext), '+/', '-_'), '=');
    }
}
