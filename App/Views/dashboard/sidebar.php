<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

<!-- Desktop Sidebar -->
<aside class="hidden lg:flex lg:flex-col lg:justify-between w-72 h-screen sticky top-0 bg-white border-r border-slate-100 p-4 shrink-0">
    <div>
        <div class="flex items-center gap-3 px-2 mb-8">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600">
                <i class="fas fa-graduation-cap text-xl"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-800 tracking-tight">Career Guidance</h2>
                <p class="text-[11px] font-medium text-slate-400">Student Portal</p>
            </div>
        </div>

        <nav class="space-y-1">
            <?php 
            $currentPage = $currentPage ?? ($_GET['page'] ?? 'dashboard');
            $activePage = $currentPage === 'change-password' ? 'settings' : $currentPage;
            $currentStudentUserId = \App\Modules\Student\Support\StudentFeaturePermissionHelper::currentStudentUserId();

            $navItems = [
                ['id' => 'dashboard', 'feature_key' => 'view_dashboard', 'label' => 'Dashboard', 'icon' => 'fa-th-large'],
                ['id' => 'student-assessments', 'feature_key' => 'take_assessment', 'label' => 'Assessments', 'icon' => 'fa-clipboard-list'],
                ['id' => 'recommendation', 'feature_key' => 'view_recommendations', 'label' => 'Career Maps', 'icon' => 'fa-map-marked-alt'],
                ['id' => 'settings', 'feature_key' => 'edit_profile', 'label' => 'Settings', 'icon' => 'fa-sliders-h']
            ];

            foreach ($navItems as $item):
                $featureKey = (string)($item['feature_key'] ?? '');
                $shouldShow = $featureKey === '' || \App\Modules\Student\Support\StudentFeaturePermissionHelper::canStudentAccessFeature($currentStudentUserId, $featureKey);
                if (!$shouldShow) continue;

                $isActive = ($activePage === $item['id']);
                $href = $item['id'] === 'settings' ? BASE_URL . '/index.php?page=change-password' : BASE_URL . '/index.php?page=' . $item['id'];
            ?>
                <a href="<?= $href ?>"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 no-underline group <?= $isActive ? 'text-white bg-gradient-to-r from-indigo-600 to-indigo-500 shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' ?>">
                    <i class="fas <?= $item['icon'] ?> text-base <?= $isActive ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' ?>"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="border-t border-slate-100 pt-4">
        <div class="flex items-center gap-2.5 px-2 text-slate-400">
            <i class="fas fa-shield-alt text-sm text-emerald-500"></i>
            <span class="text-xs font-medium tracking-wide">Secure student access</span>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar (Offcanvas-style) -->
<div id="main-sidebar" class="fixed top-0 left-0 z-50 w-72 h-full bg-white border-r border-slate-100 shadow-xl transform -translate-x-full lg:hidden transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between px-4 py-3.5 border-b border-slate-50">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600">
                <i class="fas fa-graduation-cap text-lg"></i>
            </div>
            <div>
                <h5 class="text-sm font-bold text-slate-800 m-0">Career Guidance</h5>
                <p class="text-[10px] text-slate-400 font-medium m-0">Student Portal</p>
            </div>
        </div>
        <button id="close-sidebar-btn" class="w-8 h-8 rounded-lg bg-transparent text-slate-400 hover:text-slate-600 hover:bg-slate-50 flex items-center justify-center transition border-0 outline-none">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <div class="flex-1 p-4 overflow-y-auto">
        <nav class="space-y-1.5 w-full">
            <?php foreach ($navItems as $item):
                $featureKey = (string)($item['feature_key'] ?? '');
                $shouldShow = $featureKey === '' || \App\Modules\Student\Support\StudentFeaturePermissionHelper::canStudentAccessFeature($currentStudentUserId, $featureKey);
                if (!$shouldShow) continue;

                $isActive = ($activePage === $item['id']);
                $href = $item['id'] === 'settings' ? BASE_URL . '/index.php?page=change-password' : BASE_URL . '/index.php?page=' . $item['id'];
            ?>
                <a href="<?= $href ?>"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 no-underline group <?= $isActive ? 'text-white bg-gradient-to-r from-indigo-600 to-indigo-500 shadow-sm' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' ?>">
                    <i class="fas <?= $item['icon'] ?> text-base <?= $isActive ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' ?>"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="border-t border-slate-100 p-4">
        <div class="flex items-center gap-2.5 text-slate-400">
            <i class="fas fa-shield-alt text-sm text-emerald-500"></i>
            <span class="text-xs font-medium tracking-wide">Secure student access</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('main-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const trigger = document.getElementById('open-sidebar-btn');
    const closeBtn = document.getElementById('close-sidebar-btn');

    function toggleSidebar(open) {
        if(open) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.classList.add('opacity-100'), 10);
        } else {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.remove('opacity-100');
            setTimeout(() => backdrop.classList.add('hidden'), 300);
        }
    }

    if(trigger) trigger.addEventListener('click', () => toggleSidebar(true));
    if(closeBtn) closeBtn.addEventListener('click', () => toggleSidebar(false));
    if(backdrop) backdrop.addEventListener('click', () => toggleSidebar(false));
});
</script>