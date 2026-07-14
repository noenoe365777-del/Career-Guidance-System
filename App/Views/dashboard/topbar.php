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
$firstLetter = $studentName !== '' ? mb_strtoupper(mb_substr($studentName, 0, 1)) : 'S';

if (!empty($user['profile_image'])) {
    $profileImage = $user['profile_image'];
} elseif ($userId > 0) {
    try {
        $pdo = \App\Config\Database::getConnection();
        $stmt = $pdo->prepare("SELECT profile_image FROM student_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['profile_image'])) {
            $profileImage = $row['profile_image'];
        }
    } catch (\Throwable $e) {
        // Silently fail
    }
}

// Mock notifications (frontend-only demo)
$notifications = [
    [
        'id' => 1,
        'type' => 'assessment',
        'icon' => 'fa-brain',
        'title' => 'Assessment Completed',
        'message' => 'Your Personality Assessment has been completed.',
        'time' => '2 minutes ago',
        'read' => false,
    ],
    [
        'id' => 2,
        'type' => 'recommendation',
        'icon' => 'fa-bullseye',
        'title' => 'Career Recommendation Ready',
        'message' => 'Your personalized career recommendation is available.',
        'time' => 'Yesterday',
        'read' => false,
    ],
    [
        'id' => 3,
        'type' => 'profile',
        'icon' => 'fa-user-check',
        'title' => 'Profile Updated',
        'message' => 'Your profile has been updated successfully.',
        'time' => '3 days ago',
        'read' => true,
    ],
];
$unreadCount = count(array_filter($notifications, fn($n) => !$n['read']));
?>
<nav class="sticky top-0 z-40 border-b border-slate-100 bg-white">
    <div class="flex h-16 items-center justify-between px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button id="open-sidebar-btn" class="inline-flex items-center justify-center rounded-xl border-0 bg-transparent p-2 text-slate-500 outline-none transition-colors duration-200 hover:bg-slate-50 hover:text-indigo-600 lg:hidden" type="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <h1 class="m-0 hidden text-base font-bold tracking-tight text-slate-800 md:block"><?= htmlspecialchars($currentPageLabel) ?></h1>
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
            <!-- Notifications -->
            <div class="relative" id="notifContainer">
                <button type="button" id="notifBtn" aria-label="Notifications" class="group relative rounded-xl border-0 bg-transparent p-2 text-slate-400 outline-none transition-all duration-200 hover:bg-slate-50 hover:text-slate-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 transition-transform duration-200 group-hover:scale-105">
                        <path d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.4V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path>
                        <path d="M9 17a3 3 0 0 0 6 0"></path>
                    </svg>
                    <?php if ($unreadCount > 0): ?>
                    <span id="notifBadge" class="absolute -right-1 -top-1 flex min-w-[18px] items-center justify-center rounded-full bg-[#5B5CEB] px-1 text-[10px] font-bold leading-[18px] text-white ring-2 ring-white"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </button>

                <div id="notifDropdown" class="absolute right-0 z-50 mt-2 hidden w-[360px] origin-top-right rounded-2xl border border-slate-100 bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <h3 class="text-sm font-bold text-slate-900">Notifications</h3>
                        <?php if ($unreadCount > 0): ?>
                        <button type="button" id="markAllReadBtn" class="text-xs font-semibold text-[#5B5CEB] hover:text-[#4a4bd6]">Mark all as read</button>
                        <?php endif; ?>
                    </div>
                    <div id="notifList" class="max-h-[350px] overflow-y-auto">
                        <?php if (empty($notifications)): ?>
                        <div class="flex flex-col items-center justify-center px-5 py-12 text-center">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7">
                                    <path d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.4V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path>
                                    <path d="M9 17a3 3 0 0 0 6 0"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-500">No notifications yet.</p>
                        </div>
                        <?php else: ?>
                        <?php foreach ($notifications as $n): ?>
                        <div class="notif-item <?= $n['read'] ? '' : 'bg-indigo-50/40' ?> flex cursor-pointer items-start gap-3 border-b border-slate-50 px-5 py-3.5 transition-colors duration-150 hover:bg-slate-50" data-id="<?= $n['id'] ?>" data-read="<?= $n['read'] ? '1' : '0' ?>">
                            <div class="relative mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                                <i class="fas <?= htmlspecialchars($n['icon']) ?> text-xs"></i>
                                <?php if (!$n['read']): ?>
                                <span class="notif-dot absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full bg-[#5B5CEB] ring-2 ring-white"></span>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-slate-900"><?= htmlspecialchars($n['title']) ?></p>
                                <p class="mt-0.5 text-xs text-slate-500"><?= htmlspecialchars($n['message']) ?></p>
                                <p class="mt-1 text-[10px] font-medium text-slate-400"><?= htmlspecialchars($n['time']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                <button type="button" id="userDropdownBtn" aria-expanded="false" class="flex h-12 cursor-pointer items-center gap-2.5 rounded-full border border-[#E5E7EB] bg-white px-3 py-2 outline-none transition-all duration-200 hover:bg-slate-50">
                    <?php if ($profileImage): ?>
                        <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($profileImage) ?>" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    <?php else: ?>
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#6366F1] text-sm font-semibold text-white"><?= htmlspecialchars($firstLetter) ?></span>
                    <?php endif; ?>
                    <span class="hidden select-none items-center gap-1.5 sm:flex">
                        <span class="max-w-[120px] truncate text-sm font-semibold text-[#1F2937]"><?= htmlspecialchars($studentName) ?></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" id="userDropdownChevron"></i>
                    </span>
                </button>

                <div id="userDropdownMenu" class="absolute right-0 z-50 mt-2 hidden w-48 rounded-2xl border border-slate-100 bg-white p-1.5 shadow-xl">
                    <a href="<?= BASE_URL ?>/index.php?page=profile" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-600 transition-colors duration-150 hover:bg-slate-50 hover:text-indigo-600 no-underline">
                        <i class="fas fa-user text-sm text-slate-400"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=home" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-600 transition-colors duration-150 hover:bg-slate-50 hover:text-indigo-600 no-underline">
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
document.addEventListener('DOMContentLoaded', function() {
    // User Dropdown
    const dropdownBtn = document.getElementById('userDropdownBtn');
    const dropdownMenu = document.getElementById('userDropdownMenu');
    const chevron = document.getElementById('userDropdownChevron');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            if (chevron) chevron.classList.toggle('rotate-180');
        });

        document.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });
    }

    // Notification Dropdown
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifContainer = document.getElementById('notifContainer');

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = notifDropdown.classList.contains('hidden');
            // Close user dropdown if open
            if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
                dropdownMenu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
            if (isHidden) {
                notifDropdown.classList.remove('hidden');
                notifDropdown.classList.add('animate-notif-in');
            } else {
                notifDropdown.classList.add('hidden');
                notifDropdown.classList.remove('animate-notif-in');
            }
        });

        document.addEventListener('click', function(e) {
            if (notifContainer && !notifContainer.contains(e.target)) {
                notifDropdown.classList.add('hidden');
                notifDropdown.classList.remove('animate-notif-in');
            }
        });
    }

    // Mark individual notification as read on click
    document.querySelectorAll('.notif-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const isRead = this.dataset.read === '1';
            if (isRead) return;
            this.dataset.read = '1';
            this.classList.remove('bg-indigo-50/40');
            const dot = this.querySelector('.notif-dot');
            if (dot) dot.remove();
            updateUnreadCount();
        });
    });

    // Mark all as read
    const markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('.notif-item').forEach(function(item) {
                item.dataset.read = '1';
                item.classList.remove('bg-indigo-50/40');
                const dot = item.querySelector('.notif-dot');
                if (dot) dot.remove();
            });
            updateUnreadCount();
            this.remove();
        });
    }

    function updateUnreadCount() {
        const unread = document.querySelectorAll('.notif-item[data-read="0"]').length;
        const badge = document.getElementById('notifBadge');
        if (badge) {
            if (unread > 0) {
                badge.textContent = unread;
            } else {
                badge.remove();
            }
        }
        // Remove mark-all button if no unread left
        if (unread === 0 && markAllBtn) {
            markAllBtn.remove();
        }
    }
});
</script>

<style>
@keyframes notifFadeSlide {
    from { opacity: 0; transform: translateY(-8px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-notif-in {
    animation: notifFadeSlide 0.2s ease-out forwards;
}
#notifDropdown {
    transform-origin: top right;
}
</style>
