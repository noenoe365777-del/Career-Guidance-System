<?php
$pageTitle = 'Phone Support - Career Guidance System';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-white via-indigo-50/30 to-slate-50 py-16 lg:py-24">
    <div class="absolute top-0 right-0 -z-10 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -z-10 h-96 w-96 rounded-full bg-purple-200/30 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-1.5 py-2 px-4 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 mb-6">
            Phone Support
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
            Speak With Our Team
        </h1>
        <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto font-medium mt-5 leading-relaxed">
            We&apos;re available during office hours. Call us for immediate support.
        </p>
    </div>
</section>

<!-- Phone Content -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 lg:py-20">

    <!-- Back + Breadcrumb -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="<?= BASE_URL ?>/index.php?page=contact"
           class="inline-flex items-center gap-2 text-sm font-semibold text-purple-600 hover:text-purple-700 transition-colors group">
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
            <span class="text-slate-700">Phone</span>
        </nav>
    </div>

    <div class="max-w-xl mx-auto space-y-8">

        <!-- Phone Number Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8 sm:p-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-purple-200/50 flex-shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">Main Line</h3>
                    <p class="text-slate-500 text-sm">Available during office hours</p>
                </div>
            </div>

            <!-- Phone Number Display -->
            <div class="p-6 rounded-2xl bg-slate-50/70 border border-slate-100 mb-8">
                <a href="tel:+959679343479"
                   class="block text-3xl sm:text-4xl font-bold text-slate-800 hover:text-purple-600 transition-colors text-center no-underline font-mono tracking-tight">
                    +959 679 343 479
                </a>
            </div>

            <!-- Call Now Button -->
            <a href="tel:+959679343479"
               class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 no-underline">
                <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                </svg>
                <span>Call Now</span>
            </a>
        </div>

        <!-- Office Hours Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8 sm:p-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-purple-200/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Availability</h3>
                    <p class="text-slate-500 text-sm">When to call</p>
                </div>
            </div>
            <div class="space-y-4 text-sm">
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-slate-500">Monday – Friday</span>
                    <span class="font-semibold text-slate-800">9:00 AM – 6:00 PM</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-slate-500">Saturday</span>
                    <span class="font-semibold text-slate-800">10:00 AM – 2:00 PM</span>
                </div>
                <div class="flex justify-between items-center py-2.5">
                    <span class="text-slate-500">Sunday</span>
                    <span class="font-semibold text-slate-800">Closed</span>
                </div>
            </div>
        </div>

    </div>
</section>