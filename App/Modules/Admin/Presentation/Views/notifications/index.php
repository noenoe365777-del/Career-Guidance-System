<?php
$notifications = $notifications ?? [];
$unreadCount = $unreadCount ?? 0;
$totalCount = $totalCount ?? 0;
$todayCount = $todayCount ?? 0;
$tab = $tab ?? 'all';
$type = $type ?? '';
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$pageTitle = 'Notifications';
$activeMenu = 'notifications';
$perPage = $perPage ?? 15;
ob_start();
?>

<style>
:root { --primary: #6366F1; --primary-light: #818CF8; --primary-dark: #4F46E5; --bg-page: #F8FAFC; --radius: 16px; }
@keyframes fadeInUp { from { opacity:0; transform:translateY(12px) } to { opacity:1; transform:translateY(0) } }
@keyframes slideDown { from { opacity:0; transform:translateY(-8px) } to { opacity:1; transform:translateY(0) } }
.animate-in { animation:fadeInUp 0.35s cubic-bezier(0.34,1.56,0.64,1) both; }
.d1 { animation-delay:0.04s } .d2 { animation-delay:0.08s } .d3 { animation-delay:0.12s }
.d4 { animation-delay:0.16s } .d5 { animation-delay:0.20s }
.chip { transition:all 0.2s cubic-bezier(0.34,1.56,0.64,1); }
.chip-active { background:#6366F1 !important; color:#fff !important; box-shadow:0 2px 8px rgba(99,102,241,0.3); }
.chip-inactive { background:#fff !important; color:#64748B !important; border-color:#E2E8F0 !important; }
.chip-inactive:hover { background:#F1F5F9 !important; }
.date-header { font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#94A3B8; padding:0.75rem 1.25rem; background:#FAFBFC; border-bottom:1px solid #F1F5F9; }
</style>

<div class="space-y-6" style="background:var(--bg-page);min-height:100vh">

<div class="animate-in flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Notifications</h1>
        <p class="mt-1 text-sm text-slate-500"><?= $todayCount ?> new today, <?= $unreadCount ?> unread</p>
    </div>
    <div class="flex items-center gap-2">
        <button id="markAllReadBtn"
                class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-50 px-3.5 py-2 text-xs font-semibold text-indigo-600 transition-all duration-200 hover:bg-indigo-100 active:scale-[0.97] <?= $unreadCount === 0 ? 'opacity-50 pointer-events-none' : '' ?>">
            <i class="bi bi-check2-all"></i> Mark All as Read
        </button>
    </div>
</div>

<div class="animate-in d1 rounded-[var(--radius)] bg-white shadow-[0_1px_3px_rgba(0,0,0,0.06)] overflow-hidden">
    <div class="p-4 sm:p-5 border-b border-slate-100">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <input type="hidden" name="page" value="admin-notifications">
            <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
            <div class="relative flex-1 min-w-0">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search notifications..."
                       class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-4 text-sm text-slate-700 outline-none transition-all duration-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
            </div>
            <?php if ($search !== '' || $type !== ''): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-notifications"
               class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 transition-all duration-200 hover:bg-slate-50">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="px-4 sm:px-5 py-3 border-b border-slate-100 flex flex-wrap items-center gap-2">
        <?php
        $chips = [
            'all' => ['label' => 'All', 'count' => $totalCount],
            'unread' => ['label' => 'Unread', 'count' => $unreadCount],
            'question' => ['label' => 'Questions', 'icon' => 'bi-question-circle'],
            'assessment' => ['label' => 'Assessments', 'icon' => 'bi-clipboard-check'],
            'user' => ['label' => 'Users', 'icon' => 'bi-person-plus'],
            'career' => ['label' => 'Careers', 'icon' => 'bi-briefcase'],
            'system' => ['label' => 'System', 'icon' => 'bi-gear'],
        ];
        foreach ($chips as $key => $chip):
            $isActive = ($key === 'all' && $tab === 'all' && $type === '') || ($key === 'unread' && $tab === 'unread') || ($key !== 'all' && $key !== 'unread' && $type === $key);
            $href = $key === 'all' ? BASE_URL . '/index.php?page=admin-notifications' : ($key === 'unread' ? BASE_URL . '/index.php?page=admin-notifications&tab=unread' : BASE_URL . '/index.php?page=admin-notifications&type=' . urlencode($key));
            $icon = $chip['icon'] ?? null;
        ?>
        <a href="<?= $href ?>"
           class="chip no-underline inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-semibold <?= $isActive ? 'chip-active' : 'chip-inactive' ?>">
            <?php if ($icon): ?><i class="bi <?= $icon ?>"></i><?php endif; ?>
            <?= htmlspecialchars($chip['label']) ?>
            <?php if (isset($chip['count'])): ?><span class="ml-0.5">(<?= $chip['count'] ?>)</span><?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>

    <div>
        <?php if (empty($notifications)): ?>
        <div class="flex flex-col items-center justify-center py-16">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50">
                <i class="bi bi-bell-slash text-2xl text-slate-300"></i>
            </div>
            <h3 class="mt-4 text-base font-semibold text-slate-700">No notifications</h3>
            <p class="mt-1 text-sm text-slate-400"><?= $tab === 'unread' ? 'All caught up!' : 'No notifications match your filters.' ?></p>
        </div>
        <?php else:
            $todayStart = strtotime('today');
            $yesterdayStart = strtotime('yesterday');
            $groups = ['today' => [], 'yesterday' => [], 'earlier' => []];
            foreach ($notifications as $n) {
                $ts = strtotime((string)($n['created_at'] ?? ''));
                if ($ts >= $todayStart) $groups['today'][] = $n;
                elseif ($ts >= $yesterdayStart) $groups['yesterday'][] = $n;
                else $groups['earlier'][] = $n;
            }
            $groupLabels = ['today' => 'Today', 'yesterday' => 'Yesterday', 'earlier' => 'Earlier'];
            $firstGroup = true;
            foreach (['today', 'yesterday', 'earlier'] as $gk):
                if (empty($groups[$gk])) continue;
                if (!$firstGroup) echo '<div class="border-t border-slate-100"></div>';
                $firstGroup = false;
        ?>
        <div class="date-header"><?= $groupLabels[$gk] ?></div>
        <div class="divide-y divide-slate-50">
            <?php foreach ($groups[$gk] as $n):
                $nid = (int)$n['id'];
                $nType = $n['type'] ?? 'system';
                $nTitle = htmlspecialchars((string)($n['title'] ?? ''), ENT_QUOTES, 'UTF-8');
                $nMessage = htmlspecialchars((string)($n['message'] ?? ''), ENT_QUOTES, 'UTF-8');
                $nLink = $n['link'] ?? null;
                $nIsRead = (int)($n['is_read'] ?? 0);
                $nCreated = $n['created_at'] ?? '';
                $ts = strtotime((string)$nCreated);
                $relative = $ts ? time() - $ts : 0;
                if ($relative < 60) $relStr = 'Just now';
                elseif ($relative < 3600) $relStr = floor($relative / 60) . 'm ago';
                elseif ($relative < 86400) $relStr = floor($relative / 3600) . 'h ago';
                elseif ($relative < 604800) $relStr = floor($relative / 86400) . 'd ago';
                else $relStr = date('M j', $ts);

                $typeIconMap = [
                    'assessment' => ['icon' => 'bi-clipboard-check', 'bg' => 'bg-indigo-50', 'color' => 'text-indigo-600'],
                    'user' => ['icon' => 'bi-person-plus', 'bg' => 'bg-emerald-50', 'color' => 'text-emerald-600'],
                    'career' => ['icon' => 'bi-briefcase', 'bg' => 'bg-amber-50', 'color' => 'text-amber-600'],
                    'question' => ['icon' => 'bi-question-circle', 'bg' => 'bg-cyan-50', 'color' => 'text-cyan-600'],
                    'system' => ['icon' => 'bi-gear', 'bg' => 'bg-purple-50', 'color' => 'text-purple-600'],
                ];
                $iconInfo = $typeIconMap[$nType] ?? ['icon' => 'bi-bell', 'bg' => 'bg-slate-50', 'color' => 'text-slate-500'];
            ?>
            <div class="notification-item transition-all duration-150 <?= $nIsRead ? 'bg-white' : 'bg-purple-50/30' ?> hover:bg-slate-50 <?= $nIsRead ? '' : 'border-l-4 border-l-purple-500' ?>" data-id="<?= $nid ?>" style="<?= $nIsRead ? '' : '' ?>">
                <div class="flex items-start gap-3.5 px-5 py-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl <?= $iconInfo['bg'] ?> <?= $iconInfo['color'] ?>">
                        <i class="bi <?= $iconInfo['icon'] ?> text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm <?= $nIsRead ? 'font-medium text-slate-700' : 'font-bold text-slate-900' ?>"><?= $nTitle ?></p>
                                <?php if ($nMessage): ?>
                                <p class="mt-0.5 text-xs text-slate-500 line-clamp-2"><?= $nMessage ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="shrink-0 text-xs text-slate-400 whitespace-nowrap"><?= $relStr ?></span>
                        </div>
                        <div class="mt-2 flex items-center gap-3">
                            <?php if ($nLink): ?>
                            <a href="<?= BASE_URL . htmlspecialchars($nLink) ?>" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 no-underline flex items-center gap-1"><i class="bi bi-box-arrow-up-right"></i> View</a>
                            <?php endif; ?>
                            <?php if (!$nIsRead): ?>
                            <button class="mark-read-btn text-xs font-medium text-emerald-600 hover:text-emerald-700 bg-transparent border-0 p-0 cursor-pointer transition-colors flex items-center gap-1"><i class="bi bi-check-circle"></i> Mark read</button>
                            <?php endif; ?>
                            <button class="delete-btn text-xs font-medium text-red-500 hover:text-red-600 bg-transparent border-0 p-0 cursor-pointer transition-colors flex items-center gap-1"><i class="bi bi-trash3"></i> Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3">
        <p class="text-xs text-slate-500">Page <?= $currentPage ?> of <?= $totalPages ?></p>
        <div class="flex items-center gap-1">
            <?php if ($currentPage > 1): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-notifications&tab=<?= urlencode($tab) ?>&p=<?= $currentPage - 1 ?><?= $type ? '&type='.urlencode($type) : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>"
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-xs font-medium text-slate-600 transition-all duration-150 hover:bg-slate-100 no-underline">
                <i class="bi bi-chevron-left"></i>
            </a>
            <?php endif; ?>
            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-notifications&tab=<?= urlencode($tab) ?>&p=<?= $i ?><?= $type ? '&type='.urlencode($type) : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>"
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-xs font-medium no-underline transition-all duration-150 <?= $i === $currentPage ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-notifications&tab=<?= urlencode($tab) ?>&p=<?= $currentPage + 1 ?><?= $type ? '&type='.urlencode($type) : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>"
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-xs font-medium text-slate-600 transition-all duration-150 hover:bg-slate-100 no-underline">
                <i class="bi bi-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
</div>

<script>
(function() {
    var baseUrl = '<?= BASE_URL ?>';

    function updateUnreadBadge(count) {
        var badges = document.querySelectorAll('.notification-badge');
        badges.forEach(function(b) {
            b.textContent = count;
            b.style.display = parseInt(count) > 0 ? 'flex' : 'none';
        });
    }

    document.addEventListener('click', function(e) {
        var target = e.target.closest('button');
        if (!target) return;

        if (target.classList.contains('mark-read-btn')) {
            var item = target.closest('.notification-item');
            var id = item ? item.dataset.id : null;
            if (!id) return;
            var form = new FormData();
            form.append('id', id);
            fetch(baseUrl + '/index.php?page=admin-notifications-api-mark-read', {
                method: 'POST', body: form
            }).then(function(r) { return r.json(); }).then(function(data) {
                if (data.success) {
                    item.classList.remove('bg-purple-50/30', 'border-l-4', 'border-l-purple-500');
                    item.classList.add('bg-white');
                    target.style.display = 'none';
                    updateUnreadBadge(data.unread_count);
                }
            });
        }

        if (target.classList.contains('delete-btn')) {
            var item = target.closest('.notification-item');
            var id = item ? item.dataset.id : null;
            if (!id) return;
            var form = new FormData();
            form.append('id', id);
            fetch(baseUrl + '/index.php?page=admin-notifications-api-delete', {
                method: 'POST', body: form
            }).then(function(r) { return r.json(); }).then(function(data) {
                if (data.success) {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(20px)';
                    setTimeout(function() { item.remove(); }, 300);
                    updateUnreadBadge(data.unread_count);
                    if (!document.querySelectorAll('.notification-item.bg-purple-50\\/30').length) {
                        location.reload();
                    }
                }
            });
        }
    });

    var markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch(baseUrl + '/index.php?page=admin-notifications-api-mark-all-read', {
                method: 'POST'
            }).then(function(r) { return r.json(); }).then(function(data) {
                if (data.success) {
                    document.querySelectorAll('.notification-item.bg-purple-50\\/30').forEach(function(item) {
                        item.classList.remove('bg-purple-50/30', 'border-l-4', 'border-l-purple-500');
                        item.classList.add('bg-white');
                    });
                    document.querySelectorAll('.mark-read-btn').forEach(function(btn) {
                        btn.style.display = 'none';
                    });
                    updateUnreadBadge(0);
                    setTimeout(function() { location.reload(); }, 500);
                }
            });
        });
    }
})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
