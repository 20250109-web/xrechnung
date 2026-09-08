<?php
// Minimal client for https://xrechnung.dev (cURL only).
final class XRechnung {
    public function __construct(private ?string $apiKey = null, private string $api = 'https://xrechnung.dev') {}
    private function headers(array $extra = []): array { return array_merge($this->apiKey ? ["X-Api-Key: {$this->apiKey}"] : [], $extra); }
    public function validate(string $path): array {
        $ch = curl_init("{$this->api}/v1/validate");
        curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => ['file' => new CURLFile($path)], CURLOPT_HTTPHEADER => $this->headers(), CURLOPT_RETURNTRANSFER => true]);
        return json_decode(curl_exec($ch), true);
    }
    public function generate(array $invoice, string $format = 'zugferd', string $language = 'de'): string {
        $ch = curl_init("{$this->api}/v1/invoices");
        curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode(['format' => $format, 'language' => $language, 'invoice' => $invoice]),
            CURLOPT_HTTPHEADER => $this->headers(['Content-Type: application/json']), CURLOPT_RETURNTRANSFER => true]);
        $body = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) { throw new RuntimeException(json_decode($body, true)['detail'] ?? $body); }
        return $body;
    }
}
// $xr = new XRechnung(); file_put_contents('invoice.pdf', $xr->generate(json_decode(file_get_contents('../invoice.json'), true)['invoice']));
