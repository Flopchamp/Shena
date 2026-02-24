**Shena — Deployment & Finalization Checklist**

This document lists all necessary deployment-related adjustments, UI/logic fixes, and verification steps required before finalizing and declaring the project production-ready. It is written as a GitHub issue/actionable checklist with specific files called out and implementation guidance.

**Summary**
- **Problem**: Several functional and UX issues affect registration, beneficiary handling, contribution calculation, payments integration (MPesa), and notification services. These must be fixed before production rollout.
- **Goal**: Ensure age-based incremental contribution logic is applied everywhere (registration, beneficiary add, plan upgrades), remove email as a required registration field, enforce uniform phone format that works with M-Pesa, add a proper scan-to-pay QR in forms, confirm and fix SMS/email services, and make UI forms minimal and consistent with SHA's approach.

**Critical Fixes (What / Why / How)**n
- **1) Add date of birth to beneficiary records (required for age-based increments)**
  - Why: Current `beneficiaries` table lacks `date_of_birth` which prevents computing dependent ages and incremental contributions. The system currently uses `dependents` table for DOB but `members->getMemberDependents` and UI use `beneficiaries` — inconsistent.
  - Files / DB:
    - **DB migration**: add `date_of_birth DATE NULL` to `database/schema.sql` (and create a migration file under `database/migrations` e.g., `010_add_beneficiary_dob.sql`).
    - Table: `beneficiaries` (database/schema.sql)
  - SQL example to add column (migration):
    ```sql
    ALTER TABLE beneficiaries
      ADD COLUMN date_of_birth DATE DEFAULT NULL AFTER id_number;
    ```
  - Implementation notes:
    - Backfill: Optionally migrate DOB from `dependents` if that table contains the same individuals (run an ad-hoc migration SQL if mapping exists).
    - Update model/controller/UI to capture `date_of_birth` on add/edit.

- **2) Implement age-based incremental contribution logic and apply everywhere**
  - Why: Contribution must increment based on age brackets and package rules (e.g., children under 18, second child, adults, senior contributions). This must apply during registration, when adding beneficiaries/dependents, and during plan upgrades to avoid wrong package selection errors.
  - Files to change:
    - `app/models/Member.php` — centralize contribution calculation function(s)
    - `app/models/Beneficiary.php` — accept `date_of_birth` and compute age_bracket
    - `app/controllers/MemberController.php` — update add-dependent and upgrade flows
    - `app/controllers/AuthController.php` (registration flow) — compute contribution on step/package selection
    - `resources/views/auth/register.php`, `resources/views/member/beneficiaries.php`, `resources/views/member/upgrade.php` — UI must show contributions and recalculations in realtime
  - Implementation approach:
    1. Add a new well-documented method `Member::calculateMonthlyContribution($memberIdOrPackageData, $dependents = [])` which receives current package and list of dependents (including ages) and returns the monthly contribution and a breakdown.
    2. Age calculation: use existing `Member::calculateAge($dateOfBirth)`.
    3. Plan rules should be extracted to a config (e.g., `config/packages.php` or into `settings` DB): base amounts and incremental rules per age bracket.
    4. When a beneficiary is added/edited or plan/upgrades are requested, re-run `calculateMonthlyContribution` and update `members.monthly_contribution` (or show preview for upgrades with `plan_upgrade_requests` storing `current_monthly_fee` and `new_monthly_fee`).
    5. Remove UI flows that rely on client-chosen package only — compute recommended package or at least validate choice based on dependents and age.
  - Example files touched:
    - `app/models/Member.php` — add `calculateMonthlyContribution()` and update functions that set `monthly_contribution`.
    - `app/models/Beneficiary.php` — when creating, compute `age` and `age_bracket` and return in response.

