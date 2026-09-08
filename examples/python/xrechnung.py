"""Minimal client for https://xrechnung.dev (requests only)."""
import requests

API = "https://xrechnung.dev"


class XRechnung:
    def __init__(self, api_key: str | None = None):
        self.h = {"X-Api-Key": api_key} if api_key else {}

    def validate(self, data: bytes, filename: str = "document.xml") -> dict:
        return requests.post(f"{API}/v1/validate", files={"file": (filename, data)}, headers=self.h, timeout=60).json()

    def parse(self, data: bytes, filename: str = "document.xml") -> dict:
        return requests.post(f"{API}/v1/parse", files={"file": (filename, data)}, headers=self.h, timeout=60).json()

    def generate(self, invoice: dict, fmt: str = "zugferd", language: str = "de") -> bytes:
        r = requests.post(f"{API}/v1/invoices", json={"format": fmt, "language": language, "invoice": invoice}, headers=self.h, timeout=120)
        if r.status_code != 200:
            raise RuntimeError(r.json().get("detail", r.text))
        return r.content


if __name__ == "__main__":
    import json, sys
    xr = XRechnung()
    inv = json.load(open("../invoice.json"))
    pdf = xr.generate(inv["invoice"], fmt="zugferd")
    open("RE-2026-0001.pdf", "wb").write(pdf)
    print("PDF written;", xr.validate(pdf, "RE-2026-0001.pdf")["valid"])
