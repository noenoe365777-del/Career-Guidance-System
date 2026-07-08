<!-- Mobile Sidebar Toggle Backdrop (Managed via JS) -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

<!-- Sidebar Container -->
<aside id="main-sidebar" class="fixed top-0 bottom-0 left-0 z-50 w-72 bg-slate-900 text-slate-400 border-r border-slate-800 flex flex-col justify-between transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    
    <!-- Top Branding Header -->
    <div class="p-6 border-b border-slate-800 flex items-center justify-between">
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black shadow-lg shadow-indigo-600/30 transition-transform group-hover:scale-105">
                <i class="fas fa-graduation-cap text-sm"></i>
            </div>
            <div>
                <span class="text-sm font-black text-white tracking-wider uppercase block">CareerPath</span>
                <span class="text-[10px] font-bold text-slate-500 tracking-widest uppercase block -mt-0.5">Student Hub</span>
            </div>
        </a>
        <!-- Close Button Mobile Only -->
        <button id="close-sidebar-btn" class="lg:hidden w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

    <!-- Central Navigation Links -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 custom-scrollbar">
        <?php 
        $currentPage = $currentPage ?? ($_GET['page'] ?? 'dashboard');
        $activePage = $currentPage === 'change-password' ? 'settings' : $currentPage;
        $currentStudentUserId = \App\Modules\Student\Support\StudentFeaturePermissionHelper::currentStudentUserId();

        $navItems = [
            ['id' => 'dashboard', 'feature_key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-th-large'],
            ['id' => 'assessments', 'feature_key' => 'assessments', 'label' => 'Assessments', 'icon' => 'fa-clipboard-list'],
            ['id' => 'recommendation', 'feature_key' => 'career_maps', 'label' => 'Career Maps', 'icon' => 'fa-map-marked-alt'],
            ['id' => 'profile', 'feature_key' => 'profile', 'label' => 'Profile', 'icon' => 'fa-user-circle'],
            ['id' => 'settings', 'feature_key' => 'settings', 'label' => 'Settings', 'icon' => 'fa-sliders-h']
        ];

        foreach($navItems as $item): 
            $featureKey = (string)($item['feature_key'] ?? '');
            $shouldShow = $featureKey === '' || \App\Modules\Student\Support\StudentFeaturePermissionHelper::canStudentAccessFeature($currentStudentUserId, $featureKey);
            if (!$shouldShow) {
                continue;
            }

            $isActive = ($activePage === $item['id']);
            $href = $item['id'] === 'settings' ? BASE_URL . '/index.php?page=change-password' : BASE_URL . '/index.php?page=' . $item['id'];
        ?>
            <a href="<?= $href ?>" 
               class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 group <?= $isActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'hover:bg-slate-800/60 hover:text-slate-200' ?>">
                <i class="fas <?= $item['icon'] ?> text-base transition-transform group-hover:scale-105 <?= $isActive ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' ?>"></i>
                <?= $item['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Bottom Account/Logout Area -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition duration-200 group">
            <i class="fas fa-sign-out-alt text-base text-rose-500/70 group-hover:text-rose-400 transition-transform group-hover:-translate-x-0.5"></i>
            Sign Out Workspace
        </a>
    </div>
</aside>

<!-- Small Responsive Trigger Script Interface Hook -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('main-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const trigger = document.getElementById('open-sidebar-btn'); // Found in topbar.php
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