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

$pageTitle = 'Add Career';
$headerTitle = 'Add Career';
$activeMenu = 'careers';

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

/* ---- Environment chips ---- */
.env-c {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.75rem;
    border-radius: 9999px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    font-size: 0.76rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    transition: all 0.12s;
    user-select: none;
    outline: none;
}
.env-c:hover { border-color: #a5b4fc; background: #f5f3ff; }
.env-c.sel { border-color: var(--primary); background: var(--primary-light); color: var(--primary); font-weight: 600; }
.env-c:focus-visible { box-shadow: 0 0 0 2px var(--primary); }

/* ---- Action bar ---- */
.act-b {
    position: sticky;
    top: 0;
    z-index: 50;
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid #eef1f5;
    margin: -1.5rem -1.5rem 0 -1.5rem;
}
.act-i {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.85rem;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    transition: all 0.12s;
    cursor: pointer;
    border: none;
    text-decoration: none;
    white-space: nowrap;
}
.act-i-pri { background: var(--primary); color: #fff; }
.act-i-pri:hover { background: var(--primary-dark); }
.act-i-out { background: #fff; color: #475569; border: 1px solid #e2e8f0; }
.act-i-out:hover { background: #f8fafc; }
.act-i-gh { background: transparent; color: #64748b; }
.act-i-gh:hover { background: #f1f5f9; color: #334155; }

/* ---- Section cards ---- */
.sc {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eef1f5;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    overflow: visible;
}
.sc-h {
    padding: 0.9rem 1.5rem;
    border-bottom: 1px solid #f1f4f8;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.sc-h h2 { font-size: 0.82rem; font-weight: 700; color: #0f172a; }
.sc-b { padding: 1.25rem 1.5rem 1.5rem; }

/* ---- Utilities ---- */
.rq { color: #f87171; }

@media (max-width: 640px) {
    .sc-h { padding: 0.75rem 1rem; }
    .sc-b { padding: 1rem; }
}
</style>

<!-- ===== STICKY ACTION BAR ===== -->
<div class="act-b anim">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-14">
            <div class="flex items-center gap-3 min-w-0">
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers" class="act-i act-i-gh shrink-0 -ml-1.5">
                    <i class="bi bi-arrow-left text-sm"></i>
                    <span class="hidden sm:inline">Back to Careers</span>
                </a>
                <div class="w-px h-5 bg-slate-200 shrink-0 hidden sm:block"></div>
                <div class="leading-tight min-w-0 hidden sm:block">
                    <div class="text-sm font-bold text-slate-900 truncate">Add Career</div>
                    <div class="text-[11px] text-slate-500 -mt-px truncate">Create a new career recommendation for students.</div>
                </div>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <button type="submit" name="save_draft" value="1" class="act-i act-i-out text-xs sm:text-sm" form="careerForm">
                    <i class="bi bi-save"></i>
                    <span>Draft</span>
                </button>
                <button type="submit" class="act-i act-i-pri text-xs sm:text-sm" form="careerForm">
                    <i class="bi bi-check-lg"></i>
                    <span>Publish</span>
                </button>
                <a href="<?= BASE_URL ?>/index.php?page=admin-careers" class="act-i act-i-gh hidden md:inline-flex">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-[1300px] mx-auto px-4 sm:px-6 pt-14 sm:pt-16 pb-6 sm:pb-8">

    <?php if (isset($errors['general'])): ?>
    <div class="mb-4 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3.5 py-2.5 text-xs font-medium text-red-800">
        <i class="bi bi-x-circle-fill text-red-500 shrink-0"></i>
        <?= htmlspecialchars($errors['general']) ?>
    </div>
    <?php endif; ?>

    <form id="careerForm" method="post" action="<?= BASE_URL ?>/index.php?page=admin-careers-store" onsubmit="syncSalary()">

    <input type="hidden" name="status" id="st_h" value="<?= htmlspecialchars((string)($old['status'] ?? 'active')) ?>">
    <input type="hidden" name="average_salary" id="average_salary" value="<?= htmlspecialchars((string)($old['average_salary'] ?? '')) ?>">
    <input type="hidden" name="growth_rate" id="gr_h" value="<?= htmlspecialchars((string)($old['growth_rate'] ?? '')) ?>">
    <input type="hidden" name="work_environment" id="we_h" value="<?= htmlspecialchars((string)($old['work_environment'] ?? '')) ?>">
    <input type="hidden" name="job_outlook" id="jo_h" value="<?= htmlspecialchars((string)($old['job_outlook'] ?? '')) ?>">

    <div class="space-y-4">

        <!-- ===== SECTION 1: BASIC INFORMATION ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                    <i class="bi bi-info-circle text-indigo-600 text-sm"></i>
                </div>
                <h2>Basic Information</h2>
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
                <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                    <i class="bi bi-currency-dollar text-emerald-600 text-sm"></i>
                </div>
                <h2>Salary &amp; Growth</h2>
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
                <div class="w-7 h-7 rounded-lg bg-sky-50 flex items-center justify-center shrink-0">
                    <i class="bi bi-mortarboard text-sky-600 text-sm"></i>
                </div>
                <h2>Education Requirements</h2>
            </div>
            <div class="sc-b">
                <div>
                    <label class="f-lbl" for="education_required">Education Level <span class="rq">*</span></label>
                    <select class="f-inp ts-single <?= isset($errors['education_required']) ? 'err' : '' ?>" id="education_required" name="education_required" data-placeholder="Select education level...">
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
                <div class="w-7 h-7 rounded-lg bg-violet-50 flex items-center justify-center shrink-0">
                    <i class="bi bi-puzzle text-violet-600 text-sm"></i>
                </div>
                <h2>Assessment Matching</h2>
            </div>
            <div class="sc-b">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="f-lbl">Personality Type</label>
                        <select class="f-inp ts-single" data-placeholder="Select personality type..." id="personality_type" name="personality_type">
                            <option value=""></option>
                            <?php foreach ($personalityTypes as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ((string)($old['personality_type'] ?? '') === $opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="f-lbl">Interest Type</label>
                        <select class="f-inp ts-single" data-placeholder="Select interest type..." id="interest_type" name="interest_type">
                            <option value=""></option>
                            <?php foreach ($interestTypes as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ((string)($old['interest_type'] ?? '') === $opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="f-lbl">Aptitude Type</label>
                        <select class="f-inp ts-single" data-placeholder="Select aptitude type..." id="aptitude_type" name="aptitude_type">
                            <option value=""></option>
                            <?php foreach ($aptitudeTypes as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ((string)($old['aptitude_type'] ?? '') === $opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="f-lbl">Values Type</label>
                        <select class="f-inp ts-single" data-placeholder="Select values type..." id="values_type" name="values_type">
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
                <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                    <i class="bi bi-tools text-amber-600 text-sm"></i>
                </div>
                <h2>Required Skills</h2>
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
                </div>
            </div>
        </div>

        <!-- ===== SECTION 6: WORK ENVIRONMENT ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="w-7 h-7 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                    <i class="bi bi-building text-rose-600 text-sm"></i>
                </div>
                <h2>Work Environment</h2>
            </div>
            <div class="sc-b">
                <div>
                    <label class="f-lbl">Environment Type</label>
                    <div class="flex flex-wrap gap-1.5" id="envC">
                        <?php $envVals = array_map('trim', explode(',', (string)($old['work_environment'] ?? ''))); ?>
                        <?php foreach ($workEnvironments as $env): ?>
                        <button type="button" class="env-c <?= in_array($env, $envVals) ? 'sel' : '' ?>" data-v="<?= htmlspecialchars($env) ?>"><?= htmlspecialchars($env) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 7: JOB OUTLOOK ===== -->
        <div class="sc">
            <div class="sc-h">
                <div class="w-7 h-7 rounded-lg bg-cyan-50 flex items-center justify-center shrink-0">
                    <i class="bi bi-bar-chart-line text-cyan-600 text-sm"></i>
                </div>
                <h2>Job Outlook</h2>
            </div>
            <div class="sc-b">
                <div>
                    <label class="f-lbl">Market Demand</label>
                    <div class="seg-g" role="radiogroup">
                        <?php $joVal = (string)($old['job_outlook'] ?? ''); ?>
                        <?php foreach ($jobOutlooks as $jo): ?>
                        <button type="button" class="seg-b <?= $joVal === $jo ? 'sel' : '' ?>" data-v="<?= htmlspecialchars($jo) ?>" data-g="jo"><?= htmlspecialchars($jo) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

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

    // ===== Environment chips (toggle) =====
    function togEnv(el) {
        el.classList.toggle('sel');
        el.setAttribute('aria-checked', el.classList.contains('sel') ? 'true' : 'false');
        syncEnv();
    }

    document.querySelectorAll('#envC .env-c').forEach(function(el) {
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
        document.querySelectorAll('#envC .env-c.sel').forEach(function(c) {
            vals.push(c.getAttribute('data-v'));
        });
        document.getElementById('we_h').value = vals.join(', ');
    }

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

})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