- **3) Fix wrong package chosen errors by validating package against dependents**
  - Why: Users may pick packages that don't match the dependents they added; the system should validate and either suggest the correct package or automatically adjust contribution and package.
  - Files to change:
    - `resources/views/auth/register.php` — show a small warning and suggested package if mismatch detected
    - `app/controllers/AuthController.php` — on package selection, validate count and ages of dependents and return structured errors (not generic "wrong package chosen")

- **4) Remove Email as required field in the application form**
  - Why: Email should be optional during registration (some users may register via phone only). Notifications will fall back to SMS.
  - Files to change:
    - `resources/views/auth/register.php` — remove `required` attribute from email input and update placeholder text to indicate optional.
    - `app/controllers/AuthController.php` / registration handler — make email optional and avoid validation failure when email missing.
    - `app/models/User.php` or DB `users` table: ensure email column allows NULL or handle absent email gracefully.

- **5) Enforce a uniform phone number format that supports M-Pesa prompts**
  - Why: MPesa and HostPinnacle expect `2547XXXXXXXX` (Kenya) format. UI and backend must normalize and validate.
  - Files to change:
    - `resources/views/auth/register.php`, `resources/views/member/beneficiaries.php` — update phone input placeholder and client-side normalization script.
    - `app/services/SmsService.php` — already has `formatPhoneNumber()` and `validatePhoneNumber()`; verify and use them consistently.
    - `app/controllers/*` — when saving phone numbers, store normalized `2547...` format in `users.phone`, `members.next_of_kin_phone`, `beneficiaries.phone_number`, etc.
  - UI notes:
    - Use `type="tel"` and show a helper text: "Enter phone as 07xx xxx xxx or +2547xxxxxxxx — we'll normalize it for M-Pesa".
    - Client-side JS should strip non-digits and either prepend `0` when user typed '7...' or convert to `254` when user typed '07...'. Server-side must re-normalize.

- **6) Generate an actual Scan-to-Pay QR code in the application form**
  - Why: Current QR reference points to a static `api.qrserver.com` call with only the Paybill, not tuned for M-Pesa scan-to-pay workflows (and not tied to amount/account).
  - Files to change:
    - `resources/views/auth/register.php` (sidebar) — replace the static `img` URL with a generated QR endpoint served by the app.
    - New utility/route: `public/qr.php` or internal route `GET /qr/pay` which accepts `paybill`, `account`, `amount` and returns an image/png.
    - Add a small helper service: `app/services/QrService.php`.
  - Implementation suggestions:
    - Use Composer package `endroid/qr-code` (`composer require endroid/qr-code`) or `chillerlan/php-qrcode` to generate PNG QR payload.
    - Generate a QR payload that follows the MPESA or generic payment QR format (if using a specific format like Merchant-Payments, follow their spec). Alternatively, for Paybill: encode a simple URL or text like `paybill:4163987|acc:IDNUMBER|amount:500` and make the QR scanner show instructions.
    - Example generation snippet:
      - Add `composer require endroid/qr-code`
      - New route `GET /qr/pay?paybill=4163987&account=ID123&amount=2000`
      - Controller generates PNG and streams `image/png`.

- **7) Compare and adopt the SHA form capture (minimalist)**
  - Why: The user requested to compare and adopt the SHA form style to reduce friction.
  - Files to inspect and adapt:
    - `modal-demo.html` (repository root) — may contain the SHA-like demo form.
    - `resources/views/auth/register.php` — reduce fields, group steps, minimize cognitive load.
  - Suggested changes:
    - Keep step 1 minimal: name, ID, phone (required), DOB (or age), address (optional), package choice.
    - Make email optional (per above).
    - Move secondary opt-in details to optional step or modal.
    - Show instant contribution preview on the right with breakdown.

