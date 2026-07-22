<?php
$currentPage = $currentPage ?? ($_GET['page'] ?? 'dashboard');
$pageTitles = [
    'dashboard' => 'Dashboard',
    'assessments' => 'Assessments',
    'recommendation' => 'Career Maps',
    'notifications' => 'Notifications',
    'profile' => 'Profile',
    'change-password' => 'Change Password',
    'edit-profile' => 'Edit Profile'
];
$currentPageLabel = 'Dashboard';

$user = $_SESSION['user'] ?? [];
$studentName = trim((string)($user['full_name'] ?? $user['name'] ?? $user['username'] ?? 'Student'));
$userId = (int)($user['id'] ?? $user['user_id'] ?? 0);
$profileImage = null;
$profileImageUrl = '';
$firstLetter = $studentName !== '' ? mb_strtoupper(mb_substr($studentName, 0, 1)) : 'S';

$profileImageRaw = $user['profile_image'] ?? '';
if ($profileImageRaw !== '') {
    $profileImage = $profileImageRaw;
    $newPath = BASE_PATH . '/public/uploads/profile/' . $profileImageRaw;
    $legacyPath = BASE_PATH . '/Public/assets/images/' . $profileImageRaw;
    if (file_exists($newPath)) {
        $profileImageUrl = BASE_URL . '/uploads/profile/' . rawurlencode($profileImageRaw);
    } elseif (file_exists($legacyPath)) {
        $profileImageUrl = BASE_URL . '/assets/images/' . rawurlencode($profileImageRaw);
    }
} elseif ($userId > 0) {
    try {
        $pdo = \App\Config\Database::getConnection();
        $stmt = $pdo->prepare("SELECT profile_image FROM student_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['profile_image'])) {
            $profileImage = $row['profile_image'];
            $newPath = BASE_PATH . '/public/uploads/profile/' . $row['profile_image'];
            $legacyPath = BASE_PATH . '/Public/assets/images/' . $row['profile_image'];
            if (file_exists($newPath)) {
                $profileImageUrl = BASE_URL . '/uploads/profile/' . rawurlencode($row['profile_image']);
            } elseif (file_exists($legacyPath)) {
                $profileImageUrl = BASE_URL . '/assets/images/' . rawurlencode($row['profile_image']);
            }
        }
    } catch (\Throwable $e) {}
}

