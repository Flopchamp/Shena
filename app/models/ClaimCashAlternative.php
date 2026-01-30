<?php
/**
 * Claim Cash Alternative Agreement Model
 * Manages KSH 20,000 cash alternative payments per policy Section 12
 */
class ClaimCashAlternative extends BaseModel 
{
    protected $table = 'claim_cash_alternative_agreements';
    
    /**
     * Create cash alternative agreement
     */
    public function createAgreement($data)
    {
        $required = ['claim_id', 'reason_category', 'detailed_reason', 'requested_by', 'approved_by'];
        
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
        
        // Set default amount per policy
        if (!isset($data['amount_paid'])) {
            $data['amount_paid'] = 20000.00;
        }
        
        return $this->create($data);
    }
    
    /**
     * Get agreement by claim ID
     */
    public function getByClaimId($claimId)
    {
        $sql = "SELECT ca.*, u.first_name, u.last_name 
                FROM {$this->table} ca
                JOIN users u ON ca.approved_by = u.id
                WHERE ca.claim_id = :claim_id";
        
        return $this->db->fetch($sql, ['claim_id' => $claimId]);
    }
    
    /**
     * Mark agreement as signed
     */
    public function markAgreementSigned($claimId, $documentPath = null)
    {
        $data = [
            'agreement_signed' => true
        ];
        
        if ($documentPath) {
            $data['signature_document_path'] = $documentPath;
        }
        
        $sql = "UPDATE {$this->table} 
                SET agreement_signed = :agreement_signed" .
               ($documentPath ? ", signature_document_path = :document_path" : "") .
               " WHERE claim_id = :claim_id";
        
        $params = ['agreement_signed' => true, 'claim_id' => $claimId];
        if ($documentPath) {
            $params['document_path'] = $documentPath;
        }
        
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Record payment
     */
    public function recordPayment($claimId, $paymentMethod, $paymentReference)
    {
        $sql = "UPDATE {$this->table} 
                SET payment_method = :payment_method,
                    payment_reference = :payment_reference,
                    paid_at = NOW()
                WHERE claim_id = :claim_id";
        
        return $this->db->execute($sql, [
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'claim_id' => $claimId
        ]);
    }
    
    /**
     * Validate cash alternative request
     * Per policy Section 12: Both parties must agree
     */
    public function validateRequest($claimId, $reason, $requestedBy)
    {
        $errors = [];
        
        // Validate reason is substantial
        if (strlen($reason) < 20) {
            $errors[] = "Detailed reason must be at least 20 characters";
        }
        
        // Check if claim exists and is eligible
        $claimModel = new Claim();
        $claim = $claimModel->find($claimId);
        
        if (!$claim) {
            $errors[] = "Claim not found";
        } elseif ($claim['status'] === 'rejected') {
            $errors[] = "Cannot process cash alternative for rejected claim";
        } elseif ($claim['service_delivery_type'] === 'cash_alternative') {
            $errors[] = "Cash alternative already approved for this claim";
        }
        
        return empty($errors) ? true : $errors;
    }
}