- **8) Verify SMS & Email services, diagnose and fix**
  - SMS (HostPinnacle):
    - File: `app/services/SmsService.php` — this already includes `formatPhoneNumber()` and `sendSms()` via HostPinnacle endpoint.
    - Steps to verify:
      1. Ensure constants `HOSTPINNACLE_USER_ID`, `HOSTPINNACLE_API_KEY`, `HOSTPINNACLE_SENDER_ID` are set in `.env` and `config/config.php`.
      2. Run a small CLI test script (or `php -r`) calling `SmsService::sendSms('+2547XXXXXXXX', 'Test')` and check response and logs.
      3. Check `error_log()` output and `notification_logs` table for failures.
      4. If HostPinnacle API schema changed, update parameter names and endpoint accordingly.
  - Email (SMTP):
    - File: `app/services/EmailService.php` — currently uses `mail()` and `ini_set()` which may not work on many hosts.
    - Recommended change:
      - Install and use a robust mailer like `phpmailer/phpmailer` via Composer and configure SMTP credentials from `.env`.
      - Update `EmailService::sendEmail` to use PHPMailer with proper authentication and TLS.
    - Testing:
      - Use your own SMTP credentials (set `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_EMAIL`, `MAIL_FROM_NAME`) in `.env` (local developer/test only). Send test emails and check `notification_logs` and SMTP logs.

**Files Affected (explicit list & suggested edits)**
- UI / Views
  - `resources/views/auth/register.php` — remove `required` from email, add DOB input where appropriate, show generated QR image (replace static QR `img src`), normalize phone input client-side and update helper text.
  - `resources/views/member/beneficiaries.php` — add `date_of_birth` field to Add/Edit modals, normalize phone input, display age bracket; show contribution recalculation preview on add.
  - `resources/views/member/upgrade.php` — call `calculateMonthlyContribution` to compute `prorated_amount`, show clear breakdown, validate chosen package against dependents.
  - `modal-demo.html` — review and borrow minimalist patterns.

- Models and Services
  - `app/models/Beneficiary.php` — accept `date_of_birth` in `addBeneficiary()`, compute and store age/age_bracket if desired.
  - `app/models/Member.php` — add `calculateMonthlyContribution()` and reuse it in registration/upgrade flows; ensure `calculateAge()` is available and used.
  - `app/services/SmsService.php` — ensure credentials come from config and `formatPhoneNumber()` is used consistently.
  - `app/services/EmailService.php` — replace `mail()` approach with PHPMailer or similar. Update logging to match `notification_logs` columns.
  - New service: `app/services/QrService.php` — wrapper around `endroid/qr-code` or `chillerlan/php-qrcode`.

- Controllers
  - `app/controllers/AuthController.php` — registration step handlers: compute contribution live, make email optional, validate phone, write normalized phone into `users` and `members`.
  - `app/controllers/MemberController.php` — beneficiary add/edit endpoints: accept DOB, recalc and persist monthly contribution; return JSON for UI preview.
  - `app/controllers/PaymentController.php` — implement route to return generated QR image or a signed temporary URL.
  - `app/controllers/SettingsController.php` or `app/services/SettingsService.php` — optional: expose package rules to UI.

- Database
  - `database/migrations/010_add_beneficiary_dob.sql` (new)
  - `database/schema.sql` — update to include `date_of_birth` in `beneficiaries`.

**Suggested Implementation Steps (developer checklist)**
1. Create and run DB migration adding `date_of_birth` to `beneficiaries`.
2. Update `Beneficiary` model to accept DOB, validate, and store.
3. Add `date_of_birth` fields to beneficiary Add/Edit modals and client-side validation.
4. Add `Member::calculateMonthlyContribution()` using a package configuration file `config/packages.php` that defines base fees & incremental brackets.
5. Update registration flow to call the calculation when packages or dependents change; show breakdown on UI and update `members.monthly_contribution` only after registration is complete or after upgrade payment.
6. Update upgrade flow (`plan_upgrade_requests`) to store `current_monthly_fee` and `new_monthly_fee` returned by the new calculation function.
7. Remove `required` from `email` field in `resources/views/auth/register.php` and handle optional email on server.
8. Normalize phone numbers on server before persisting; update `users.phone`, `members.next_of_kin_phone`, `beneficiaries.phone_number`.
9. Add QR generation route and `QrService` using `endroid/qr-code` and update registration sidebar to use dynamic QR endpoint.
10. Replace `EmailService` internals to use PHPMailer and test with valid SMTP credentials.
11. Test SMS by sending a test SMS via `SmsService::sendSms()` and check responses. If HostPinnacle credentials are valid but API contract changed, update `SmsService` accordingly.
12. Write unit/integration tests for `calculateMonthlyContribution()` and a small e2e for registration+first-payment flow (can be manual QA initially).

