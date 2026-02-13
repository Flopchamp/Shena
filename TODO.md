# TODO: Disable Claim Processing and Assist Payment Buttons

## Tasks
- [x] Add 'disabled' attribute to both "Initiate Claim" and "Assist Payment" buttons
- [x] Remove data-bs-toggle and data-bs-target attributes from both buttons
- [x] Add onclick attribute to "Initiate Claim" button to show alert: "Claim processing is only handled by admins."
- [x] Add onclick attribute to "Assist Payment" button to show alert: "Payment assistance is only handled by admins."
- [ ] Update CSS for .btn-initiate-claim and .btn-assist-payment to style disabled state (opacity: 0.6, cursor: not-allowed)
- [ ] Test the disabled buttons and alert messages
