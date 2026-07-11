<?php
$pageTitle = 'Contact Us - Career Guidance System';
$errors = $errors ?? [];
$success = $success ?? null;
$old = $old ?? [];

$hasFormData = !empty($old) || !empty($errors);

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

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<section class="relative overflow-hidden bg-gradient-to-b from-white via-indigo-50/20 to-slate-50 py-20 lg:py-28">
    <div class="absolute top-0 right-0 -z-10 h-72 w-72 rounded-full bg-indigo-200/30 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -z-10 h-96 w-96 rounded-full bg-blue-200/20 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-1.5 py-1.5 px-3.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 mb-5">
            Get in Touch
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#0f2d59] leading-[1.12]">
            We are here to assist you.
        </h1>
        <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto font-medium">
            Choose a contact method below and we'll get back to you as soon as possible.
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 lg:py-16">

    <?php if ($success): ?>
        <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl text-sm flex items-center gap-3 shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <span class="font-medium"><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <div x-data="{ activeTab: '<?= $hasFormData ? 'email' : 'address' ?>' }" class="grid lg:grid-cols-5 gap-8 lg:gap-10 items-start">

        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-3">Contact Information</h3>
                <p class="text-slate-500 font-medium leading-relaxed text-sm">
                    Reach out through any of the options below and we'll get back to you as soon as possible.
                </p>
            </div>

            <div class="space-y-3">
                <button @click="activeTab = 'address'"
                        :class="activeTab === 'address' ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white border-transparent shadow-lg shadow-indigo-200/50' : 'bg-white text-slate-700 border-slate-200/60 hover:border-indigo-200 hover:shadow-md hover:-translate-y-0.5'"
                        class="w-full flex items-start gap-4 p-5 rounded-2xl border shadow-sm transition-all duration-300 text-left group">
                    <div :class="activeTab === 'address' ? 'bg-white/20 text-white' : 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white'"
                         class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-all duration-300">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider mb-0.5" :class="activeTab === 'address' ? 'text-indigo-200' : 'text-slate-400'">Address</p>
                        <p class="text-sm font-semibold truncate" :class="activeTab === 'address' ? 'text-white' : 'text-slate-800'">Yangon, Myanmar</p>
                    </div>
                    <i class="fas fa-chevron-right text-xs self-center transition-all duration-300" :class="activeTab === 'address' ? 'text-white translate-x-1' : 'text-slate-300 group-hover:translate-x-1'"></i>
                </button>

                <button @click="activeTab = 'phone'"
                        :class="activeTab === 'phone' ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white border-transparent shadow-lg shadow-indigo-200/50' : 'bg-white text-slate-700 border-slate-200/60 hover:border-indigo-200 hover:shadow-md hover:-translate-y-0.5'"
                        class="w-full flex items-start gap-4 p-5 rounded-2xl border shadow-sm transition-all duration-300 text-left group">
                    <div :class="activeTab === 'phone' ? 'bg-white/20 text-white' : 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white'"
                         class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-all duration-300">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider mb-0.5" :class="activeTab === 'phone' ? 'text-indigo-200' : 'text-slate-400'">Phone</p>
                        <p class="text-sm font-semibold truncate" :class="activeTab === 'phone' ? 'text-white' : 'text-slate-800'">+959 679 343 479</p>
                    </div>
                    <i class="fas fa-chevron-right text-xs self-center transition-all duration-300" :class="activeTab === 'phone' ? 'text-white translate-x-1' : 'text-slate-300 group-hover:translate-x-1'"></i>
                </button>

                <button @click="activeTab = 'email'"
                        :class="activeTab === 'email' ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white border-transparent shadow-lg shadow-indigo-200/50' : 'bg-white text-slate-700 border-slate-200/60 hover:border-indigo-200 hover:shadow-md hover:-translate-y-0.5'"
                        class="w-full flex items-start gap-4 p-5 rounded-2xl border shadow-sm transition-all duration-300 text-left group">
                    <div :class="activeTab === 'email' ? 'bg-white/20 text-white' : 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white'"
                         class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-all duration-300">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider mb-0.5" :class="activeTab === 'email' ? 'text-indigo-200' : 'text-slate-400'">Email</p>
                        <p class="text-sm font-semibold truncate" :class="activeTab === 'email' ? 'text-white' : 'text-slate-800'">noenoe365777@gmail.com</p>
                    </div>
                    <i class="fas fa-chevron-right text-xs self-center transition-all duration-300" :class="activeTab === 'email' ? 'text-white translate-x-1' : 'text-slate-300 group-hover:translate-x-1'"></i>
                </button>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white border border-slate-100 shadow-xl shadow-slate-200/40 rounded-[2rem] p-8 sm:p-10 min-h-[420px] relative overflow-hidden">

                <div x-show="activeTab === 'address'"
                     x-transition:enter="transition-all duration-500 ease-in-out"
                     x-transition:enter-start="opacity-0 translate-x-6 scale-95"
                     x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                     x-transition:leave="transition-all duration-300 ease-in-out"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-x-6 scale-95"
                     class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-200/50">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">Our Address</h3>
                            <p class="text-slate-500 text-sm">Visit us at our office</p>
                        </div>
                    </div>
                    <div class="p-6 rounded-2xl bg-slate-50/70 border border-slate-100 space-y-3">
                        <p class="text-slate-700 font-semibold text-base">Career Guidance System</p>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Yangon<br>
                            Myanmar
                        </p>
                        <div class="pt-3 border-t border-slate-200/60">
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-2">Office Hours</p>
                            <div class="space-y-1.5 text-sm">
                                <div class="flex justify-between"><span class="text-slate-500">Mon – Fri</span><span class="font-semibold text-slate-800">9:00 AM – 6:00 PM</span></div>
                                <div class="flex justify-between"><span class="text-slate-500">Saturday</span><span class="font-semibold text-slate-800">10:00 AM – 2:00 PM</span></div>
                                <div class="flex justify-between"><span class="text-slate-500">Sunday</span><span class="font-semibold text-slate-800">Closed</span></div>
                            </div>
                        </div>
                    </div>
                    <a href="https://maps.google.com/?q=Yangon+Myanmar" target="_blank"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors group">
                        <i class="fas fa-external-link-alt text-xs"></i>
                        <span>View on Google Maps</span>
                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div x-show="activeTab === 'phone'"
                     x-transition:enter="transition-all duration-500 ease-in-out"
                     x-transition:enter-start="opacity-0 translate-x-6 scale-95"
                     x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                     x-transition:leave="transition-all duration-300 ease-in-out"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-x-6 scale-95"
                     class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200/50">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">Phone Support</h3>
                            <p class="text-slate-500 text-sm">Speak with our team</p>
                        </div>
                    </div>
                    <div class="p-6 rounded-2xl bg-slate-50/70 border border-slate-100 space-y-4">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Main Line</p>
                            <a href="tel:+959679343479" class="text-lg font-bold text-slate-800 hover:text-indigo-600 transition-colors">+959 679 343 479</a>
                        </div>
                        <div class="pt-4 border-t border-slate-200/60">
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-2">Availability</p>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Our phone support team is available during business hours. For urgent matters, please leave a voicemail and we'll return your call promptly.
                            </p>
                        </div>
                    </div>
                    <a href="tel:+959679343479"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors group">
                        <i class="fas fa-phone-alt text-xs"></i>
                        <span>Call now</span>
                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div x-show="activeTab === 'email'"
                     x-transition:enter="transition-all duration-500 ease-in-out"
                     x-transition:enter-start="opacity-0 translate-x-6 scale-95"
                     x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                     x-transition:leave="transition-all duration-300 ease-in-out"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-x-6 scale-95"
                     class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200/50">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">Send us a message</h3>
                            <p class="text-slate-500 text-sm">We'll get back to you within 24 hours</p>
                        </div>
                    </div>

                    <form action="<?= BASE_URL ?>/index.php?page=contact" method="POST" class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="full_name" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Your Name</label>
                                <input type="text" id="full_name" name="full_name" required placeholder="Enter your full name"
                                    value="<?= htmlspecialchars((string)($old['full_name'] ?? '')) ?>"
                                    class="w-full h-12 px-4 rounded-xl border <?= isset($errors['full_name']) ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50/70' ?> text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                                <?php if (!empty($errors['full_name'])): ?><p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['full_name']) ?></p><?php endif; ?>
                            </div>
                            <div class="space-y-1.5">
                                <label for="email" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Email Address</label>
                                <input type="email" id="email" name="email" required placeholder="Enter your email address"
                                    value="<?= htmlspecialchars((string)($old['email'] ?? '')) ?>"
                                    class="w-full h-12 px-4 rounded-xl border <?= isset($errors['email']) ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50/70' ?> text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                                <?php if (!empty($errors['email'])): ?><p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['email']) ?></p><?php endif; ?>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="subject" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Inquiry Subject</label>
                            <select id="subject" name="subject" required
                                class="w-full h-12 px-4 rounded-xl border <?= isset($errors['subject']) ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50/70' ?> text-sm font-medium text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                                <option value="" disabled <?= empty($old['subject']) ? 'selected' : '' ?>>Select a subject</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= htmlspecialchars($subject) ?>" <?= ((string)($old['subject'] ?? '') === $subject) ? 'selected' : '' ?>><?= htmlspecialchars($subject) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errors['subject'])): ?><p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['subject']) ?></p><?php endif; ?>
                        </div>

                        <div class="space-y-1.5">
                            <label for="message" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Message</label>
                            <textarea id="message" name="message" rows="5" required placeholder="Please write your questions here..."
                                class="w-full p-4 rounded-xl border <?= isset($errors['message']) ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50/70' ?> text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all resize-none"><?= htmlspecialchars((string)($old['message'] ?? '')) ?></textarea>
                            <?php if (!empty($errors['message'])): ?><p class="text-xs text-red-600 mt-1 font-medium"><?= htmlspecialchars($errors['message']) ?></p><?php endif; ?>
                        </div>

                        <button type="submit"
                            class="w-full h-12 bg-gradient-to-r from-brand-start via-brand-mid to-brand-end text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2">
                            Send Message <i class="fas fa-paper-plane text-[10px] opacity-80"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</section>
