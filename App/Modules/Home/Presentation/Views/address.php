<?php
$pageTitle = 'Our Address - Career Guidance System';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-white via-indigo-50/30 to-slate-50 py-16 lg:py-24">
    <div class="absolute top-0 right-0 -z-10 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -z-10 h-96 w-96 rounded-full bg-purple-200/30 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-1.5 py-2 px-4 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 mb-6">
            Our Location
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
            Visit Our Office
        </h1>
        <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto font-medium mt-5 leading-relaxed">
            We welcome you to visit us during office hours. Find our location below.
        </p>
    </div>
</section>

<!-- Address Content -->
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
            <span class="text-slate-700">Address</span>
        </nav>
    </div>

    <div class="grid lg:grid-cols-2 gap-8 lg:gap-10 items-start max-w-5xl mx-auto">

        <!-- Left: Large Embedded Google Map -->
        <div>
            <div class="bg-white border border-slate-100 shadow-xl shadow-slate-200/40 rounded-2xl overflow-hidden">
                <div class="rounded-2xl overflow-hidden" style="height: 500px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d120767.18359504924!2d96.1777!3d16.8714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c1eb142b698b39%3A0x6b3f5e7e4b0e5c7a!2sYangon%2C%20Myanmar!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s"
                        width="100%"
                        height="100%"
                        style="border:0; display:block;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Right: Details Cards -->
        <div class="space-y-6">

            <!-- Office Address Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-purple-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Office Address</h3>
                        <p class="text-slate-500 text-sm">Career Guidance System</p>
                    </div>
                </div>
                <address class="text-slate-600 text-sm leading-relaxed not-italic">
                    Yangon<br>
                    Myanmar
                </address>
            </div>

            <!-- Office Hours Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-purple-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Office Hours</h3>
                        <p class="text-slate-500 text-sm">When to visit</p>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-slate-100 last:border-0">
                        <span class="text-slate-500">Monday – Friday</span>
                        <span class="font-semibold text-slate-800">9:00 AM – 6:00 PM</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100 last:border-0">
                        <span class="text-slate-500">Saturday</span>
                        <span class="font-semibold text-slate-800">10:00 AM – 2:00 PM</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-500">Sunday</span>
                        <span class="font-semibold text-slate-800">Closed</span>
                    </div>
                </div>
            </div>

            <!-- Get Directions Button -->
            <a href="https://maps.google.com/?q=Yangon+Myanmar" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 w-full px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 justify-center no-underline">
                <svg class="w-4 h-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <span>Get Directions</span>
                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>

        </div>

    </div>
</section>