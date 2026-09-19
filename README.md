# VeriVote NG

### Evidence you can verify. Results you can audit.

VeriVote NG is an independent evidence-verification layer for polling-unit election results.

It binds source evidence to recorded vote data, applies cryptographic verification, evaluates deterministic integrity rules, detects discrepancies, and exposes an auditable public verification record.

> **AI interprets. Cryptography protects. Deterministic rules verify. Humans decide.**

---

## The Problem

There is a trust gap between polling-unit result evidence and later representations of that result during the electoral collation process.

A result may exist as:

- Recorded vote data
- Physical source evidence
- Digitally transmitted or published information
- Later representations used during collation or review

VeriVote NG focuses on the integrity of the recorded evidence and the consistency of the data associated with it.

The system does not attempt to determine electoral intent or declare fraud.

Instead, it provides technical evidence that can identify an integrity failure or discrepancy requiring human investigation.

---

## What VeriVote NG Does

The current MVP provides a complete verification workflow:

1. Record a polling-unit result.
2. Attach source evidence to the result.
3. Generate a deterministic SHA-256 payload hash.
4. Hash the source evidence.
5. Bind result data and evidence cryptographically.
6. Verify an Ed25519 signature.
7. Run deterministic mathematical integrity checks.
8. Detect and classify discrepancies.
9. Record verification events in a tamper-evident audit chain.
10. Expose the result through public verification.
11. Generate a public verification report.
12. Provide QR-based access to public verification.

---

## Current MVP

### Implemented

- Polling-unit result capture
- Source evidence storage
- SHA-256 payload hashing
- SHA-256 evidence hashing
- Ed25519 digital signatures
- Evidence-bound cryptographic verification
- Deterministic result verification
- Discrepancy detection
- Verification status tracking
- Hash-linked audit trail
- Public verification
- Verification reports
- QR verification
- Automated tests
- Responsive product landing page

### Intentionally not part of the current MVP

The following capabilities are future extensions and are **not represented as implemented functionality**:

- Offline-first synchronization
- Distributed/local evidence exchange
- Cross-source comparison with later published representations
- Expanded AI assistance
- Incident and safety reporting
- Broader electoral audit infrastructure

These are clearly marked as **Coming Soon** in the application.

---

# Verification Architecture

VeriVote NG separates the different responsibilities involved in establishing and interpreting evidence.

```text
                    ┌─────────────────────────┐
                    │   Polling Unit Result   │
                    └────────────┬────────────┘
                                 │
                                 ▼
                    ┌─────────────────────────┐
                    │    Source Evidence      │
                    └────────────┬────────────┘
                                 │
                   ┌─────────────┴─────────────┐
                   ▼                           ▼
          ┌─────────────────┐        ┌─────────────────┐
          │   SHA-256 Hash  │        │   Evidence Hash │
          └────────┬────────┘        └────────┬────────┘
                   │                          │
                   └────────────┬─────────────┘
                                ▼
                     ┌────────────────────┐
                     │ Ed25519 Signature  │
                     └─────────┬──────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │ Cryptographic Check  │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │ Deterministic Rules  │
                    │        C1 – C5       │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │ Discrepancy Detection│
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │    Audit Trail       │
                    └──────────┬───────────┘
                               │
                               ▼
              ┌─────────────────────────────────┐
              │ Public Verification / Report /  │
              │              QR                 │
              └─────────────────────────────────┘