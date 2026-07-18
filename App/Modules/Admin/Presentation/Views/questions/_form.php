<?php
$mode = $mode ?? 'create';
$errors = $errors ?? [];
$old = $old ?? [];
$options = $options ?? [];
$assessments = $assessments ?? [];
$questionTypes = $questionTypes ?? [];
$questionId = $questionId ?? ($old['question_id'] ?? 0);

$isEdit = $mode === 'edit';
$pageTitle = $isEdit ? 'Edit Question' : 'Add Question';
$headerTitle = $pageTitle;
$activeMenu = 'questions';

if ($isEdit) {
    $formAction = BASE_URL . '/index.php?page=admin-questions-update';
    $submitLabel = 'Save Changes';
    $submitIcon = 'bi-check-lg';
    $pageSubtitle = 'Update the question and answer options.';
} else {
    $formAction = BASE_URL . '/index.php?page=admin-questions-store';
    $submitLabel = 'Create Question';
    $submitIcon = 'bi-check-lg';
    $pageSubtitle = 'Create a new question with answer options.';
}

ob_start();
?>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<style>
:root {
    --primary: #5B5FEF;
    --primary-light: #EEF0FF;
    --primary-dark: #4A4ED9;
    --primary-ring: rgba(91,95,239,0.18);
}

* { box-sizing: border-box; }

/* ---- Base ---- */
body { background: #f8fafc; }

/* ---- Inputs ---- */
.f-inp {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.82rem;
    color: #0f172a;
    background: #fff;
    transition: border-color 0.15s, box-shadow 0.15s;
    outline: none;
}
.f-inp:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-ring); }
.f-inp.err { border-color: #ef4444; }
.f-inp.err:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.15); }
.f-inp::placeholder { color: #94a3b8; }
textarea.f-inp { resize: vertical; min-height: 72px; }
select.f-inp {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.6rem center;
    background-size: 13px 10px;
    padding-right: 2rem;
}
.f-lbl {
    display: block;
    font-size: 0.72rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.3rem;
}
.err-msg {
    margin-top: 0.2rem;
    font-size: 0.68rem;
    font-weight: 500;
    color: #ef4444;
}

/* ---- TomSelect error state ---- */
.ts-wrapper.ts-has-error .ts-control {
    border-color: #ef4444;
}
.ts-wrapper.ts-has-error .ts-control:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,0.15);
}

/* ---- TomSelect: hide the original <select> so its border never shows ---- */
select.f-inp.ts-single,
select.f-inp.ts-has-error {
    position: absolute !important;
    opacity: 0 !important;
    height: 0 !important;
    width: 0 !important;
    pointer-events: none !important;
}

