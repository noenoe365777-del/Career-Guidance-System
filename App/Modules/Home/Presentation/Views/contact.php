<?php
$pageTitle = 'Contact Us - Career Guidance System';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-white via-indigo-50/30 to-slate-50 py-16 lg:py-24">
    <div class="absolute top-0 right-0 -z-10 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -z-10 h-96 w-96 rounded-full bg-purple-200/30 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-1.5 py-2 px-4 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 mb-6">
            Get in Touch
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
            Contact Information
        </h1>
        <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto font-medium mt-5 leading-relaxed">
            Choose a contact method below and we&apos;ll get back to you as soon as possible.
        </p>
    </div>
</section>

<!-- Contact Cards Grid -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 lg:py-20">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">

        <!-- Address Card -->
        <a href="<?= BASE_URL ?>/index.php?page=contact-address"
           class="group block bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8 transition-all duration-300 hover:border-purple-200 hover:shadow-2xl hover:shadow-purple-100/40 hover:-translate-y-1 no-underline">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 group-hover:bg-gradient-to-br group-hover:from-purple-600 group-hover:to-indigo-600 flex items-center justify-center transition-all duration-300 mb-6">
                <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Address</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">Visit our office in Yangon, Myanmar.</p>
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-purple-600 group-hover:gap-2.5 transition-all duration-300">
                View Details
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </span>
        </a>

        <!-- Phone Card -->
        <a href="<?= BASE_URL ?>/index.php?page=contact-phone"
           class="group block bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8 transition-all duration-300 hover:border-purple-200 hover:shadow-2xl hover:shadow-purple-100/40 hover:-translate-y-1 no-underline">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 group-hover:bg-gradient-to-br group-hover:from-purple-600 group-hover:to-indigo-600 flex items-center justify-center transition-all duration-300 mb-6">
                <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Phone</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">Speak with our support team directly.</p>
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-purple-600 group-hover:gap-2.5 transition-all duration-300">
                View Details
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </span>
        </a>

        <!-- Email Card -->
        <a href="<?= BASE_URL ?>/index.php?page=contact-email"
           class="group block bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8 transition-all duration-300 hover:border-purple-200 hover:shadow-2xl hover:shadow-purple-100/40 hover:-translate-y-1 no-underline">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 group-hover:bg-gradient-to-br group-hover:from-purple-600 group-hover:to-indigo-600 flex items-center justify-center transition-all duration-300 mb-6">
                <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.5a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Email</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">Send us a message and we&apos;ll respond quickly.</p>
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-purple-600 group-hover:gap-2.5 transition-all duration-300">
                View Details
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </span>
        </a>

    </div>
</section>