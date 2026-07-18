<?php
$errors = $errors ?? [];
$old = $old ?? [];
$educationLevels = $educationLevels ?? [];
$personalityTypes = $personalityTypes ?? [];
$interestTypes = $interestTypes ?? [];
$aptitudeTypes = $aptitudeTypes ?? [];
$valuesTypes = $valuesTypes ?? [];
$growthRates = $growthRates ?? [];
$allSkills = $allSkills ?? [];
$workEnvironments = $workEnvironments ?? [];
$jobOutlooks = $jobOutlooks ?? [];
$currencies = $currencies ?? [];

$pageTitle = 'Edit Career';
$headerTitle = 'Edit Career';
$activeMenu = 'careers';
$careerId = (int)($old['career_id'] ?? 0);

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

/* ---- TomSelect overrides ---- */
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

/* ---- Segmented button groups ---- */
.seg-g {
    display: inline-flex;
    background: #f1f5f9;
    border-radius: 10px;
    padding: 3px;
    gap: 2px;
    flex-wrap: wrap;
}
.seg-b {
    padding: 0.4rem 1rem;
    border-radius: 8px;
    border: none;
    font-size: 0.78rem;
    font-weight: 500;
    color: #64748b;
    background: transparent;
    cursor: pointer;
    transition: all 0.12s;
    white-space: nowrap;
    outline: none;
}
.seg-b:hover { color: #334155; }
.seg-b.sel {
    background: #fff;
    color: #0f172a;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07);
}
.seg-b:focus-visible { box-shadow: 0 0 0 2px var(--primary); }

/* ---- Environment chips (redesigned) ---- */
.env-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    font-size: 0.82rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s;
    user-select: none;
    outline: none;
}
.env-chip:hover { border-color: #a5b4fc; background: #f5f3ff; }
.env-chip.sel { border-color: var(--primary); background: var(--primary-light); color: var(--primary); font-weight: 600; }
.env-chip.sel .check-icon { display: inline-flex; }
.env-chip .check-icon { display: none; }
.env-chip:focus-visible { box-shadow: 0 0 0 2px var(--primary); }

/* ---- Job Outlook option cards ---- */
.jo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 0.75rem;
}
.jo-card {
    position: relative;
    padding: 1rem 1.25rem;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    font-size: 0.82rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s;
    user-select: none;
    outline: none;
}
.jo-card:hover { border-color: #a5b4fc; background: #f5f3ff; }
.jo-card.sel { border-color: var(--primary); background: var(--primary-light); color: var(--primary); font-weight: 600; }
.jo-card.sel .check-icon { display: inline-flex; }
.jo-card .check-icon { display: none; position: absolute; top: 0.5rem; right: 0.5rem; width: 18px; height: 18px; border-radius: 9999px; background: var(--primary); color: #fff; align-items: center; justify-content: center; font-size: 0.65rem; }
.jo-card:focus-visible { box-shadow: 0 0 0 2px var(--primary); }

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
    justify-content: space-between;
    gap: 0.75rem;
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #eef1f5;
    background: #fafbfc;
    border-radius: 0 0 12px 12px;
    margin-top: 1.5rem;
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

/* ---- Skills helper ---- */
.skills-helper {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.35rem;
}
.skills-helper i { font-size: 0.7rem; }

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
    .form-actions { padding: 1rem; flex-direction: column-reverse; align-items: stretch; }
    .form-actions .btn { width: 100%; justify-content: center; }
    .jo-grid { grid-template-columns: 1fr; }
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
                <h1>Edit Career</h1>
                <p>Update the career information and save your changes.</p>
            </div>
      
        </div>
    </header>

    <form id="careerForm" method="post" action="<?= BASE_URL ?>/index.php?page=admin-careers-update" onsubmit="syncSalary()">

    <input type="hidden" name="id" value="<?= $careerId ?>">
    <input type="hidden" name="status" id="st_h" value="<?= htmlspecialchars((string)($old['status'] ?? 'active')) ?>">
    <input type="hidden" name="average_salary" id="average_salary" value="<?= htmlspecialchars((string)($old['average_salary'] ?? '')) ?>">
    <input type="hidden" name="growth_rate" id="gr_h" value="<?= htmlspecialchars((string)($old['growth_rate'] ?? '')) ?>">
    <input type="hidden" name="work_environment" id="we_h" value="<?= htmlspecialchars((string)($old['work_environment'] ?? '')) ?>">
    <input type="hidden" name="job_outlook" id="jo_h" value="<?= htmlspecialchars((string)($old['job_outlook'] ?? '')) ?>">

    <div class="space-y-6">

        <!-- ===== SECTION 1: BASIC INFORMATION ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-indigo-50">
                    <i class="bi bi-info-circle text-indigo-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Basic Information</h2>
                    <p>Enter the career name, category, and a brief description.</p>
                </div>
            </div>
            <div class="sc-b space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="f-lbl" for="career_name">Career Name <span class="rq">*</span></label>
                        <input type="text" class="f-inp <?= isset($errors['career_name']) ? 'err' : '' ?>" id="career_name" name="career_name"
                               value="<?= htmlspecialchars((string)($old['career_name'] ?? '')) ?>"
                               placeholder="e.g. Software Engineer">
                        <?php if (isset($errors['career_name'])): ?>
                            <p class="err-msg"><?= htmlspecialchars($errors['career_name']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="f-lbl" for="career_icon">Category</label>
                        <select class="f-inp" id="career_icon" name="career_icon">
                            <option value="">Select category</option>
                            <option value="fa-code" <?= ((string)($old['career_icon'] ?? '') === 'fa-code') ? 'selected' : '' ?>>💻 Technology</option>
                            <option value="fa-stethoscope" <?= ((string)($old['career_icon'] ?? '') === 'fa-stethoscope') ? 'selected' : '' ?>>🏥 Healthcare</option>
                            <option value="fa-chart-bar" <?= ((string)($old['career_icon'] ?? '') === 'fa-chart-bar') ? 'selected' : '' ?>>📊 Business</option>
                            <option value="fa-paint-brush" <?= ((string)($old['career_icon'] ?? '') === 'fa-paint-brush') ? 'selected' : '' ?>>🎨 Creative</option>
                            <option value="fa-calculator" <?= ((string)($old['career_icon'] ?? '') === 'fa-calculator') ? 'selected' : '' ?>>📐 Engineering</option>
                            <option value="fa-gavel" <?= ((string)($old['career_icon'] ?? '') === 'fa-gavel') ? 'selected' : '' ?>>⚖️ Legal</option>
                            <option value="fa-chalkboard-teacher" <?= ((string)($old['career_icon'] ?? '') === 'fa-chalkboard-teacher') ? 'selected' : '' ?>>📚 Education</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="f-lbl" for="description">Description</label>
                    <textarea class="f-inp" id="description" name="description" rows="3"
                              placeholder="Describe the career path, what professionals do, and why it matters..."><?= htmlspecialchars((string)($old['description'] ?? '')) ?></textarea>
                </div>
                <div>
                    <label class="f-lbl">Status</label>
                    <div class="seg-g" role="radiogroup">
                        <?php $stVal = (string)($old['status'] ?? 'active'); ?>
                        <button type="button" class="seg-b <?= $stVal === 'active' ? 'sel' : '' ?>" data-v="active" data-g="st">Active</button>
                        <button type="button" class="seg-b <?= $stVal === 'inactive' ? 'sel' : '' ?>" data-v="inactive" data-g="st">Inactive</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 2: SALARY & GROWTH ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-emerald-50">
                    <i class="bi bi-currency-dollar text-emerald-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Salary & Growth</h2>
                    <p>Set the expected salary range and career growth rate.</p>
                </div>
            </div>
            <div class="sc-b space-y-4">
                <div>
                    <label class="f-lbl">Salary Range</label>
                    <div class="grid grid-cols-[1fr_1fr_100px] gap-3">
                        <input type="number" class="f-inp" id="sal_min" placeholder="Minimum" value="">
                        <input type="number" class="f-inp" id="sal_max" placeholder="Maximum" value="">
                        <select class="f-inp" id="sal_cur">
                            <?php foreach ($currencies as $c): ?>
                            <option value="<?= htmlspecialchars($c) ?>" <?= ($c === 'USD') ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="f-lbl">Growth Rate</label>
                    <div class="seg-g" role="radiogroup">
                        <?php $grVal = (string)($old['growth_rate'] ?? ''); ?>
                        <?php foreach (['Low', 'Medium', 'High', 'Very High'] as $g): ?>
                        <button type="button" class="seg-b <?= $grVal === $g ? 'sel' : '' ?>" data-v="<?= htmlspecialchars($g) ?>" data-g="gr"><?= htmlspecialchars($g) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 3: EDUCATION REQUIREMENTS ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-sky-50">
                    <i class="bi bi-mortarboard text-sky-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Education Requirements</h2>
                    <p>Select the minimum education level required for this career.</p>
                </div>
            </div>
            <div class="sc-b">
                <div>
                    <label class="f-lbl" for="education_required">Education Level <span class="rq">*</span></label>
                    <select class="ts-single <?= isset($errors['education_required']) ? 'ts-has-error' : '' ?>" id="education_required" name="education_required" data-placeholder="Select education level...">
                        <option value=""></option>
                        <?php foreach ($educationLevels as $level): ?>
                        <option value="<?= htmlspecialchars($level) ?>" <?= ((string)($old['education_required'] ?? '') === $level) ? 'selected' : '' ?>><?= htmlspecialchars($level) ?></option>
                        <?php endforeach; ?>
                        <option value="High School" <?= ((string)($old['education_required'] ?? '') === 'High School') ? 'selected' : '' ?>>High School</option>
                        <option value="Undergraduate" <?= ((string)($old['education_required'] ?? '') === 'Undergraduate') ? 'selected' : '' ?>>Undergraduate</option>
                        <option value="Graduate" <?= ((string)($old['education_required'] ?? '') === 'Graduate') ? 'selected' : '' ?>>Graduate</option>
                        <option value="Post Graduate" <?= ((string)($old['education_required'] ?? '') === 'Post Graduate') ? 'selected' : '' ?>>Post Graduate</option>
                        <option value="Doctorate" <?= ((string)($old['education_required'] ?? '') === 'Doctorate') ? 'selected' : '' ?>>Doctorate</option>
                    </select>
                    <?php if (isset($errors['education_required'])): ?>
                        <p class="err-msg"><?= htmlspecialchars($errors['education_required']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 4: ASSESSMENT MATCHING ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-violet-50">
                    <i class="bi bi-puzzle text-violet-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Assessment Matching</h2>
                    <p>Map this career to personality, interest, aptitude, and values types for student matching.</p>
                </div>
            </div>
            <div class="sc-b">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="f-lbl">Personality Type</label>
                        <select class="ts-single" data-placeholder="Select personality type..." id="personality_type" name="personality_type">
                            <option value=""></option>
                            <?php foreach ($personalityTypes as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ((string)($old['personality_type'] ?? '') === $opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="f-lbl">Interest Type</label>
                        <select class="ts-single" data-placeholder="Select interest type..." id="interest_type" name="interest_type">
                            <option value=""></option>
                            <?php foreach ($interestTypes as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ((string)($old['interest_type'] ?? '') === $opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="f-lbl">Aptitude Type</label>
                        <select class="ts-single" data-placeholder="Select aptitude type..." id="aptitude_type" name="aptitude_type">
                            <option value=""></option>
                            <?php foreach ($aptitudeTypes as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ((string)($old['aptitude_type'] ?? '') === $opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="f-lbl">Values Type</label>
                        <select class="ts-single" data-placeholder="Select values type..." id="values_type" name="values_type">
                            <option value=""></option>
                            <?php foreach ($valuesTypes as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ((string)($old['values_type'] ?? '') === $opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 5: REQUIRED SKILLS ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-amber-50">
                    <i class="bi bi-tools text-amber-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Required Skills</h2>
                    <p>Add the key skills needed for this career. Type to search or create new skills.</p>
                </div>
            </div>
            <div class="sc-b">
                <div>
                    <label class="f-lbl">Skills</label>
                    <select class="ts-multi" id="required_skills" multiple data-placeholder="Search or type to add skills..." style="display:none">
                        <?php foreach ($allSkills as $opt): ?>
                        <option value="<?= htmlspecialchars($opt) ?>" <?= (in_array($opt, array_map('trim', explode(',', (string)($old['required_skills'] ?? '')))) ? 'selected' : '') ?>><?= htmlspecialchars($opt) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="required_skills" id="rs_h" value="<?= htmlspecialchars((string)($old['required_skills'] ?? '')) ?>">
                    <div class="skills-helper">
                        <i class="bi bi-info-circle"></i>
                        <span>Press Enter or comma to add custom skills not in the list.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 6: WORK ENVIRONMENT ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-rose-50">
                    <i class="bi bi-building text-rose-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Work Environment</h2>
                    <p>Select all applicable work environment types for this career.</p>
                </div>
            </div>
            <div class="sc-b">
                <div>
                    <label class="f-lbl">Environment Type</label>
                    <div class="flex flex-wrap gap-2" id="envC">
                        <?php $envVals = array_map('trim', explode(',', (string)($old['work_environment'] ?? ''))); ?>
                        <?php foreach ($workEnvironments as $env): ?>
                        <button type="button" class="env-chip <?= in_array($env, $envVals) ? 'sel' : '' ?>" data-v="<?= htmlspecialchars($env) ?>">
                            <?= htmlspecialchars($env) ?>
                            <span class="check-icon"><i class="bi bi-check"></i></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 7: JOB OUTLOOK ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="icon-wrap bg-cyan-50">
                    <i class="bi bi-bar-chart-line text-cyan-600"></i>
                </div>
                <div class="sc-h-content">
                    <h2>Job Outlook</h2>
                    <p>Select the market demand level that best represents this career's future prospects.</p>
                </div>
            </div>
            <div class="sc-b">
                <div>
                    <label class="f-lbl">Market Demand</label>
                    <div class="jo-grid" id="joGrid" role="radiogroup">
                        <?php $joVal = (string)($old['job_outlook'] ?? ''); ?>
                        <?php foreach ($jobOutlooks as $jo): ?>
                        <button type="button" class="jo-card <?= $joVal === $jo ? 'sel' : '' ?>" data-v="<?= htmlspecialchars($jo) ?>" role="radio" aria-checked="<?= $joVal === $jo ? 'true' : 'false' ?>">
                            <?= htmlspecialchars($jo) ?>
                            <span class="check-icon"><i class="bi bi-check"></i></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Form Actions -->
    <div class="form-actions">
        <a href="<?= BASE_URL ?>/index.php?page=admin-careers" class="btn btn-ghost">
            <i class="bi bi-arrow-left text-base"></i>
            <span>Back to Careers</span>
        </a>
        <button type="submit" name="save_draft" value="1" class="btn btn-secondary">
            <i class="bi bi-file-text text-base"></i>
            <span>Save Draft</span>
        </button>
        <button type="submit" name="save_publish" value="1" class="btn btn-primary">
            <i class="bi bi-send text-base"></i>
            <span>Update Career</span>
        </button>
    </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
(function() {
    'use strict';

    // ===== Segmented buttons =====
    function selSeg(btn) {
        var g = btn.getAttribute('data-g');
        var v = btn.getAttribute('data-v');
        if (g) {
            document.querySelectorAll('.seg-b[data-g="' + g + '"]').forEach(function(b) {
                b.classList.remove('sel');
                b.setAttribute('aria-checked', 'false');
            });
            btn.classList.add('sel');
            btn.setAttribute('aria-checked', 'true');
            var h = document.getElementById(g + '_h');
            if (h) h.value = v;
        }
    }

    document.querySelectorAll('.seg-b').forEach(function(btn) {
        btn.addEventListener('click', function() { selSeg(this); });
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selSeg(this); }
        });
        btn.setAttribute('tabindex', '0');
        btn.setAttribute('role', 'radio');
        btn.setAttribute('aria-checked', btn.classList.contains('sel') ? 'true' : 'false');
    });

    // ===== Environment chips (toggle with checkmark) =====
    function togEnv(el) {
        el.classList.toggle('sel');
        el.setAttribute('aria-checked', el.classList.contains('sel') ? 'true' : 'false');
        syncEnv();
    }

    document.querySelectorAll('#envC .env-chip').forEach(function(el) {
        el.addEventListener('click', function() { togEnv(this); });
        el.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); togEnv(this); }
        });
        el.setAttribute('tabindex', '0');
        el.setAttribute('role', 'checkbox');
        el.setAttribute('aria-checked', el.classList.contains('sel') ? 'true' : 'false');
    });

    function syncEnv() {
        var vals = [];
        document.querySelectorAll('#envC .env-chip.sel').forEach(function(c) {
            vals.push(c.getAttribute('data-v'));
        });
        document.getElementById('we_h').value = vals.join(', ');
    }

    // ===== Job Outlook option cards =====
    function selJo(card) {
        var v = card.getAttribute('data-v');
        document.querySelectorAll('#joGrid .jo-card').forEach(function(c) {
            c.classList.remove('sel');
            c.setAttribute('aria-checked', 'false');
        });
        card.classList.add('sel');
        card.setAttribute('aria-checked', 'true');
        document.getElementById('jo_h').value = v;
    }

    document.querySelectorAll('#joGrid .jo-card').forEach(function(card) {
        card.addEventListener('click', function() { selJo(this); });
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selJo(this); }
        });
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'radio');
        card.setAttribute('aria-checked', card.classList.contains('sel') ? 'true' : 'false');
    });

    // ===== TomSelect single selects =====
    document.querySelectorAll('.ts-single').forEach(function(el) {
        new TomSelect(el, {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            maxOptions: null,
            dropdownParent: 'body'
        });
    });

    // ===== TomSelect multi for skills =====
    var skillSelect = new TomSelect('#required_skills', {
        plugins: ['remove_button'],
        create: true,
        createOnBlur: true,
        sortField: { field: 'text', direction: 'asc' },
        persist: false,
        maxOptions: null,
        dropdownParent: 'body',
        render: {
            option_create: function(data, escape) {
                return '<div class="create">Add <strong>' + escape(data.input) + '</strong>&hellip;</div>';
            }
        },
        onChange: function(value) {
            document.getElementById('rs_h').value = value.join(', ');
        }
    });

    // ===== Salary sync on submit =====
    window.syncSalary = function() {
        var min = document.getElementById('sal_min').value.trim();
        var max = document.getElementById('sal_max').value.trim();
        var cur = document.getElementById('sal_cur').value;
        var sal = '';
        if (min || max) {
            var fm = min, fx = max;
            if (cur === 'USD') {
                if (fm) fm = Number(fm).toLocaleString();
                if (fx) fx = Number(fx).toLocaleString();
            }
            if (fm && fx) sal = cur + ' ' + fm + ' - ' + fx;
            else if (fm) sal = cur + ' ' + fm;
            else if (fx) sal = cur + ' ' + fx;
        }
        document.getElementById('average_salary').value = sal;
    };

    // ===== Save Draft =====
    document.querySelectorAll('button[form="careerForm"][name="save_draft"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('st_h').value = 'draft';
        });
    });

    // ===== Parse existing salary on load =====
    (function initSalary() {
        var sal = document.getElementById('average_salary').value.trim();
        if (!sal) return;
        var cur = 'USD';
        var parts = sal.split(/[\s\-–]+/).filter(Boolean);
        var nums = [];
        for (var i = 0; i < parts.length; i++) {
            var n = parts[i].replace(/[^0-9.]/g, '');
            if (n) nums.push(n);
        }
        if (nums.length >= 1) document.getElementById('sal_min').value = nums[0];
        if (nums.length >= 2) document.getElementById('sal_max').value = nums[1];
        var m = sal.match(/[A-Z]{3}/);
        if (m) document.getElementById('sal_cur').value = m[0];
    })();

})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';