/* ---- TomSelect overrides ---- */
.ts-wrapper {
    border: none;
    box-shadow: none;
    outline: none;
}
.ts-wrapper .ts-control {
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.45rem 0.75rem;
    font-size: 0.82rem;
    min-height: 36px;
    box-shadow: none;
}
.ts-wrapper .ts-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-ring); }
.ts-wrapper.multi .ts-control .item {
    background: var(--primary-light);
    color: var(--primary);
    border: none;
    border-radius: 9999px;
    font-size: 0.75rem;
    padding: 0.08rem 0.5rem;
    margin: 1px;
}
.ts-wrapper.multi .ts-control .item .remove { color: var(--primary); border-color: var(--primary-light); padding: 0; }
.ts-dropdown {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    font-size: 0.82rem;
}
.ts-dropdown .option.active { background: var(--primary-light); color: var(--primary); }
.ts-dropdown .create:hover { background: var(--primary-light); color: var(--primary); }
.ts-wrapper.single .ts-control .item { color: #0f172a; }
.ts-wrapper.single .ts-control { padding-right: 2rem; }
.ts-wrapper.plugin-remove_button .item .remove { border-left-color: color-mix(in srgb, var(--primary-light) 60%, transparent); }

/* ---- Section cards ---- */
.sc {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eef1f5;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    overflow: visible;
}
.sc-h {
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid #f1f4f8;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}
.sc-h .icon-wrap {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 0.1rem;
}
.sc-h .icon-wrap i { font-size: 1rem; }
.sc-h-content { flex: 1; min-width: 0; }
.sc-h h2 { font-size: 0.85rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.3; }
.sc-h p { font-size: 0.75rem; color: #64748b; margin: 0.15rem 0 0; line-height: 1.4; }
.sc-b { padding: 1rem 1.25rem 1.25rem; }

/* ---- Form actions at bottom ---- */
.form-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.65rem;
    padding: 0.85rem 1.25rem;
    border-top: 1px solid #f0f2f5;
    background: #fafbfd;
    border-radius: 0 0 12px 12px;
}
.form-actions .btn:first-child {
    margin-right: auto;
}
.form-actions .btn {
    padding: 0.55rem 1.15rem;
}
.form-actions .btn-cancel {
    background: #fff;
    color: #475569;
    border: 1.5px solid #d5d9e0;
}
.form-actions .btn-cancel:hover {
    background: #f8fafc;
    border-color: #b0b7c3;
    color: #334155;
}
.form-actions .btn-submit {
    background: var(--primary);
    color: #fff;
    border: 1.5px solid var(--primary);
}
.form-actions .btn-submit:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

/* ---- Buttons ---- */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.12s;
    cursor: pointer;
    border: none;
    text-decoration: none;
    white-space: nowrap;
}
.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-dark); }
.btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
.btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
.btn-ghost { background: transparent; color: #64748b; }
.btn-ghost:hover { background: #f1f5f9; color: #334155; }
.btn-add-option {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--primary);
    background: var(--primary-light);
    border: none;
    cursor: pointer;
    transition: all 0.12s;
}
.btn-add-option:hover { background: var(--primary); color: #fff; }
.btn-add-option:focus-visible { box-shadow: 0 0 0 2px var(--primary); }

/* ---- Option cards ---- */
.opt-c {
    background: #fff;
    border: 1px solid #eef1f5;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: 0.75rem;
    transition: all 0.15s;
}
.opt-c:hover { border-color: #e2e8f0; }
.opt-c:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-ring); }
.opt-c-head {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}
.opt-c-num {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--primary-light);
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.opt-c-del {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: #94a3b8;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.12s;
}
.opt-c-del:hover { color: #ef4444; background: #fef2f2; }
.opt-c-del:focus-visible { box-shadow: 0 0 0 2px var(--primary); }
.opt-c-body {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 0.75rem;
    align-items: end;
}
.opt-c-field { min-width: 0; }
@media (max-width: 640px) {
    .opt-c-body {
        grid-template-columns: 1fr;
    }
}

/* ---- Page header ---- */
.page-header {
    margin-bottom: 1.5rem;
}
.page-header-content {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
}
.page-header-title h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.25;
}
.page-header-title p {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0 0;
    line-height: 1.5;
}
.page-header-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

/* ---- Utilities ---- */
.rq { color: #f87171; }
.max-w-content { max-width: 1200px; }

@media (max-width: 640px) {
    .sc-h { padding: 0.75rem 1rem; }
    .sc-b { padding: 0.85rem 1rem 1rem; }
    .page-header-title h1 { font-size: 1.25rem; }
    .form-actions { padding: 0.85rem 1rem; flex-direction: column; gap: 0.5rem; }
    .form-actions .btn:first-child { margin-right: 0; }
    .form-actions .btn { width: 100%; justify-content: center; }
}
</style>


<div class="max-w-content mx-auto px-4 sm:px-6 pt-6 sm:pt-8 pb-6 sm:pb-8">

    <?php if (isset($errors['general'])): ?>
    <div class="mb-4 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3.5 py-2.5 text-xs font-medium text-red-800">
        <i class="bi bi-x-circle-fill text-red-500 shrink-0"></i>
        <?= htmlspecialchars($errors['general']) ?>
    </div>
    <?php endif; ?>

    <!-- Page Header -->
    <header class="page-header">
        <div class="page-header-content">
            <div class="page-header-title">
                <h1><?= htmlspecialchars($headerTitle) ?></h1>
                <p><?= htmlspecialchars($pageSubtitle) ?></p>
            </div>
        </div>
    </header>

    <form id="questionForm" method="post" action="<?= $formAction ?>" class="space-y-6">

        <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$questionId ?>">
        <?php endif; ?>

        <!-- ===== SECTION 1: BASIC INFORMATION ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-indigo-50">
                    <i class="bi bi-file-text text-indigo-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Basic Information</h2>
                    <p>Enter the question text, select the assessment, and configure the question type.</p>
                </div>
            </div>
            <div class="sc-b space-y-4">
                <div>
                    <label class="f-lbl" for="question_text">Question Text <span class="rq">*</span></label>
                    <textarea class="f-inp <?= isset($errors['question_text']) ? 'err' : '' ?>" id="question_text" name="question_text" rows="3"
                              placeholder="Enter the question text..."><?= htmlspecialchars((string)($old['question_text'] ?? '')) ?></textarea>
                    <?php if (isset($errors['question_text'])): ?>
                        <p class="err-msg"><?= htmlspecialchars($errors['question_text']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="f-lbl" for="assessment_id">Assessment <span class="rq">*</span></label>
                        <select class="f-inp ts-single <?= isset($errors['assessment_id']) ? 'ts-has-error' : '' ?>" id="assessment_id" name="assessment_id" data-placeholder="Select assessment...">
                            <option value=""></option>
                            <?php foreach ($assessments as $a): ?>
                            <option value="<?= (int)($a['assessment_id'] ?? 0) ?>" <?= ((int)($old['assessment_id'] ?? 0) === (int)($a['assessment_id'] ?? 0)) ? 'selected' : '' ?>><?= htmlspecialchars((string)($a['title'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['assessment_id'])): ?>
                            <p class="err-msg"><?= htmlspecialchars($errors['assessment_id']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="f-lbl" for="question_type">Question Type</label>
                        <select class="f-inp ts-single" id="question_type" name="question_type" data-placeholder="Select question type...">
                            <?php foreach ($questionTypes as $key => $label): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= ((string)($old['question_type'] ?? 'single_choice') === $key) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="f-lbl" for="question_order">Order Number <span class="rq">*</span></label>
                        <input type="number" class="f-inp <?= isset($errors['question_order']) ? 'err' : '' ?>" id="question_order" name="question_order" min="1"
                               value="<?= (int)($old['question_order'] ?? 1) ?>">
                        <?php if (isset($errors['question_order'])): ?>
                            <p class="err-msg"><?= htmlspecialchars($errors['question_order']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 2: ANSWER OPTIONS ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-emerald-50">
                    <i class="bi bi-list-ul text-emerald-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Answer Options</h2>
                    <p>Add answer options for this question. Each option needs text, a numeric value, and an order number.</p>
                </div>
            </div>
            <div class="sc-b">
                <div class="flex items-center justify-between mb-4">
                    <button type="button" onclick="addOptionRow()" class="btn-add-option">
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Add Option</span>
                    </button>
                </div>
                <?php if (isset($errors['options'])): ?>
                <div class="mb-4 p-3 rounded-lg border border-red-200 bg-red-50 text-sm text-red-800">
                    <?= htmlspecialchars($errors['options']) ?>
                </div>
                <?php endif; ?>
                <div id="optionsContainer" class="space-y-3">
                    <!-- Option cards will be inserted here by JavaScript -->
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?= BASE_URL ?>/index.php?page=admin-questions" class="btn btn-ghost">
                    <i class="bi bi-arrow-left text-sm"></i>
                    <span>Back to Questions</span>
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=admin-questions" class="btn btn-cancel">
                    <i class="bi bi-x text-sm"></i>
                    <span>Cancel</span>
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="bi <?= $submitIcon ?> text-sm"></i>
                    <span><?= htmlspecialchars($submitLabel) ?></span>
                </button>
            </div>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
(function() {
    'use strict';

    var optionIndex = 0;
    var container = document.getElementById('optionsContainer');

    function createOptionCard(text, value, order) {
        var card = document.createElement('div');
        card.className = 'opt-c';
        card.dataset.index = optionIndex;

        var num = optionIndex + 1;

        card.innerHTML =
            '<div class="opt-c-head">' +
                '<span class="opt-c-num">' + num + '</span>' +
                '<button type="button" class="opt-c-del" onclick="removeOptionRow(this)" aria-label="Delete option">' +
                    '<i class="bi bi-trash text-base"></i>' +
                '</button>' +
            '</div>' +
            '<div class="opt-c-body">' +
                '<div class="opt-c-field">' +
                    '<label class="f-lbl">Option Text <span class="rq">*</span></label>' +
                    '<input type="text" name="option_text[' + optionIndex + ']" value="' + (text || '') + '" class="f-inp" placeholder="Option text" required>' +
                '</div>' +
                '<div class="opt-c-field" style="min-width: 120px;">' +
                    '<label class="f-lbl">Value</label>' +
                    '<input type="number" name="option_value[' + optionIndex + ']" value="' + (value !== undefined ? value : '') + '" step="0.01" min="0" class="f-inp" placeholder="0.00">' +
                '</div>' +
                '<div class="opt-c-field" style="min-width: 80px;">' +
                    '<label class="f-lbl">Order</label>' +
                    '<input type="number" name="option_order[' + optionIndex + ']" value="' + (order || (optionIndex + 1)) + '" min="1" class="f-inp" placeholder="1">' +
                '</div>' +
            '</div>';

        return card;
    }

    window.addOptionRow = function(text, value, order) {
        var card = createOptionCard(text, value, order);
        container.appendChild(card);
        renumberOptions();
        optionIndex++;
    };

    window.removeOptionRow = function(btn) {
        var card = btn.closest('.opt-c');
        card.parentNode.removeChild(card);
        renumberOptions();
    };

    function renumberOptions() {
        var cards = container.querySelectorAll('.opt-c');
        cards.forEach(function(card, i) {
            card.querySelector('.opt-c-num').textContent = i + 1;
            card.dataset.index = i;
            // Update input names to match new index
            var inputs = card.querySelectorAll('input[name]');
            inputs.forEach(function(input) {
                var name = input.getAttribute('name');
                var base = name.replace(/\[\d+\]/, '');
                input.setAttribute('name', base + '[' + i + ']');
            });
        });
        optionIndex = cards.length;
    }

    // Initialize with default options or existing data
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

    // ===== TomSelect single selects =====
    document.querySelectorAll('.ts-single').forEach(function(el) {
        new TomSelect(el, {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            maxOptions: null,
            dropdownParent: 'body'
        });
    });

})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
