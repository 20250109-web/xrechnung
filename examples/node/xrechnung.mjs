// Minimal client for https://xrechnung.dev (fetch, Node 18+).
const API = "https://xrechnung.dev";
export class XRechnung {
  constructor(apiKey) { this.h = apiKey ? { "X-Api-Key": apiKey } : {}; }
  async validate(blob, filename = "document.xml") {
    const fd = new FormData(); fd.append("file", blob, filename);
    return (await fetch(`${API}/v1/validate`, { method: "POST", body: fd, headers: this.h })).json();
  }
  async parse(blob, filename = "document.xml") {
    const fd = new FormData(); fd.append("file", blob, filename);
    return (await fetch(`${API}/v1/parse`, { method: "POST", body: fd, headers: this.h })).json();
  }
  async generate(invoice, format = "zugferd", language = "de") {
    const r = await fetch(`${API}/v1/invoices`, { method: "POST", headers: { ...this.h, "Content-Type": "application/json" }, body: JSON.stringify({ format, language, invoice }) });
    if (!r.ok) throw new Error((await r.json()).detail ?? r.statusText);
    return Buffer.from(await r.arrayBuffer());
  }
}
// import fs from "node:fs"; const xr = new XRechnung(); fs.writeFileSync("invoice.pdf", await xr.generate(JSON.parse(fs.readFileSync("../invoice.json")).invoice));
