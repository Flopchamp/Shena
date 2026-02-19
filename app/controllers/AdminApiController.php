<?php
/**
 * Admin API Controller for AJAX endpoints
 */
class AdminApiController extends BaseController
{
    /**
     * Require admin access (super_admin or manager)
     */
    private function requireAdminAccess()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ||
            !in_array($_SESSION['user_role'], ['super_admin', 'manager'])) {
            header('Location: /admin-login');
            exit();
        }
    }
    /**
     * Return a list of members for search/filter (AJAX)
     * GET /admin/api/members?search=...
     */
    public function members()
    {
        $this->requireAdminAccess();
        $search = $_GET['search'] ?? '';
        require_once __DIR__ . '/../models/Member.php';
        $memberModel = new Member();
        $members = $memberModel->getAllMembers(['search' => $search, 'status' => 'active']);
        $result = array_map(function($m) {
            return [
                'id' => $m['id'],
                'member_number' => $m['member_number'],
                'first_name' => $m['first_name'],
                'last_name' => $m['last_name'],
                'email' => $m['email'],
                'phone' => $m['phone']
            ];
        }, $members);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