**Diagnostics & Quick Tests**
- SMS quick test (run in app context):
  ```php
  $sms = new SmsService();
  var_dump($sms->sendSms('0712345678', 'Test SMS from Shena')); // expects success true
  ```
- Email quick test using PHPMailer (after updating `EmailService`):
  ```php
  $email = new EmailService();
  $email->sendEmail('you@example.com', 'Test', '<p>Hello</p>');
  ```
- QR generation test (after implementing qr route):
  - Visit `/qr/pay?paybill=4163987&account=ID123&amount=500` and confirm image stream.

**Notes on Safety & Production Readiness**
- Secrets: Confirm that SMTP, HostPinnacle, and Mpesa keys are stored in `.env` and not committed. Use `.env.example` as template.
- Logging: Ensure production logging does not leak secrets (avoid logging full SMTP passwords or API keys).
- Rate limits: When sending bulk SMS or emails, implement rate-limit/backoff and monitor failures in `notification_logs`.
- Tests: Add automated tests for the new contribution calculation logic. Run DB migration on a staging copy and exercise registration + payment flows.
- Backups: Take DB backup before running migrations.

**Deployment Checklist (pre-release)**
- [ ] Add DB migration files and run on staging.
- [ ] Update code per files listed above.
- [ ] Add `composer require endroid/qr-code phpmailer/phpmailer` and run `composer install` on server.
- [ ] Configure `.env` with SMTP and HostPinnacle keys (and Mpesa Daraja credentials).
- [ ] Confirm SMS and Email tests pass using test credentials.
- [ ] Test STK Push and MPesa callback flows using sandbox credentials.
- [ ] QA forms on desktop and mobile; ensure registration flow is clear and minimal.
- [ ] Run migration and smoke tests on production (or as part of CI/CD).
- [ ] Verify `notification_logs` show no fatal errors after a controlled send.

**Acceptance Criteria (How we know it's done)**
- Adding a new beneficiary with a DOB causes the `monthly_contribution` to update according to configured rules.
- Plan upgrade previews show correct `prorated_amount` and `new_monthly_fee` computed server-side.
- The registration form no longer fails if `email` is missing.
- Phone fields are stored in normalized `2547XXXXXXXX` format and SMS/MPesa prompts work.
- QR image renders on the registration form and encodes paybill/account/amount info.
- SMS sends succeed with HostPinnacle credentials; Email sending succeeds via SMTP using PHPMailer.
- QA checklist is complete and staging tests pass; documentation and migrations are included in the PR.

**Recommended Next Steps (for the PR / GH issue)**
- Create a separate PR to add DB migration and model changes (small atomic commits):
  1. Migration: add `date_of_birth` to `beneficiaries`.
  2. Model changes: `Beneficiary.php` and `Member.php` update to add calculation logic.
  3. UI changes: `register.php`, `beneficiaries.php`, `upgrade.php`.
  4. Service changes: `QrService`, `EmailService` (PHPMailer), small updates to `SmsService` if needed.
  5. Tests and README updates (how to configure `.env` for SMTP and HostPinnacle).

If you want, I can open a PR with the DB migration + model skeleton and update the registration & beneficiary views to include the DOB and phone-normalization UI changes. Which step should I start implementing first? (I recommend starting with the DB migration and model changes.)
