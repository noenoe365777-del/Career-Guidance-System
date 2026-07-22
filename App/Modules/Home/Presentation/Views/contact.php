<?php
$pageTitle = 'Contact Us - Career Guidance System';
$errors = $errors ?? [];
$success = $success ?? null;
$old = $old ?? [];
?>

<style>
    .cd-panel { opacity:0; transition:opacity 220ms ease-in-out; position:absolute; inset:0; pointer-events:none; }
    .cd-panel.cd-active { opacity:1; position:relative; pointer-events:auto; }

    @keyframes cd-slideUp {
        from { opacity:0; transform:translateY(24px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .cd-enter {
        opacity: 0;
        animation: cd-slideUp 0.5s cubic-bezier(0.22,1,0.36,1) forwards;
    }
    .cd-enter-d1 { animation-delay: 0.05s; }
    .cd-enter-d2 { animation-delay: 0.15s; }
    .cd-enter-d3 { animation-delay: 0.25s; }
    .cd-enter-d4 { animation-delay: 0.35s; }
    .cd-enter-d5 { animation-delay: 0.45s; }
    .cd-enter-d6 { animation-delay: 0.55s; }
    .cd-enter-d7 { animation-delay: 0.65s; }
    .cd-enter-d8 { animation-delay: 0.75s; }
</style>

<section class="bg-white py-6 lg:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="cd-enter cd-enter-d1 inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100 mb-3">
            Get in Touch
        </span>
        <h1 class="cd-enter cd-enter-d2 text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-[#0f2d59]">
            Contact Us
        </h1>
        <p class="cd-enter cd-enter-d3 text-sm text-slate-500 max-w-lg mx-auto font-medium mt-2">
            Reach out through any of these channels. We would love to hear from you.
        </p>
    </div>
</section>

<section class="bg-white pb-8 lg:pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid lg:grid-cols-5 gap-6 lg:gap-8 lg:items-stretch">

            <!-- Left Column: Navigation Buttons -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-2 lg:grid-cols-1 lg:flex lg:flex-col lg:justify-between lg:h-full gap-3" id="cd-nav">

                    <button type="button" data-panel="address"
                            class="cd-enter cd-enter-d4 cd-btn active-btn flex items-start gap-4 bg-white rounded-xl border-2 border-l-4 border-slate-100 border-l-blue-600 shadow-md p-4 text-left transition-all duration-200 hover:shadow-lg group focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-[#0f2d59]">Address</h3>
                            <p class="text-slate-500 text-xs leading-relaxed mt-0.5">Yangon, Myanmar</p>
                        </div>
                    </button>

                    <button type="button" data-panel="phone"
                            class="cd-enter cd-enter-d5 cd-btn flex items-start gap-4 bg-white rounded-xl border-2 border-l-4 border-slate-100 border-l-transparent shadow-md p-4 text-left transition-all duration-200 hover:shadow-lg group focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-[#0f2d59]">Phone</h3>
                            <p class="text-slate-500 text-xs leading-relaxed mt-0.5">Speak with our support team</p>
                        </div>
                    </button>

                    <button type="button" data-panel="email"
                            class="cd-btn flex items-start gap-4 bg-white rounded-xl border-2 border-l-4 border-slate-100 border-l-transparent shadow-md p-4 text-left transition-all duration-200 hover:shadow-lg group focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.5a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-[#0f2d59]">Email</h3>
                            <p class="text-slate-500 text-xs leading-relaxed mt-0.5">Send us a message anytime</p>
                        </div>
                    </button>

                    <button type="button" data-panel="hours"
                            class="cd-btn flex items-start gap-4 bg-white rounded-xl border-2 border-l-4 border-slate-100 border-l-transparent shadow-md p-4 text-left transition-all duration-200 hover:shadow-lg group focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-[#0f2d59]">Office Hours</h3>
                            <p class="text-slate-500 text-xs leading-relaxed mt-0.5">Mon - Fri, 9 AM - 6 PM</p>
                        </div>
                    </button>

                </div>
            </div>

            <!-- Right Column: Detail Panels -->
            <div class="lg:col-span-3">
                <div class="h-full bg-white rounded-xl border border-slate-100 shadow-md p-5 sm:p-6 min-h-[420px] relative">

                    <!-- ADDRESS PANEL -->
                    <div id="panel-address" class="cd-panel cd-active" role="tabpanel">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-[#0f2d59]">Our Address</h2>
                                <p class="text-slate-500 text-xs">Visit us during office hours</p>
                            </div>
                        </div>
                        <div class="rounded-xl overflow-hidden border border-slate-100 mb-5 h-56">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d120767.18359504924!2d96.1777!3d16.8714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c1eb142b698b39%3A0x6b3f5e7e4b0e5c7a!2sYangon%2C%20Myanmar!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" width="100%" height="100%" style="border:0; display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-slate-50/70 rounded-xl border border-slate-100 p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <h3 class="text-sm font-bold text-[#0f2d59]">Office Address</h3>
                                </div>
                                <address class="text-slate-600 text-sm leading-relaxed not-italic">Yangon<br>Myanmar</address>
                            </div>
                            <a href="https://maps.google.com/?q=Yangon+Myanmar" target="_blank" rel="noopener noreferrer"
                               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-200 no-underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 2.499l1.25.75a2.25 2.25 0 002.25-2.25h-1.5m-1.5 0L18 15.75m-3.75-5.25l1.5 1.5M6.75 7.5l1.5 1.5M6.75 7.5L5.25 6m1.5 1.5l1.5 1.5m0-5.25L8.25 3.75" />
                                </svg>
                                Get Directions
                            </a>
                        </div>
                    </div>

                    <!-- PHONE PANEL -->
                    <div id="panel-phone" class="cd-panel" role="tabpanel">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-[#0f2d59]">Phone Support</h2>
                                <p class="text-slate-500 text-xs">Available during office hours</p>
                            </div>
                        </div>
                        <div class="bg-slate-50/70 rounded-2xl border border-slate-100 p-6 mb-5 text-center">
                            <a href="tel:+959679343479" class="block text-2xl sm:text-3xl font-bold text-[#0f2d59] hover:text-blue-600 transition-colors no-underline font-mono tracking-tight">
                                +959 679 343 479
                            </a>
                            <p class="text-slate-400 text-xs mt-2 font-medium">Main Support Line</p>
                        </div>
                        <a href="tel:+959679343479"
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-200 no-underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            Call Now
                        </a>
                        <div class="mt-5 space-y-3">
                            <div class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Immediate support during working hours</span>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Available Monday to Saturday</span>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>For urgent matters, leave a voicemail</span>
                            </div>
                        </div>
                    </div>

                    <!-- EMAIL PANEL -->
                    <div id="panel-email" class="cd-panel" role="tabpanel">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-[#0f2d59]">Send a Message</h2>
                                <p class="text-slate-500 text-xs">We will respond within 24 hours</p>
                            </div>
                        </div>

                        <?php if ($success): ?>
                            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                    </svg>
                                </div>
                                <span class="font-medium"><?= htmlspecialchars($success) ?></span>
                            </div>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>/index.php?page=contact-email#email" method="POST" class="space-y-3" novalidate>
                            <div class="grid sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="full_name" class="block text-xs font-semibold text-slate-700 mb-1">Full Name</label>
                                    <input type="text" id="full_name" name="full_name" required placeholder="Your name"
                                           value="<?= htmlspecialchars((string)($old['full_name'] ?? '')) ?>"
                                           class="w-full h-10 px-3 rounded-lg border text-sm font-medium text-[#0f2d59] placeholder-slate-400 focus:outline-none transition-all duration-200 <?= isset($errors['full_name']) ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-200' : 'border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100' ?>">
                                    <?php if (!empty($errors['full_name'])): ?>
                                        <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['full_name']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                                    <input type="email" id="email" name="email" required placeholder="you@example.com"
                                           value="<?= htmlspecialchars((string)($old['email'] ?? '')) ?>"
                                           class="w-full h-10 px-3 rounded-lg border text-sm font-medium text-[#0f2d59] placeholder-slate-400 focus:outline-none transition-all duration-200 <?= isset($errors['email']) ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-200' : 'border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100' ?>">
                                    <?php if (!empty($errors['email'])): ?>
                                        <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['email']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <label for="subject" class="block text-xs font-semibold text-slate-700 mb-1">Subject</label>
                                <select id="subject" name="subject" required
                                        class="w-full h-10 px-3 rounded-lg border appearance-none text-sm font-medium text-[#0f2d59] focus:outline-none cursor-pointer transition-all duration-200 <?= isset($errors['subject']) ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-200' : 'border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100' ?>">
                                    <option value="" disabled <?= empty($old['subject']) ? 'selected' : '' ?>>Select a subject</option>
                                    <?php foreach (['General Inquiry','Career Guidance','Technical Support','Assessment Help','Account Issue','Feedback'] as $s): ?>
                                        <option value="<?= htmlspecialchars($s) ?>" <?= ((string)($old['subject'] ?? '') === $s) ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($errors['subject'])): ?>
                                    <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['subject']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="message" class="block text-xs font-semibold text-slate-700 mb-1">Message</label>
                                <textarea id="message" name="message" rows="4" required placeholder="Write your message here..."
                                          class="w-full px-3 py-2.5 rounded-lg border resize-none text-sm font-medium text-[#0f2d59] placeholder-slate-400 focus:outline-none transition-all duration-200 <?= isset($errors['message']) ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-200' : 'border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100' ?>"><?= htmlspecialchars((string)($old['message'] ?? '')) ?></textarea>
                                <?php if (!empty($errors['message'])): ?>
                                    <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['message']) ?></p>
                                <?php endif; ?>
                            </div>
                            <button type="submit"
                                    class="w-full h-10 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-lg shadow-md hover:shadow-lg hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                <span>Send Message</span>
                            </button>
                        </form>
                    </div>

                    <!-- OFFICE HOURS PANEL -->
                    <div id="panel-hours" class="cd-panel" role="tabpanel">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-[#0f2d59]">Office Hours</h2>
                                <p class="text-slate-500 text-xs">Our regular working schedule</p>
                            </div>
                        </div>

                        <div class="space-y-0 mb-5">
                            <?php
                            $schedule = [
                                ['day' => 'Monday', 'hours' => '9:00 AM - 6:00 PM'],
                                ['day' => 'Tuesday', 'hours' => '9:00 AM - 6:00 PM'],
                                ['day' => 'Wednesday', 'hours' => '9:00 AM - 6:00 PM'],
                                ['day' => 'Thursday', 'hours' => '9:00 AM - 6:00 PM'],
                                ['day' => 'Friday', 'hours' => '9:00 AM - 6:00 PM'],
                                ['day' => 'Saturday', 'hours' => '10:00 AM - 2:00 PM'],
                                ['day' => 'Sunday', 'hours' => null],
                            ];
                            foreach ($schedule as $i => $row): ?>
                                <div class="flex justify-between items-center py-3 <?= $i < count($schedule) - 1 ? 'border-b border-slate-100' : '' ?>">
                                    <span class="text-sm text-slate-600 font-medium"><?= $row['day'] ?></span>
                                    <?php if ($row['hours']): ?>
                                        <span class="text-sm font-semibold text-[#0f2d59]"><?= $row['hours'] ?></span>
                                    <?php else: ?>
                                        <span class="text-sm font-semibold text-slate-400">Closed</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="bg-blue-50/60 rounded-xl border border-blue-100 p-4 mb-5">
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-[#0f2d59] mb-1">Important Notice</p>
                                    <p class="text-xs text-slate-500 leading-relaxed">Hours may vary during public holidays. Online services are available 24/7. Appointments are recommended for in-person visits.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="tel:+959679343479"
                               class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all no-underline group">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.061 12.061 0 01-7.143-7.143c-.166-.441.008-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-[#0f2d59]">Call Us</p>
                                    <p class="text-xs text-slate-500">+959 679 343 479</p>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var btns = document.querySelectorAll('.cd-btn');
    var panels = document.querySelectorAll('.cd-panel');
    var VALID = ['address','phone','email','hours'];
    var current = 'address';

    function getHash() {
        var h = window.location.hash.replace('#','');
        return VALID.indexOf(h) !== -1 ? h : null;
    }

    function switchTo(id) {
        if (id === current) return;
        current = id;

        btns.forEach(function (b) {
            var active = b.getAttribute('data-panel') === id;
            b.setAttribute('aria-selected', active ? 'true' : 'false');
            if (active) {
                b.classList.add('active-btn');
                b.classList.remove('inactive-btn');
                b.style.borderLeftColor = '';
                b.classList.add('border-l-blue-600');
                b.classList.remove('border-l-transparent');
            } else {
                b.classList.remove('active-btn');
                b.classList.add('inactive-btn');
                b.style.borderLeftColor = 'transparent';
                b.classList.remove('border-l-blue-600');
                b.classList.add('border-l-transparent');
            }
        });

        panels.forEach(function (p) {
            p.classList.remove('cd-active');
        });

        var target = document.getElementById('panel-' + id);
        if (target) {
            void target.offsetWidth;
            target.classList.add('cd-active');
        }

        history.replaceState(null, '', '#' + id);
    }

    btns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var panel = this.getAttribute('data-panel');
            switchTo(panel);
        });
    });

    var initial = getHash();
    if (initial && initial !== 'address') {
        switchTo(initial);
    }
});
</script>
