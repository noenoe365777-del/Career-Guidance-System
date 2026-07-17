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

        if ($tab === 'unread') {
            $notifications = $this->notificationService->getUnread($perPage, $offset);
            $totalCount = $this->notificationService->getUnreadCount();
        } else {
            $notifications = $this->notificationService->getAll($perPage, $offset, $type, $search);
            $totalCount = $this->notificationService->getTotalCount($type, $search !== '' ? $search : null);
        }
        $unreadCount = $this->notificationService->getUnreadCount();
        $todayCount = $this->notificationService->getTodayCount();

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
                'todayCount' => $todayCount,
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

    public function apiList(): void
    {
        AdminAuthMiddleware::requireAdmin();

        $limit = max(1, min(20, (int)($_GET['limit'] ?? 10)));
        $type = $_GET['type'] ?? null;
        $onlyUnread = isset($_GET['unread']) && $_GET['unread'] === '1';
        $search = '';

        $items = $this->notificationService->getAll($limit, 0, $type !== '' ? $type : null, $search !== '' ? $search : null);

        if ($onlyUnread) {
            $items = array_filter($items, fn($n) => (int)($n['is_read'] ?? 0) === 0);
        }

        $now = new \DateTime();
        $list = array_map(function ($n) use ($now) {
            $created = new \DateTime((string)($n['created_at'] ?? 'now'));
            $diff = $now->getTimestamp() - $created->getTimestamp();
            if ($diff < 60) {
                $timeAgo = 'just now';
            } elseif ($diff < 3600) {
                $timeAgo = floor($diff / 60) . 'm ago';
            } elseif ($diff < 86400) {
                $timeAgo = floor($diff / 3600) . 'h ago';
            } elseif ($diff < 604800) {
                $timeAgo = floor($diff / 86400) . 'd ago';
            } else {
                $timeAgo = $created->format('M j');
            }
            return [
                'id' => (int)($n['id'] ?? 0),
                'type' => (string)($n['type'] ?? 'system'),
                'title' => (string)($n['title'] ?? ''),
                'message' => (string)($n['message'] ?? ''),
                'link' => (string)($n['link'] ?? ''),
                'is_read' => (int)($n['is_read'] ?? 0),
                'created_at' => (string)($n['created_at'] ?? ''),
                'time_ago' => $timeAgo,
            ];
        }, array_values($items));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'unread_count' => $this->notificationService->getUnreadCount(),
            'notifications' => $list,
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
