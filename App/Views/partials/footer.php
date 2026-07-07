
<!-- ================= FOOTER ================= -->
<footer style="background:#111827;color:white;padding:60px;">
    <!-- Background Glow Animations -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px] pointer-events-none animate-pulse" style="animation-duration: 8s;"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none animate-pulse" style="animation-duration: 12s;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Main Footer Links Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 py-16">
            
            <!-- Brand Column -->
            <div class="md:col-span-5 space-y-5">
                <a href="index.php?page=home" class="flex items-center gap-3.5 group w-fit">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-indigo-500 flex items-center justify-center shadow-xl shadow-indigo-600/20 group-hover:scale-105 group-hover:rotate-3 transition-all duration-300">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold tracking-tight text-white group-hover:text-indigo-400 transition-colors">
                            Career Guidance
                        </h2>
                        <p class="text-[10px] tracking-[0.25em] uppercase text-indigo-400 font-bold">
                            For Students
                        </p>
                    </div>
                </a>
                <p class="text-slate-400 text-sm max-w-sm leading-relaxed">
                    Empowering minds, illuminating individual paths, and helping students confidently choose the optimal trajectory for their professional futures.
                </p>
 <!-- Social Media Badges -->
<div class="flex items-center gap-3 pt-2">
    <?php 
    $socials = [
        ['icon' => 'fa-facebook-f', 'url' => '#', 'label' => 'Facebook'],
        ['icon' => 'fa-instagram', 'url' => '#', 'label' => 'Instagram'],
        // Changed to fa-twitter to ensure backward compatibility across all Font Awesome versions
        ['icon' => 'fa-twitter', 'url' => '#', 'label' => 'Twitter'], 
        ['icon' => 'fa-linkedin-in', 'url' => '#', 'label' => 'LinkedIn']
    ];
    foreach ($socials as $social):
    ?>
        <a href="<?= $social['url'] ?>" 
           aria-label="<?= $social['label'] ?>"
           class="w-10 h-10 rounded-xl bg-slate-900/60 border border-slate-800/80 flex items-center justify-center text-slate-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-500 hover:-translate-y-1 shadow-lg backdrop-blur-md transition-all duration-300">
            <i class="fab <?= $social['icon'] ?> text-sm"></i>
        </a>
    <?php endforeach; ?>
</div>
            </div>

            <!-- Quick Navigation Links -->
            <div class="md:col-span-3 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-400">
                    Quick Navigation
                </h3>
                <ul class="space-y-2.5">
                    <?php 
                    $footerLinks = [
                        'home' => 'Home Base',
                        'careers' => 'Explore Careers',
                        'assessments' => 'Skill Assessments',
                        'contact' => 'Get in Touch'
                    ];
                    foreach ($footerLinks as $page => $title):
                    ?>
                        <li>
                            <a href="index.php?page=<?= $page ?>" class="text-sm text-slate-400 hover:text-white flex items-center gap-2 group transition-colors duration-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 scale-0 group-hover:scale-100 transition-transform duration-200 origin-center"></span>
                                <span class="group-hover:translate-x-1 transition-transform duration-200"><?= $title ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Communication Channel Details -->
            <div class="md:col-span-4 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-400">
                    Communication Channels
                </h3>
                <div class="space-y-3.5">
                    <a href="mailto:career@gmail.com" class="flex items-center gap-3.5 p-3.5 rounded-xl bg-slate-900/40 border border-slate-800/50 hover:border-slate-700/80 hover:bg-slate-900/80 transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-indigo-950/60 border border-indigo-900/50 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <span class="text-sm text-slate-300 group-hover:text-white font-medium transition-colors">career@gmail.com</span>
                    </a>

                    <a href="tel:+959679343479" class="flex items-center gap-3.5 p-3.5 rounded-xl bg-slate-900/40 border border-slate-800/50 hover:border-slate-700/80 hover:bg-slate-900/80 transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-indigo-950/60 border border-indigo-900/50 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-phone text-xs"></i>
                        </div>
                        <span class="text-sm text-slate-300 group-hover:text-white font-medium transition-colors">+959 679 343 479</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Absolute Bottom Metadata Block -->
        <div class="border-t border-slate-900 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
            <div>
                © <?= date('Y') ?> Career Guidance System. All rights reserved.
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-slate-300 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-slate-300 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- ================= JAVASCRIPT REFINEMENTS ================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Top Header scroll styling animation observer
    const header = document.querySelector("header");
    if (header) {
        window.addEventListener("scroll", function () {
            if (window.scrollY > 20) {
                header.classList.remove("bg-white/75", "border-slate-100");
                header.classList.add("bg-white/90", "shadow-xl", "shadow-slate-100/40", "border-slate-200/40");
            } else {
                header.classList.remove("bg-white/90", "shadow-xl", "shadow-slate-100/40", "border-slate-200/40");
                header.classList.add("bg-white/75", "border-slate-100");
            }
        });
    }
});
</script>
<?php if (isset($extraJs)): ?>
<script src="<?= BASE_URL ?>/<?= $extraJs ?>"></script>
<?php endif; ?>

</body>
</html>