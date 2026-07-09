<?php
$questionCount = count($questions ?? []);
?>

<main class="flex-grow bg-[#f8fafc] w-full">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
        <a href="<?= BASE_URL ?>/index.php?page=<?= $backToPage ?? 'assessments' ?>"
           class="inline-flex items-center text-sm font-bold transition-colors hover:text-pink-700 <?= $accentClass ?? 'text-[#ec4899]' ?>">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Assessments
        </a>

        <div class="mt-5 rounded-xl border border-slate-200/60 bg-white p-6 shadow-sm md:p-8">
            <h1 class="text-2xl font-bold text-slate-900 md:text-3xl">
                Interest Assessment
            </h1>
            <p class="mt-2 text-sm font-medium text-slate-500">
                Answer the following <?= $questionCount ?> questions to discover careers that best match your interests.
            </p>
            <?php if (!empty($guestMode)): ?>
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                    You are taking this assessment as a guest. Your answers are stored in your browser session only until you sign in.
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-6 rounded-xl border border-slate-200/60 bg-white p-6 shadow-sm md:p-8">
            <h2 class="mb-6 text-sm font-bold uppercase tracking-wider text-[#ec4899]">
                Questions
            </h2>

            <form method="POST" class="space-y-6">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-6 w-6 shrink-0 select-none items-center justify-center rounded-full bg-[#ec4899] text-xs font-bold text-white">
                                <?= $index + 1 ?>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 md:text-base">
                                <?= htmlspecialchars($question['question'] ?? '') ?>
                            </h3>
                        </div>

                        <div class="ml-9 grid grid-cols-1 gap-2 rounded-xl border border-slate-200/70 bg-white p-1 shadow-xs sm:grid-cols-5">
                            <?php foreach ($question['options'] ?? [] as $opt): ?>
                                <label class="flex cursor-pointer select-none items-center gap-2 rounded-lg px-3 py-2.5 transition hover:bg-slate-50 group">
                                    <input type="radio"
                                           name="answers[<?= (int)($question['id'] ?? $index) ?>]"
                                           value="<?= (int)$opt['value'] ?>"
                                           <?= (int)$opt['value'] === 5 ? 'required' : '' ?>
                                           class="h-4 w-4 border-slate-300 text-[#ec4899] focus:ring-[#ec4899]">
                                    <span class="text-xs font-medium text-slate-500 transition-colors group-hover:text-slate-800">
                                        <?= htmlspecialchars($opt['label']) ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="flex justify-center pt-6">
                    <button type="submit"
                            class="rounded-xl px-8 py-3 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] <?= $buttonClass ?? 'bg-[#ec4899] hover:bg-pink-700' ?>">
                        Submit Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>