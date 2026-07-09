<?php
$errors = $errors ?? [];
$old = $old ?? [];
$options = $options ?? [];
$assessments = $assessments ?? [];
$questionTypes = $questionTypes ?? [];

$pageTitle = 'Edit Question';
$headerTitle = 'Edit Question';
$activeMenu = 'questions';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Edit Question</h1>
        <p class="text-sm text-slate-500 m-0 mt-1">Update this question and its answer options.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/index.php?page=admin-questions"
           class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 no-underline">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <form method="post" action="<?= BASE_URL ?>/index.php?page=admin-questions-update" class="space-y-5" id="questionForm">
            <input type="hidden" name="id" value="<?= (int)($old['question_id'] ?? 0) ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="question_text">Question Text <span class="text-red-400">*</span></label>
                    <textarea id="question_text" name="question_text" rows="3"
                              class="block w-full px-4 py-2.5 text-sm bg-white border <?= isset($errors['question_text']) ? 'border-red-300' : 'border-slate-200' ?> rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                              placeholder="Enter the question text..."><?= htmlspecialchars((string)($old['question_text'] ?? '')) ?></textarea>
                    <?php if (isset($errors['question_text'])): ?>
                        <p class="mt-1.5 text-xs font-medium text-red-500"><?= htmlspecialchars($errors['question_text']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="assessment_id">Assessment <span class="text-red-400">*</span></label>
                    <select id="assessment_id" name="assessment_id"
                            class="block w-full px-4 py-2.5 text-sm bg-white border <?= isset($errors['assessment_id']) ? 'border-red-300' : 'border-slate-200' ?> rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                        <option value="">Select assessment...</option>
                        <?php foreach ($assessments as $a): ?>
                            <option value="<?= (int)($a['assessment_id'] ?? 0) ?>" <?= ((int)($old['assessment_id'] ?? 0) === (int)($a['assessment_id'] ?? 0)) ? 'selected' : '' ?>><?= htmlspecialchars((string)($a['title'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['assessment_id'])): ?>
                        <p class="mt-1.5 text-xs font-medium text-red-500"><?= htmlspecialchars($errors['assessment_id']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="question_type">Question Type</label>
                    <select id="question_type" name="question_type"
                            class="block w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150">
                        <?php foreach ($questionTypes as $key => $label): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= ((string)($old['question_type'] ?? 'single_choice') === $key) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="question_order">Order Number <span class="text-red-400">*</span></label>
                    <input type="number" id="question_order" name="question_order" min="1"
                           class="block w-full px-4 py-2.5 text-sm bg-white border <?= isset($errors['question_order']) ? 'border-red-300' : 'border-slate-200' ?> rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150"
                           value="<?= (int)($old['question_order'] ?? 1) ?>">
                    <?php if (isset($errors['question_order'])): ?>
                        <p class="mt-1.5 text-xs font-medium text-red-500"><?= htmlspecialchars($errors['question_order']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-bold text-slate-800 m-0">Answer Options</h3>
                    <button type="button" onclick="addOptionRow()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-all duration-150 border-0 outline-none cursor-pointer">
                        <i class="bi bi-plus-lg text-xs"></i>
                        Add Option
                    </button>
                </div>
                <?php if (isset($errors['options'])): ?>
                    <p class="text-xs font-medium text-red-500 mb-3"><?= htmlspecialchars($errors['options']) ?></p>
                <?php endif; ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse align-middle" id="optionsTable">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50">
                                <th class="whitespace-nowrap px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider" style="width:40px">#</th>
                                <th class="whitespace-nowrap px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Option Text <span class="text-red-400">*</span></th>
                                <th class="whitespace-nowrap px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center" style="width:100px">Value</th>
                                <th class="whitespace-nowrap px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center" style="width:80px">Order</th>
                                <th class="whitespace-nowrap px-4 py-3 text-center" style="width:40px"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm" id="optionsBody">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-150 border-0 outline-none">
                    <i class="bi bi-check-lg mr-2"></i>
                    Update Question
                </button>
                <a href="<?= BASE_URL ?>/index.php?page=admin-questions"
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all duration-150 no-underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    var optionIndex = 0;

    function addOptionRow(text, value, order) {
        var tbody = document.getElementById('optionsBody');
        var tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/40 transition-colors duration-150';
        tr.dataset.index = optionIndex;
        tr.innerHTML =
            '<td class="px-4 py-2 text-slate-400 text-center option-number">' + (optionIndex + 1) + '</td>' +
            '<td class="px-4 py-2"><input type="text" name="option_text[' + optionIndex + ']" value="' + (text || '') + '" class="block w-full px-3 py-1.5 text-sm bg-white border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150" placeholder="Option text" required></td>' +
            '<td class="px-4 py-2 text-center"><input type="number" name="option_value[' + optionIndex + ']" value="' + (value !== undefined ? value : '') + '" step="0.01" min="0" class="block w-full px-3 py-1.5 text-sm bg-white border border-slate-200 rounded-lg text-slate-800 text-center focus:outline-none focus:ring-2 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150" placeholder="0.00"></td>' +
            '<td class="px-4 py-2 text-center"><input type="number" name="option_order[' + optionIndex + ']" value="' + (order || (optionIndex + 1)) + '" min="1" class="block w-full px-3 py-1.5 text-sm bg-white border border-slate-200 rounded-lg text-slate-800 text-center focus:outline-none focus:ring-2 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-150" style="width:60px"></td>' +
            '<td class="px-4 py-2 text-center"><button type="button" onclick="removeOptionRow(this)" class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-150 border-0 bg-transparent outline-none p-0 cursor-pointer"><i class="bi bi-x text-sm"></i></button></td>';
        tbody.appendChild(tr);
        renumberOptions();
        optionIndex++;
    }

    function removeOptionRow(btn) {
        var tr = btn.closest('tr');
        tr.parentNode.removeChild(tr);
        renumberOptions();
    }

    function renumberOptions() {
        var rows = document.querySelectorAll('#optionsBody tr');
        rows.forEach(function(row, i) {
            row.querySelector('.option-number').textContent = i + 1;
        });
    }

    <?php if (empty($options)): ?>
    addOptionRow('Strongly Agree', '5.00', 1);
    addOptionRow('Agree', '4.00', 2);
    addOptionRow('Neutral', '3.00', 3);
    addOptionRow('Disagree', '2.00', 4);
    addOptionRow('Strongly Disagree', '1.00', 5);
    <?php else: ?>
    <?php foreach ($options as $opt): ?>
    addOptionRow(<?= json_encode($opt['option_text'] ?? '') ?>, <?= json_encode((string)($opt['option_value'] ?? '0')) ?>, <?= json_encode((string)($opt['option_order'] ?? 1)) ?>);
    <?php endforeach; ?>
    <?php endif; ?>
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
