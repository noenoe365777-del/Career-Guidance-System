<?php
$admin = $admin ?? ($_SESSION['admin'] ?? []);
$rawName = trim((string)($admin['full_name'] ?? $admin['username'] ?? 'Admin'));
$adminName = strtolower($rawName) === 'admin' ? 'Admin' : $rawName;
$adminInitial = strtoupper(substr($adminName ?: 'A', 0, 1));
$breadcrumbLabel = $breadcrumbLabel ?? ($pageTitle ?? 'Dashboard');

$notifUnreadCount = 0;
try {
    $pdo = \App\Config\Database::getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
    $notifUnreadCount = (int)$stmt->fetchColumn();
} catch (\Throwable $e) {}
?>
<style>
    @keyframes headerFadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes breadcrumbFade { from { opacity: 0; transform: translateX(-6px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes dropdownOpen { from { opacity: 0; transform: translateY(-4px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes notifPop { from { opacity: 0; transform: translateY(-8px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .notif-item { transition: background-color 0.15s ease; }
    .notif-item:hover { background: #f8fafc; }
    .notif-item.unread { background: #f5f7ff; }
    .notif-item.unread .notif-title { color: #1e1b4b; font-weight: 700; }
    .notif-dot { width: 8px; height: 8px; border-radius: 9999px; background: #6366F1; flex-shrink: 0; }
    .notif-scroll::-webkit-scrollbar { width: 6px; }
    .notif-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 9999px; }
    .header-anim { animation: headerFadeIn 0.4s ease-out both; }
    .breadcrumb-anim { animation: breadcrumbFade 0.35s ease-out 0.1s both; }
    .dropdown-menu { animation: dropdownOpen 0.2s ease-out both; }
    .icon-hover { transition: all 0.2s ease; }
    .icon-hover:hover { transform: scale(1.12); }
    .icon-hover:active { transform: scale(0.95); }
    .profile-btn { transition: all 0.2s ease; }
    .profile-btn:hover { transform: scale(1.03); }
    .profile-btn:active { transform: scale(0.97); }
    .dropdown-item-link {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.5rem 0.75rem; border-radius: 0.65rem;
        font-size: 0.8rem; font-weight: 500;
        transition: all 0.15s ease; text-decoration: none;
        color: #475569;
    }
    .dropdown-item-link:hover {
        background: #f5f3ff;
        color: #5B5FEF;
        transform: translateX(3px);
    }
    .dropdown-item-link:hover i { color: #5B5FEF; }
    .dropdown-item-link.logout { color: #dc2626; }
    .dropdown-item-link.logout:hover { background: #fef2f2; color: #dc2626; }
    .dropdown-item-link.logout:hover i { color: #dc2626; }
</style>

<nav class="sticky top-0 z-40 bg-white border-b border-slate-100 header-anim">
    <div class="px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3 min-w-0">
            <button class="md:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 border-0 bg-transparent outline-none icon-hover"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#adminSidebarMobile"
                    aria-controls="adminSidebarMobile">
                <i class="bi bi-list text-xl"></i>
            </button>
            <div class="min-w-0 hidden md:block">
                <h1 class="text-base font-bold text-slate-800 tracking-tight m-0 leading-tight">Admin Dashboard</h1>
                <p class="breadcrumb-anim text-xs font-medium text-slate-400 m-0 mt-0.5 flex items-center gap-1">
                    <a href="<?= BASE_URL ?>/index.php?page=home" class="text-slate-400 hover:text-indigo-600 transition-colors no-underline">Home</a>
                    <i class="bi bi-chevron-right text-[9px] opacity-50"></i>
                    <span class="text-slate-500 font-semibold"><?= htmlspecialchars($breadcrumbLabel) ?></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative" id="notifWrapper">
                <button type="button"
                        class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 no-underline outline-none inline-flex items-center justify-center icon-hover"
                        id="notifBell"
                        aria-label="Notifications"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <i class="bi bi-bell text-xl"></i>
                    <span id="notifBadge" class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none shadow-sm ring-2 ring-white <?= $notifUnreadCount > 0 ? '' : 'hidden' ?>"><?= $notifUnreadCount ?></span>
                </button>

                <div id="notifPanel"
                     class="absolute right-0 mt-3 w-[420px] max-w-[calc(100vw-2rem)] bg-white rounded-[20px] shadow-2xl border border-[#E5E7EB] overflow-hidden z-50 origin-top-right"
                     style="display:none; animation: notifPop 0.22s cubic-bezier(0.16,1,0.3,1) both;">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-800 m-0">Notifications</h3>
                        <button type="button" id="notifMarkAll"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors <?= $notifUnreadCount === 0 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <i class="bi bi-check2-all"></i> Mark all as read
                        </button>
                    </div>

                    <div id="notifList" class="notif-scroll max-h-[380px] overflow-y-auto divide-y divide-slate-50">
                        <div class="px-5 py-10 text-center text-sm text-slate-400">
                            <i class="bi bi-arrow-repeat animate-spin block text-lg mb-2"></i> Loading…
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>/index.php?page=admin-notifications"
                       class="block text-center px-5 py-3 text-sm font-semibold text-indigo-600 hover:bg-indigo-50/60 transition-colors border-t border-slate-100 no-underline">
                        View all notifications
                    </a>
                </div>
            </div>

            <div class="relative dropdown">
                <button class="profile-btn flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-50/80 border-0 bg-transparent text-left outline-none"
                        type="button"
                        id="userProfileDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <div class="flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 text-[#5B5FEF] font-bold shrink-0 w-8 h-8 text-xs shadow-sm ring-2 ring-white">
                        <?= htmlspecialchars($adminInitial) ?>
                    </div>
                    <div class="hidden sm:block min-w-0 max-w-[140px]">
                        <p class="text-sm font-semibold text-slate-700 truncate m-0 leading-tight"><?= htmlspecialchars($adminName) ?></p>
                        <p class="text-[10px] font-medium text-slate-400 m-0 leading-tight">Administrator</p>
                    </div>
                    <i class="bi bi-chevron-down text-[9px] text-slate-400 transition-transform duration-200"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end absolute right-0 mt-2 w-52 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 list-none m-0"
                    aria-labelledby="userProfileDropdown">
                    <li>
                        <div class="px-3 py-2.5 border-b border-slate-50 mb-1">
                            <p class="text-sm font-bold text-slate-800 m-0"><?= htmlspecialchars($adminName) ?></p>
                            <p class="text-[11px] text-slate-400 m-0">Administrator</p>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item-link" href="<?= BASE_URL ?>/index.php?page=admin-profile">
                            <i class="bi bi-person text-sm text-slate-400"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-link" href="<?= BASE_URL ?>/index.php?page=home">
                            <i class="bi bi-house text-sm text-slate-400"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-link" href="<?= BASE_URL ?>/index.php?page=admin-role-permissions">
                            <i class="bi bi-gear text-sm text-slate-400"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="border-t border-slate-100 my-1"></li>
                    <li>
                        <a class="dropdown-item-link logout" href="<?= BASE_URL ?>/index.php?page=admin-logout">
                            <i class="bi bi-box-arrow-right text-sm"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
(function () {
    var baseUrl = '<?= BASE_URL ?>/index.php?page=admin-notifications-';
    var wrapper = document.getElementById('notifWrapper');
    var bell = document.getElementById('notifBell');
    var panel = document.getElementById('notifPanel');
    var list = document.getElementById('notifList');
    var badge = document.getElementById('notifBadge');
    var markAll = document.getElementById('notifMarkAll');
    var isOpen = false;

    var typeIcons = {
        system: 'bi-gear-fill',
        assessment: 'bi-clipboard-check',
        user: 'bi-person-circle',
        career: 'bi-briefcase',
        question: 'bi-question-circle'
    };

    function setBadge(count) {
        count = parseInt(count, 10) || 0;
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function refreshBadge() {
        fetch(baseUrl + 'api-unread-count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) { setBadge(data.unread_count); })
            .catch(function () {});
    }

    function escapeHtml(str) {
        var d = document.createElement('div');
        d.textContent = str == null ? '' : String(str);
        return d.innerHTML;
    }

    function renderItem(n) {
        var unread = parseInt(n.is_read, 10) === 0;
        var icon = typeIcons[n.type] || 'bi-bell';
        var inner = ''
            + '<div class="flex gap-3 px-5 py-3.5 ' + (unread ? 'unread' : '') + '">'
            + '<div class="mt-0.5 flex-shrink-0 w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">'
            + '<i class="bi ' + icon + '"></i></div>'
            + '<div class="min-w-0 flex-1">'
            + '<div class="flex items-start justify-between gap-2">'
            + '<p class="notif-title text-sm text-slate-700 leading-snug m-0">' + escapeHtml(n.title) + '</p>'
            + (unread ? '<span class="notif-dot mt-1.5"></span>' : '')
            + '</div>'
            + (n.message ? '<p class="text-xs text-slate-500 mt-0.5 line-clamp-2 m-0">' + escapeHtml(n.message) + '</p>' : '')
            + '<p class="text-[11px] text-slate-400 mt-1 m-0">' + escapeHtml(n.time_ago) + '</p>'
            + '</div></div>';
        var wrap = document.createElement('div');
        wrap.className = 'notif-item cursor-pointer';
        wrap.setAttribute('data-id', n.id);
        wrap.setAttribute('data-read', unread ? '0' : '1');
        wrap.innerHTML = inner;
        return wrap;
    }

    function renderList(notifications) {
        list.innerHTML = '';
        if (!notifications.length) {
            list.innerHTML = '<div class="px-5 py-12 text-center text-sm text-slate-400">'
                + '<i class="bi bi-bell-slash block text-2xl mb-2 opacity-60"></i> No notifications yet</div>';
            return;
        }
        notifications.forEach(function (n) {
            var el = renderItem(n);
            if (n.link) {
                el.addEventListener('click', function () { window.location.href = n.link; });
            }
            list.appendChild(el);
        });
    }

    function loadList() {
        list.innerHTML = '<div class="px-5 py-10 text-center text-sm text-slate-400">'
            + '<i class="bi bi-arrow-repeat animate-spin block text-lg mb-2"></i> Loading…</div>';
        fetch(baseUrl + 'api-list&limit=10', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                renderList(data.notifications || []);
                setBadge(data.unread_count);
            })
            .catch(function () {
                list.innerHTML = '<div class="px-5 py-10 text-center text-sm text-slate-400">Failed to load.</div>';
            });
    }

    function openPanel() {
        isOpen = true;
        panel.style.display = 'block';
        bell.setAttribute('aria-expanded', 'true');
        loadList();
    }

    function closePanel() {
        isOpen = false;
        panel.style.display = 'none';
        bell.setAttribute('aria-expanded', 'false');
    }

    bell.addEventListener('click', function (e) {
        e.stopPropagation();
        isOpen ? closePanel() : openPanel();
    });

    document.addEventListener('click', function (e) {
        if (isOpen && wrapper && !wrapper.contains(e.target)) {
            closePanel();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closePanel();
    });

    list.addEventListener('click', function (e) {
        var item = e.target.closest('.notif-item');
        if (!item) return;
        if (item.getAttribute('data-read') !== '0') return;
        var id = item.getAttribute('data-id');
        fetch(baseUrl + 'api-mark-read', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            item.classList.remove('unread');
            item.querySelector('.notif-dot')?.remove();
            item.setAttribute('data-read', '1');
            setBadge(data.unread_count);
            refreshBadge();
        })
        .catch(function () {});
    });

    if (markAll) {
        markAll.addEventListener('click', function () {
            fetch(baseUrl + 'api-mark-all-read', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function () {
                list.querySelectorAll('.notif-item.unread').forEach(function (el) {
                    el.classList.remove('unread');
                    el.querySelector('.notif-dot')?.remove();
                    el.setAttribute('data-read', '1');
                });
                setBadge(0);
                refreshBadge();
            })
            .catch(function () {});
        });
    }

    refreshBadge();
})();
</script>
