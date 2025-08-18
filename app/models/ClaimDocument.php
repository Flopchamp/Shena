<?php
/**
 * ClaimDocument Model - Handles claim document uploads and management
 */
class ClaimDocument extends BaseModel 
{
    protected $table = 'claim_documents';
    
    public function getClaimDocuments($claimId)
    {
        return $this->findAll(['claim_id' => $claimId], 'created_at ASC');
    }
    
    public function addDocument($data)
    {
        $documentData = [
            'claim_id' => $data['claim_id'],
            'document_type' => $data['document_type'], // id_copy, birth_certificate, chief_letter, mortuary_invoice, death_certificate
            'file_name' => $data['file_name'],
            'file_path' => $data['file_path'],
            'file_size' => $data['file_size'],
            'mime_type' => $data['mime_type'],
            'uploaded_by' => $data['uploaded_by'] ?? null
        ];
        
        return $this->create($documentData);
    }
    
    public function getDocumentsByType($claimId, $documentType)
    {
        return $this->findAll([
            'claim_id' => $claimId,
            'document_type' => $documentType
        ]);
    }
    
    public function deleteDocument($documentId)
    {
        // Get document info first to delete physical file
        $document = $this->find($documentId);
        
        if ($document && file_exists($document['file_path'])) {
            unlink($document['file_path']);
        }
        
        return $this->delete($documentId);
    }
    
    public function getRequiredDocuments()
    {
        return [
            'id_copy' => 'Copy of ID/Birth Certificate',
            'death_certificate' => 'Death Certificate',
            'chief_letter' => "Chief's Letter",
            'mortuary_invoice' => 'Mortuary Invoice'
        ];
    }
    
    public function checkClaimDocumentCompleteness($claimId)
    {
        $required = array_keys($this->getRequiredDocuments());
        $uploaded = [];
        
        $documents = $this->getClaimDocuments($claimId);
        foreach ($documents as $doc) {
            $uploaded[] = $doc['document_type'];
        }
        
        $missing = array_diff($required, $uploaded);
        
        return [
            'complete' => empty($missing),
            'missing' => $missing,
            'uploaded' => $uploaded
        ];
    }
}
