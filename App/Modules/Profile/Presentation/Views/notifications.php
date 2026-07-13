<?php
$mockNotifications = [
    [
        'id' => 1,
        'type' => 'assessment',
        'icon' => 'fa-brain',
        'title' => 'Assessment Completed',
        'message' => 'Your Personality Assessment has been completed.',
        'date' => 'Today',
        'time' => '10:30 AM',
        'read' => false,
    ],
    [
        'id' => 2,
        'type' => 'recommendation',
        'icon' => 'fa-bullseye',
        'title' => 'Career Recommendation Ready',
        'message' => 'Your personalized career recommendation is now available.',
        'date' => 'Yesterday',
        'time' => '',
        'read' => false,
    ],
    [
        'id' => 3,
        'type' => 'profile',
        'icon' => 'fa-user-check',
        'title' => 'Profile Updated',
        'message' => 'Your profile information has been updated successfully.',
        'date' => 'Jun 28',
        'time' => '2:15 PM',
        'read' => true,
    ],
    [
        'id' => 4,
        'type' => 'password',
        'icon' => 'fa-lock',
        'title' => 'Password Changed',
        'message' => 'Your account password was changed successfully.',
        'date' => 'Jun 20',
        'time' => '',
        'read' => true,
    ],
    [
        'id' => 5,
        'type' => 'system',
        'icon' => 'fa-bullhorn',
        'title' => 'New Features Available',
        'message' => 'Check out the new career exploration tools added to your dashboard.',
        'date' => 'Jun 15',
        'time' => '9:00 AM',
        'read' => true,
    ],
];
$hasUnread = count(array_filter($mockNotifications, fn($n) => !$n['read'])) > 0;
?>
<div class="mx-auto w-full max-w-4xl px-4 py-6 sm:px-6 sm:py-8">

    <!-- Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Notifications</h1>
            <p class="mt-0.5 text-sm text-slate-500">View all your recent updates and announcements.</p>
        </div>
        <?php if ($hasUnread): ?>
        <button type="button" id="markAllReadBtn" class="inline-flex items-center gap-2 rounded-xl border border-[#E5E7EB] bg-white px-4 py-2.5 text-xs font-semibold text-[#5B5CEB] shadow-sm transition-all duration-200 hover:bg-slate-50 no-underline">
            <i class="fas fa-check-double text-xs"></i>
            Mark all as read
        </button>
        <?php endif; ?>
    </div>

    <?php if (empty($mockNotifications)): ?>
    <!-- Empty State -->
    <section class="flex flex-col items-center justify-center rounded-[20px] border border-[#E5E7EB] bg-white px-6 py-20 shadow-sm">
        <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-slate-300">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="h-9 w-9">
                <path d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.4V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path>
                <path d="M9 17a3 3 0 0 0 6 0"></path>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-700">No notifications yet.</h3>
        <p class="mt-1 text-sm text-slate-400">We'll notify you when something new arrives.</p>
    </section>
    <?php else: ?>
    <!-- Notification List -->
    <section class="rounded-[20px] border border-[#E5E7EB] bg-white shadow-sm">
        <?php foreach ($mockNotifications as $i => $n): ?>
        <div class="notif-item flex cursor-pointer items-start gap-4 px-5 py-4 sm:px-6 sm:py-5 <?= $i > 0 ? 'border-t border-slate-100' : '' ?> <?= $n['read'] ? '' : 'bg-indigo-50/30' ?> transition-colors duration-150 hover:bg-slate-50" data-id="<?= $n['id'] ?>" data-read="<?= $n['read'] ? '1' : '0' ?>">
            <div class="relative mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?= $n['read'] ? 'bg-slate-50 text-slate-400' : 'bg-indigo-100 text-indigo-600' ?>">
                <i class="fas <?= htmlspecialchars($n['icon']) ?> text-sm"></i>
                <?php if (!$n['read']): ?>
                <span class="notif-dot absolute -right-0.5 -top-0.5 h-3 w-3 rounded-full bg-[#5B5CEB] ring-2 ring-white"></span>
                <?php endif; ?>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-semibold <?= $n['read'] ? 'text-slate-700' : 'text-slate-900' ?>"><?= htmlspecialchars($n['title']) ?></p>
                    <span class="shrink-0 text-xs text-slate-400"><?= htmlspecialchars($n['date']) ?><?= $n['time'] ? ' &bull; ' . htmlspecialchars($n['time']) : '' ?></span>
                </div>
                <p class="mt-0.5 text-sm text-slate-500"><?= htmlspecialchars($n['message']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark individual notification as read
    document.querySelectorAll('.notif-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const isRead = this.dataset.read === '1';
            if (isRead) return;
            this.dataset.read = '1';
            this.classList.remove('bg-indigo-50/30');
            const dot = this.querySelector('.notif-dot');
            if (dot) dot.remove();
            // Update icon background
            const iconWrap = this.querySelector('.rounded-xl');
            if (iconWrap) {
                iconWrap.classList.remove('bg-indigo-100', 'text-indigo-600');
                iconWrap.classList.add('bg-slate-50', 'text-slate-400');
            }
            // Update title color
            const title = this.querySelector('.font-semibold');
            if (title) title.classList.replace('text-slate-900', 'text-slate-700');
            updateMarkAllBtn();
        });
    });

    // Mark all as read
    const markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            document.querySelectorAll('.notif-item[data-read="0"]').forEach(function(item) {
                item.dataset.read = '1';
                item.classList.remove('bg-indigo-50/30');
                const dot = item.querySelector('.notif-dot');
                if (dot) dot.remove();
                const iconWrap = item.querySelector('.rounded-xl');
                if (iconWrap) {
                    iconWrap.classList.remove('bg-indigo-100', 'text-indigo-600');
                    iconWrap.classList.add('bg-slate-50', 'text-slate-400');
                }
                const title = item.querySelector('.font-semibold');
                if (title) title.classList.replace('text-slate-900', 'text-slate-700');
            });
            this.remove();
        });
    }

    function updateMarkAllBtn() {
        if (!markAllBtn) return;
        const remaining = document.querySelectorAll('.notif-item[data-read="0"]').length;
        if (remaining === 0) markAllBtn.remove();
    }
});
</script>