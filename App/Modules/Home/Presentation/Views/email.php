<?php
$pageTitle = 'Send a Message - Career Guidance System';
$errors = $errors ?? [];
$success = $success ?? null;
$old = $old ?? [];

$subjects = [
    'General Inquiry',
    'Career Guidance',
    'Technical Support',
    'Assessment Help',
    'Account Issue',
    'Report a Problem',
    'Feedback',
    'Other',
];
?>

<section class="py-6 lg:py-8 bg-white">
    <div class="relative max-w-5xl mx-auto px-4 text-center">

        <span class="inline-flex items-center px-3 py-1 rounded-full
                     text-[11px] font-semibold
                     bg-blue-50 text-blue-600 border border-blue-100 mb-4">
            Contact
        </span>

        <h1 class="text-2xl sm:text-3xl lg:text-4xl
                   font-bold text-slate-900">
            Send Us a Message
        </h1>

        <p class="mt-3 text-sm text-slate-500 max-w-xl mx-auto">
            Have a question? Fill out the form below and we'll reply as soon as possible.
        </p>

    </div>
</section>

<!-- Form Section -->
<section class="py-6 lg:py-8 bg-slate-50">

    <!-- Back + Breadcrumb -->
    <div class="max-w-2xl mx-auto mb-6 px-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
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
            <span class="text-[#0f2d59]">Email</span>
        </nav>
    </div>

    <div class="max-w-2xl mx-auto px-4">

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl text-sm flex items-center gap-3 shadow-sm animate-fade-in">
                <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <span class="font-medium"><?= htmlspecialchars($success) ?></span>
            </div>
        <?php endif; ?>

        <!-- Contact Form Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-5 sm:p-7">

            <!-- Form Header -->
            <div class="flex items-center gap-4 mb-6">
                <div class="w-13 h-13 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200/50 flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-[#0f2d59]">Contact Form</h3>
                    <p class="text-slate-500 text-sm">We'll respond within 24 hours</p>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/index.php?page=contact-email" method="POST" class="space-y-4" novalidate>
                <!-- Name & Email Row -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="full_name" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Full Name</label>
                        <input type="text"
                               id="full_name"
                               name="full_name"
                               required
                               placeholder="Enter your full name"
                               value="<?= htmlspecialchars((string)($old['full_name'] ?? '')) ?>"
                               class="w-full h-12 px-4 rounded-xl border transition-all duration-200
                               <?php if (isset($errors['full_name'])): ?>
                                   border-red-400 bg-red-50 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200
                               <?php else: ?>
                                   border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100
                               <?php endif; ?>
                               text-sm font-medium text-[#0f2d59] placeholder-slate-400 focus:outline-none">
                        <?php if (!empty($errors['full_name'])): ?>
                            <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['full_name']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Email Address</label>
                        <input type="email"
                               id="email"
                               name="email"
                               required
                               placeholder="you@example.com"
                               value="<?= htmlspecialchars((string)($old['email'] ?? '')) ?>"
                               class="w-full h-12 px-4 rounded-xl border transition-all duration-200
                               <?php if (isset($errors['email'])): ?>
                                   border-red-400 bg-red-50 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200
                               <?php else: ?>
                                   border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100
                               <?php endif; ?>
                               text-sm font-medium text-[#0f2d59] placeholder-slate-400 focus:outline-none">
                        <?php if (!empty($errors['email'])): ?>
                            <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Subject -->
                <div class="space-y-1.5">
                    <label for="subject" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Subject</label>
                    <select id="subject"
                            name="subject"
                            required
                            class="w-full h-12 px-4 rounded-xl border appearance-none transition-all duration-200
                            <?php if (isset($errors['subject'])): ?>
                                border-red-400 bg-red-50 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200
                            <?php else: ?>
                                border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100
                            <?php endif; ?>
                            text-sm font-medium text-[#0f2d59] focus:outline-none cursor-pointer">
                        <option value="" disabled <?= empty($old['subject']) ? 'selected' : '' ?>>Select a subject</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= htmlspecialchars($subject) ?>" <?= ((string)($old['subject'] ?? '') === $subject) ? 'selected' : '' ?>><?= htmlspecialchars($subject) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['subject'])): ?>
                        <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['subject']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Message -->
                <div class="space-y-1.5">
                    <label for="message" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Message</label>
                    <textarea id="message"
                              name="message"
                              rows="5"
                              required
                              placeholder="Please write your message here..."
                              class="w-full px-4 py-4 rounded-xl border resize-none transition-all duration-200
                              <?php if (isset($errors['message'])): ?>
                                  border-red-400 bg-red-50 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200
                              <?php else: ?>
                                  border-slate-200 bg-slate-50/70 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100
                              <?php endif; ?>
                              text-sm font-medium text-[#0f2d59] placeholder-slate-400 focus:outline-none"
                    ><?= htmlspecialchars((string)($old['message'] ?? '')) ?></textarea>
                    <?php if (!empty($errors['message'])): ?>
                        <p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['message']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full h-12 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>Send Message</span>
                </button>
            </form>

        </div>
    </div>
</section>