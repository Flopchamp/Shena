# TODO: Implement Transaction Details Modal and Fix CSV Export

## Tasks
- [ ] Add transaction details modal to resources/views/member/payments.php
- [ ] Make payment table rows clickable to open modal
- [ ] Add "Download Receipt PDF" button in modal
- [ ] Add JavaScript to populate modal with payment data
- [ ] Add new method exportPaymentReceiptPdf() in app/controllers/MemberController.php
- [ ] Style PDF receipt with Shena logo as letterhead
- [ ] Add route for receipt PDF download in app/core/Router.php
- [ ] Fix CSV export column arrangement in exportPaymentHistory()
