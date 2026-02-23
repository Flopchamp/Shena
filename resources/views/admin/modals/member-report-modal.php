<!-- Member Report Modal -->
<div class="modal fade" id="memberReportModal" tabindex="-1" aria-labelledby="memberReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="memberReportModalLabel">Select Member for Payment History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control mb-3" id="memberSearchInput" placeholder="Search by name, number, email, or phone...">
        <div id="memberListContainer" style="max-height: 350px; overflow-y: auto;">
          <!-- Member list will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
