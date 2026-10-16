# xrechnung.dev — API examples & SDK snippets

Generate, validate and read German e-invoices (**XRechnung 3.0**, **ZUGFeRD 2.x / Factur-X**, EN 16931) with one REST API. Every generated document is checked by the **official KoSIT validator** before you receive it.

- Base URL: `https://xrechnung.dev`
- Free: 10 documents/month per IP, no sign-up. Paid keys (`X-Api-Key`): Starter €19/month (200), Pro €40 (1,000), Business €99 (5,000), Unlimited €499 → https://xrechnung.dev/preise/
- Docs: https://xrechnung.dev/api/ · OpenAPI: https://xrechnung.dev/docs · Guides (DE): https://xrechnung.dev/ratgeber/

| Endpoint | Purpose |
|---|---|
| `POST /v1/invoices` | JSON → validated XRechnung XML or ZUGFeRD PDF |
| `POST /v1/validate` | XML or ZUGFeRD PDF → validation report (errors/warnings with rule codes) |
| `POST /v1/parse` | XML or PDF → JSON (parties, lines, totals, payment) |
| `GET /v1/usage` | quota for your key / IP |

## Quick start (curl)

```bash
curl -X POST https://xrechnung.dev/v1/validate --data-binary @examples/sample-xrechnung.xml
curl -X POST https://xrechnung.dev/v1/invoices -H "Content-Type: application/json" -d @examples/invoice.json -o invoice.pdf
```

See `examples/python`, `examples/php`, `examples/node` and the Postman collection in `postman/`.

## Invoice JSON (minimal)

```json
{ "format": "zugferd",
  "invoice": {
    "number": "RE-2026-0001", "issue_date": "2026-09-08",
    "seller": {"name": "Beispiel GmbH", "vat_id": "DE123456789", "email": "rechnung@beispiel.de", "phone": "+49 30 1234567",
               "address": {"street": "Musterstr. 1", "postcode": "10115", "city": "Berlin"}},
    "buyer":  {"name": "Kunde AG", "address": {"street": "Kundenweg 9", "postcode": "80331", "city": "München"}},
    "lines":  [{"description": "Beratung", "quantity": "10", "unit": "HUR", "unit_price": "120.00"}],
    "payment": {"iban": "DE02120300000000202051", "due_date": "2026-09-22"} } }
```

Field reference: https://xrechnung.dev/api/#invoice-model. Money is rounded per line (round-half-up) so EN 16931 arithmetic rules always hold; XRechnung's German rules (BR-DE-*) are enforced up front with clear messages.

## Also from xrechnung.dev
- **WooCommerce plugin** — e-invoice for every order: https://xrechnung.dev/woocommerce/
- **Chrome extension** — validate/read e-invoice attachments in the browser: https://xrechnung.dev/chrome/
- **RapidAPI listing** — same API with RapidAPI billing (BASIC free, PRO $19, ULTRA $59): https://rapidapi.com/nikolamaksimlab/api/xrechnung-zugferd-e-invoice

MIT-licensed examples. The API itself is a hosted service; see https://xrechnung.dev/datenschutz.