$notifUnreadCount = 0;
try {
    $pdo = \App\Config\Database::getConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE is_read = 0 AND recipient_id = :rid AND recipient_role = 'student'");
    $stmt->execute([':rid' => $userId]);
    $notifUnreadCount = (int)$stmt->fetchColumn();
} catch (\Throwable $e) {}
?>
<style>
    @keyframes notifPop { from { opacity: 0; transform: translateY(-8px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes dropFade { from { opacity: 0; transform: translateY(-6px) scale(0.96); } to { opacity: 1; transform: translateY(0) scale(1); } }
    #userDropdownMenu:not(.hidden) { animation: dropFade 0.18s ease-out both; }
    .notif-item { transition: background-color 0.15s ease; }
    .notif-item:hover { background: #f8fafc; }
    .notif-item.unread { background: #f5f7ff; }
    .notif-item.unread .notif-title { color: #1e1b4b; font-weight: 700; }
    .notif-dot { width: 8px; height: 8px; border-radius: 9999px; background: #3B82F6; flex-shrink: 0; }
    .notif-scroll::-webkit-scrollbar { width: 6px; }
    .notif-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 9999px; }
    .icon-hover { transition: all 0.2s ease; }
    .icon-hover:hover { transform: scale(1.12); }
    .icon-hover:active { transform: scale(0.95); }
    .profile-btn { transition: all 0.2s ease; }
    .profile-btn:hover { transform: scale(1.03); }
    .profile-btn:active { transform: scale(0.97); }
</style>

<nav class="sticky top-0 z-40 border-b border-slate-100 bg-white">
    <div class="flex h-16 items-center justify-between px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button id="open-sidebar-btn" class="inline-flex items-center justify-center rounded-xl border-0 bg-transparent p-2 text-slate-500 outline-none transition-colors duration-200 hover:bg-slate-50 hover:text-blue-600 lg:hidden" type="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="m-0 hidden text-base font-bold tracking-tight text-slate-800 md:block"><?= htmlspecialchars($currentPageLabel) ?></h1>
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
            <div class="relative" id="notifWrapper">
                <button type="button"
                        class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 no-underline outline-none inline-flex items-center justify-center icon-hover"
                        id="notifBell"
                        aria-label="Notifications"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <i class="bi bi-bell text-xl"></i>
                    <span id="notifBadge" class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-blue-500 text-white text-[10px] font-bold leading-none shadow-sm ring-2 ring-white <?= $notifUnreadCount > 0 ? '' : 'hidden' ?>"><?= $notifUnreadCount ?></span>
                </button>

                <div id="notifPanel"
                     class="absolute right-0 mt-3 w-[420px] max-w-[calc(100vw-2rem)] bg-white rounded-[20px] shadow-2xl border border-[#E5E7EB] overflow-hidden z-50 origin-top-right"
                     style="display:none; animation: notifPop 0.22s cubic-bezier(0.16,1,0.3,1) both;">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-800 m-0">Notifications</h3>
                        <button type="button" id="notifMarkAll"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors <?= $notifUnreadCount === 0 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <i class="bi bi-check2-all"></i> Mark all as read
                        </button>
                    </div>

                    <div id="notifList" class="notif-scroll max-h-[380px] overflow-y-auto divide-y divide-slate-50">
                        <div class="px-5 py-10 text-center text-sm text-slate-400">
                            <i class="bi bi-arrow-repeat animate-spin block text-lg mb-2"></i> Loading&hellip;
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>/index.php?page=notifications"
                       class="block text-center px-5 py-3 text-sm font-semibold text-blue-600 hover:bg-blue-50/60 transition-colors border-t border-slate-100 no-underline">
                        View all notifications
                    </a>
                </div>
            </div>

            <div class="relative">
                <button type="button" id="userDropdownBtn" aria-expanded="false" class="profile-btn flex h-12 cursor-pointer items-center gap-2.5 rounded-full border border-[#E5E7EB] bg-white px-3 py-2 outline-none transition-all duration-200 hover:bg-slate-50">
                    <?php if ($profileImageUrl): ?>
                        <img id="navbarAvatarImg" src="<?= $profileImageUrl ?>?v=<?= time() ?>" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    <?php else: ?>
                        <span id="navbarAvatarPlaceholder" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#3B82F6] text-sm font-semibold text-white"><?= htmlspecialchars($firstLetter) ?></span>
                    <?php endif; ?>
                    <span class="hidden select-none items-center gap-1.5 sm:flex">
                        <span class="max-w-[120px] truncate text-sm font-semibold text-[#1F2937]"><?= htmlspecialchars($studentName) ?></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" id="userDropdownChevron"></i>
                    </span>
                </button>

                <div id="userDropdownMenu" class="absolute right-0 z-50 mt-2 hidden w-48 rounded-2xl border border-slate-100 bg-white p-1.5 shadow-xl">
                    <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-600 transition-colors duration-150 hover:bg-slate-50 hover:text-blue-600 no-underline">
                        <i class="fas fa-user text-sm text-slate-400"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=home" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-600 transition-colors duration-150 hover:bg-slate-50 hover:text-blue-600 no-underline">
                        <i class="fas fa-home text-sm text-slate-400"></i>
                        <span>Dashboard</span>
                    </a>
                    <div class="my-1 border-t border-slate-50"></div>
                    <a href="<?= BASE_URL ?>/index.php?page=logout" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-red-600 transition-colors duration-150 hover:bg-red-50/50 hover:text-red-700 no-underline">
                        <i class="fas fa-sign-out-alt text-sm opacity-80"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
(function() {
    var baseUrl = '<?= BASE_URL ?>/index.php?page=notifications-';
    var wrapper = document.getElementById('notifWrapper');
    var bell = document.getElementById('notifBell');
    var panel = document.getElementById('notifPanel');
    var list = document.getElementById('notifList');
    var badge = document.getElementById('notifBadge');
    var markAll = document.getElementById('notifMarkAll');
    var isOpen = false;

    var typeIcons = {
        system: 'bi-megaphone',
        assessment: 'bi-clipboard-check',
        user: 'bi-person-circle',
        career: 'bi-briefcase',
        profile: 'bi-person-check',
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
            .then(function(r) { return r.json(); })
            .then(function(data) { setBadge(data.unread_count); })
            .catch(function() {});
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
            + '<div class="mt-0.5 flex-shrink-0 w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-base">'
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
        notifications.forEach(function(n) {
            var el = renderItem(n);
            list.appendChild(el);
        });
    }

    function loadList() {
        list.innerHTML = '<div class="px-5 py-10 text-center text-sm text-slate-400">'
            + '<i class="bi bi-arrow-repeat animate-spin block text-lg mb-2"></i> Loading&hellip;</div>';
        fetch(baseUrl + 'api-list&limit=5', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                renderList(data.notifications || []);
                setBadge(data.unread_count);
            })
            .catch(function() {
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

    var dropdownBtn = document.getElementById('userDropdownBtn');
    var dropdownMenu = document.getElementById('userDropdownMenu');
    var chevron = document.getElementById('userDropdownChevron');
    var userDropdownOpen = false;

    function closeUserDropdown() {
        userDropdownOpen = false;
        dropdownMenu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    }

    function openUserDropdown() {
        userDropdownOpen = true;
        dropdownMenu.classList.remove('hidden');
        if (chevron) chevron.classList.add('rotate-180');
    }

    function toggleUserDropdown() {
        if (userDropdownOpen) {
            closeUserDropdown();
        } else {
            if (isOpen) closePanel();
            openUserDropdown();
        }
    }

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleUserDropdown();
        });
    }

    bell.addEventListener('click', function(e) {
        e.stopPropagation();
        if (userDropdownOpen) closeUserDropdown();
        isOpen ? closePanel() : openPanel();
    });

    document.addEventListener('click', function(e) {
        if (isOpen && wrapper && !wrapper.contains(e.target)) {
            closePanel();
        }
        if (userDropdownOpen && dropdownBtn && dropdownMenu && !dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            closeUserDropdown();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (isOpen) closePanel();
            if (userDropdownOpen) closeUserDropdown();
        }
    });

    list.addEventListener('click', function(e) {
        var item = e.target.closest('.notif-item');
        if (!item) return;
        if (item.getAttribute('data-read') !== '0') return;
        var id = item.getAttribute('data-id');
        fetch(baseUrl + 'api-mark-read', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                item.classList.remove('unread');
                var dot = item.querySelector('.notif-dot');
                if (dot) dot.remove();
                item.setAttribute('data-read', '1');
                var title = item.querySelector('.notif-title');
                if (title) title.style.fontWeight = '500';
                setBadge(data.unread_count);
                refreshBadge();
            }
        })
        .catch(function() {});
    });

    if (markAll) {
        markAll.addEventListener('click', function() {
            fetch(baseUrl + 'api-mark-all-read', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function() {
                list.querySelectorAll('.notif-item.unread').forEach(function(el) {
                    el.classList.remove('unread');
                    var dot = el.querySelector('.notif-dot');
                    if (dot) dot.remove();
                    el.setAttribute('data-read', '1');
                    var title = el.querySelector('.notif-title');
                    if (title) title.style.fontWeight = '500';
                });
                setBadge(0);
                refreshBadge();
            })
            .catch(function() {});
        });
    }

    refreshBadge();

    window.updateNavbarAvatar = function(imageUrl) {
        var btn = document.getElementById('userDropdownBtn');
        if (!btn) return;
        var existingImg = document.getElementById('navbarAvatarImg');
        var existingPlaceholder = document.getElementById('navbarAvatarPlaceholder');
        if (imageUrl) {
            var cacheBusted = imageUrl + '?v=' + Date.now();
            if (existingImg) {
                existingImg.src = cacheBusted;
            } else {
                if (existingPlaceholder) existingPlaceholder.remove();
                var img = document.createElement('img');
                img.id = 'navbarAvatarImg';
                img.src = cacheBusted;
                img.alt = '';
                img.className = 'h-9 w-9 shrink-0 rounded-full object-cover';
                btn.insertBefore(img, btn.firstChild);
            }
        } else {
            if (existingImg) {
                existingImg.remove();
                var letter = '<?= $firstLetter ?>';
                if (!document.getElementById('navbarAvatarPlaceholder')) {
                    var span = document.createElement('span');
                    span.id = 'navbarAvatarPlaceholder';
                    span.className = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#3B82F6] text-sm font-semibold text-white';
                    span.textContent = letter;
                    btn.insertBefore(span, btn.firstChild);
                }
            }
        }
    };
})();
</script>
