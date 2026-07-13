<?php
$notifications = $notifications ?? [];
$unreadCount = $unreadCount ?? 0;
$totalCount = $totalCount ?? 0;
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
@keyframes fadeIn { from { opacity:0 } to { opacity:1 } }
@keyframes slideDown { from { opacity:0; transform:translateY(-8px) } to { opacity:1; transform:translateY(0) } }
@keyframes pulse { 0%,100% { opacity:1 } 50% { opacity:0.5 } }
.animate-in { animation:fadeInUp 0.35s cubic-bezier(0.34,1.56,0.64,1) both; }
.d1 { animation-delay:0.04s } .d2 { animation-delay:0.08s } .d3 { animation-delay:0.12s }
.d4 { animation-delay:0.16s } .d5 { animation-delay:0.20s }
.hover-lift { transition:transform 0.2s,box-shadow 0.2s; }
.hover-lift:hover { transform:translateY(-2px); box-shadow:0 8px 25px -6px rgba(0,0,0,0.08); }
.tab-active { color:#fff !important; background:linear-gradient(135deg,#6366F1,#4F46E5) !important; box-shadow:0 2px 8px rgba(99,102,241,0.3); }
.pulse-dot { animation:pulse 2s ease-in-out infinite; }
</style>

<div class="space-y-6" style="background:var(--bg-page);min-height:100vh">

<div class="animate-in flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Notifications</h1>
        <p class="mt-1 text-sm text-slate-500"><?= $unreadCount ?> unread notification<?= $unreadCount === 1 ? '' : 's' ?></p>
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
            <div class="relative flex-1 min-w-0">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search notifications..."
                       class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-4 text-sm text-slate-700 outline-none transition-all duration-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
            </div>
            <select name="type" onchange="this.form.submit()"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition-all duration-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                <option value="">All Types</option>
                <option value="assessment" <?= $type === 'assessment' ? 'selected' : '' ?>>Assessment</option>
                <option value="user" <?= $type === 'user' ? 'selected' : '' ?>>User</option>
                <option value="career" <?= $type === 'career' ? 'selected' : '' ?>>Career</option>
                <option value="question" <?= $type === 'question' ? 'selected' : '' ?>>Question</option>
                <option value="system" <?= $type === 'system' ? 'selected' : '' ?>>System</option>
            </select>
            <?php if ($search !== '' || $type !== ''): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin-notifications"
               class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 transition-all duration-200 hover:bg-slate-50">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="flex border-b border-slate-100">
        <a href="<?= BASE_URL ?>/index.php?page=admin-notifications&tab=all<?= $type ? '&type='.urlencode($type) : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>"
           class="relative px-5 py-3 text-xs font-semibold transition-all duration-200 no-underline <?= $tab === 'all' ? 'tab-active text-white' : 'text-slate-500 hover:text-slate-800' ?>">
            All (<?= $totalCount ?>)
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=admin-notifications&tab=unread<?= $type ? '&type='.urlencode($type) : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>"
           class="relative px-5 py-3 text-xs font-semibold transition-all duration-200 no-underline <?= $tab === 'unread' ? 'tab-active text-white' : 'text-slate-500 hover:text-slate-800' ?>">
            Unread (<?= $unreadCount ?>)
        </a>
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
        <?php else: ?>
        <div class="divide-y divide-slate-50">
            <?php foreach ($notifications as $n): ?>
            <?php
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
            <div class="notification-item px-5 py-4 transition-all duration-150 <?= $nIsRead ? 'bg-white' : 'bg-indigo-50/30' ?> hover:bg-slate-50" data-id="<?= $nid ?>">
                <div class="flex items-start gap-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl <?= $iconInfo['bg'] ?> <?= $iconInfo['color'] ?>">
                        <i class="bi <?= $iconInfo['icon'] ?> text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm font-semibold <?= $nIsRead ? 'text-slate-700' : 'text-slate-900' ?>"><?= $nTitle ?></p>
                                <?php if ($nMessage): ?>
                                <p class="mt-0.5 text-xs text-slate-500 line-clamp-2"><?= $nMessage ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="shrink-0 text-xs text-slate-400 whitespace-nowrap"><?= $relStr ?></span>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <?php if ($nLink): ?>
                            <a href="<?= BASE_URL . htmlspecialchars($nLink) ?>" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 no-underline">View details</a>
                            <?php endif; ?>
                            <?php if (!$nIsRead): ?>
                            <button class="mark-read-btn text-xs font-medium text-emerald-600 hover:text-emerald-700 bg-transparent border-0 p-0 cursor-pointer transition-colors">Mark read</button>
                            <?php endif; ?>
                            <button class="delete-btn text-xs font-medium text-red-500 hover:text-red-600 bg-transparent border-0 p-0 cursor-pointer transition-colors">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
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
        var target = e.target;

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
                    item.classList.remove('bg-indigo-50/30');
                    item.classList.add('bg-white');
                    if (target) target.style.display = 'none';
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
                    var unreadCountEl = document.querySelector('.notification-item.bg-indigo-50\\/30, .notification-item.bg-indigo-50\\/30');
                    if (!document.querySelectorAll('.notification-item.bg-indigo-50\\/30, .notification-item[class*=\"bg-indigo-50\"]').length) {
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
                    document.querySelectorAll('.notification-item.bg-indigo-50\\/30').forEach(function(item) {
                        item.classList.remove('bg-indigo-50/30');
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
