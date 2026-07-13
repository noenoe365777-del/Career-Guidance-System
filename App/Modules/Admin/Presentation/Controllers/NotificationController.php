<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\NotificationService;
use App\Shared\Core\Controller;

class NotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(?NotificationService $notificationService = null)
    {
        $this->notificationService = $notificationService ?? new NotificationService();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_reports');

        $tab = $_GET['tab'] ?? 'all';
        $type = $_GET['type'] ?? null;
        $search = trim((string)($_GET['search'] ?? ''));
        $page = max(1, (int)($_GET['p'] ?? 1));
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        $notifications = $tab === 'unread'
            ? $this->notificationService->getAll($perPage, $offset, $type, $search)
            : $this->notificationService->getAll($perPage, $offset, $type, $search);

        $totalCount = $this->notificationService->getTotalCount($type, $search !== '' ? $search : null);
        $unreadCount = $this->notificationService->getUnreadCount();

        $totalPages = max(1, (int)ceil($totalCount / $perPage));

        if ($search !== '') {
            $tab = 'all';
        }

        $this->view(
            'Admin/Presentation/Views/notifications/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Notifications',
                'activeMenu' => 'notifications',
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
                'totalCount' => $totalCount,
                'tab' => $tab,
                'type' => $type ?? '',
                'search' => $search,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
            ]
        );
    }

    public function apiUnreadCount(): void
    {
        AdminAuthMiddleware::requireAdmin();

        header('Content-Type: application/json');
        echo json_encode([
            'unread_count' => $this->notificationService->getUnreadCount(),
        ]);
    }

    public function apiMarkAsRead(): void
    {
        AdminAuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $success = $id > 0 && $this->notificationService->markAsRead($id);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'unread_count' => $this->notificationService->getUnreadCount(),
        ]);
    }

    public function apiMarkAllAsRead(): void
    {
        AdminAuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $success = $this->notificationService->markAllAsRead();

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'unread_count' => 0,
        ]);
    }

    public function apiDelete(): void
    {
        AdminAuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $success = $id > 0 && $this->notificationService->delete($id);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'unread_count' => $this->notificationService->getUnreadCount(),
        ]);
    }
}
