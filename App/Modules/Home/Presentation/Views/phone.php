<?php
$pageTitle = 'Phone Support - Career Guidance System';
?>

<!-- Hero Section -->
<section class="relative bg-white py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-1.5 py-2 px-4 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100 mb-4">
            Phone Support
        </span>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-[#0f2d59] leading-[1.12]">
            Speak With Our Team
        </h1>
        <p class="mt-3 text-sm text-slate-500 max-w-xl mx-auto font-medium leading-relaxed">
            We're available during office hours. Call us for immediate support.
        </p>
    </div>
</section>

<!-- Phone Content -->
<section class="py-8 lg:py-12 bg-white">

    <!-- Back + Breadcrumb -->
    <div class="max-w-xl mx-auto mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4">
        <a href="<?= BASE_URL ?>/index.php?page=contact"
           class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back to Contact</span>
        </a>
        <nav class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
            <a href="<?= BASE_URL ?>/index.php?page=home" class="hover:text-slate-600 transition-colors">Home</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <a href="<?= BASE_URL ?>/index.php?page=contact" class="hover:text-slate-600 transition-colors">Contact</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-[#0f2d59]">Phone</span>
        </nav>
    </div>

    <div class="max-w-xl mx-auto px-4 space-y-6">

        <!-- Phone Number Card -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-md p-5 sm:p-6">
            <div class="flex items-center gap-4 mb-7">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200/50 flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-[#0f2d59]">Main Line</h3>
                    <p class="text-slate-500 text-sm">Available during office hours</p>
                </div>
            </div>

            <!-- Phone Number Display -->
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-100 mb-7">
                <a href="tel:+959679343479"
                   class="block text-2xl sm:text-3xl font-bold text-[#0f2d59] hover:text-blue-600 transition-colors text-center no-underline font-mono tracking-tight">
                    +959 679 343 479
                </a>
            </div>

            <!-- Call Now Button -->
            <a href="tel:+959679343479"
               class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 no-underline">
                <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                </svg>
                <span>Call Now</span>
            </a>
        </div>

    </div>

</section>