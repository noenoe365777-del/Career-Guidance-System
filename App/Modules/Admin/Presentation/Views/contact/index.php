<?php
$errors = $errors ?? [];
$success = $success ?? null;
$old = $old ?? [];

$pageTitle = 'Contact Us';
$headerTitle = 'Contact Us';
$activeMenu = 'contact';

$subjects = [
    'General Inquiry',
    'Technical Support',
    'Account Issue',
    'Assessment Issue',
    'Career Recommendation',
    'Feedback',
    'Other',
];

ob_start();
?>
<div class="px-4 sm:px-8 lg:px-10 py-8 space-y-8 flex-1 max-w-[1600px] w-full mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Contact Us</h2>
            <p class="text-slate-500 text-sm mt-1">Have a question or need assistance? Send us a message and we'll get back to you as soon as possible.</p>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500"></i>
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="p-4 sm:p-6">
                <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-contact" novalidate>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input id="full_name" type="text" name="full_name" value="<?= htmlspecialchars((string)($old['full_name'] ?? '')) ?>" placeholder="Your full name" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 <?= isset($errors['full_name']) ? 'border-red-500' : 'border-slate-300' ?>" required>
                            <?php if (!empty($errors['full_name'])): ?><p class="text-xs text-red-600 mt-1"><?= htmlspecialchars($errors['full_name']) ?></p><?php endif; ?>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input id="email" type="email" name="email" value="<?= htmlspecialchars((string)($old['email'] ?? '')) ?>" placeholder="you@example.com" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 <?= isset($errors['email']) ? 'border-red-500' : 'border-slate-300' ?>" required>
                            <?php if (!empty($errors['email'])): ?><p class="text-xs text-red-600 mt-1"><?= htmlspecialchars($errors['email']) ?></p><?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-5">
                        <label for="subject" class="block text-sm font-medium text-slate-700 mb-1">Subject <span class="text-red-500">*</span></label>
                        <select id="subject" name="subject" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white <?= isset($errors['subject']) ? 'border-red-500' : 'border-slate-300' ?>" required>
                            <option value="">Select a subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= htmlspecialchars($subject) ?>" <?= ((string)($old['subject'] ?? '') === $subject) ? 'selected' : '' ?>><?= htmlspecialchars($subject) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['subject'])): ?><p class="text-xs text-red-600 mt-1"><?= htmlspecialchars($errors['subject']) ?></p><?php endif; ?>
                    </div>

                    <div class="mt-5">
                        <label for="message" class="block text-sm font-medium text-slate-700 mb-1">Message <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="6" placeholder="Write your message here..." class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none <?= isset($errors['message']) ? 'border-red-500' : 'border-slate-300' ?>" required><?= htmlspecialchars((string)($old['message'] ?? '')) ?></textarea>
                        <?php if (!empty($errors['message'])): ?><p class="text-xs text-red-600 mt-1"><?= htmlspecialchars($errors['message']) ?></p><?php endif; ?>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm transition shadow-sm">
                            <i class="fas fa-paper-plane"></i>Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Contact Information</h3>
                <div class="space-y-5">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 shrink-0">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</p>
                            <a href="mailto:support@careerguidance.com" class="text-sm font-medium text-slate-800 hover:text-indigo-600 transition-colors">support@careerguidance.com</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 shrink-0">
                            <i class="fas fa-phone text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Phone</p>
                            <p class="text-sm font-medium text-slate-800">+959 679 343 479</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 shrink-0">
                            <i class="fas fa-map-marker-alt text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Address</p>
                            <p class="text-sm font-medium text-slate-800">Meiktila, Myanmar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
