<?php
$result = $_SESSION['guest_result'] ?? null;
unset($_SESSION['guest_result']);
?>

<style>
    .gr-wrap { overflow-x: hidden; }

    /* ==== Base (320px+) – Mobile-first ==== */

    .gr-container {
        max-width: 100%;
        margin-left: auto;
        margin-right: auto;
        padding: 1.25rem 0.75rem;
    }

    .gr-card {
        width: 100%;
        padding: 1rem;
    }

    .gr-heading {
        font-size: 1.375rem;
        word-break: break-word;
        margin-top: 0.75rem;
    }

    .gr-message {
        font-size: 0.8125rem;
        margin-top: 0.75rem;
    }

    .gr-score {
        font-size: 1.5rem;
    }

    .gr-svg {
        width: 5rem !important;
        height: 5rem !important;
    }

    .gr-btn-row {
        flex-direction: column !important;
    }

    .gr-btn-row .gr-btn {
        width: 100% !important;
    }

    .gr-btn {
        font-size: 0.8125rem;
        padding: 0.625rem 1rem !important;
    }

    .gr-icon-outer {
        width: 3rem !important;
        height: 3rem !important;
    }

    .gr-icon-outer i {
        font-size: 1rem;
    }

    .gr-section-gap {
        margin-top: 1rem !important;
    }

    /* ==== 375px+ ==== */
    @media (min-width: 375px) {
        .gr-container {
            padding: 1.5rem 1rem;
        }
        .gr-card {
            padding: 1.25rem;
        }
        .gr-heading {
            font-size: 1.625rem;
            margin-top: 1.25rem;
        }
        .gr-message {
            font-size: 0.875rem;
            margin-top: 0.75rem;
        }
        .gr-score {
            font-size: 1.75rem;
        }
        .gr-svg {
            width: 6rem !important;
            height: 6rem !important;
        }
        .gr-btn {
            font-size: 0.875rem;
            padding: 0.75rem 1.25rem !important;
        }
        .gr-icon-outer {
            width: 3.5rem !important;
            height: 3.5rem !important;
        }
        .gr-icon-outer i {
            font-size: 1.25rem;
        }
        .gr-section-gap {
            margin-top: 1.25rem !important;
        }
    }

    /* ==== 576px+ ==== */
    @media (min-width: 576px) {
        .gr-container {
            max-width: 100%;
            padding: 2rem 1.25rem;
        }
        .gr-card {
            width: 100%;
        }
        .gr-heading {
            font-size: 1.875rem;
            margin-top: 1.25rem;
        }
        .gr-message {
            font-size: 0.9375rem;
        }
        .gr-btn-row {
            flex-direction: column;
        }
        .gr-btn-row .gr-btn {
            width: 100%;
        }
        .gr-section-gap {
            margin-top: 1.25rem;
        }
    }

    /* ==== 768px+ ==== */
    @media (min-width: 768px) {
        .gr-container {
            max-width: 100%;
            padding: 2.5rem 1.5rem;
        }
        .gr-card {
            width: 100%;
        }
        .gr-btn-row {
            flex-direction: column;
        }
        .gr-btn-row .gr-btn {
            width: 100%;
        }
        .gr-heading {
            font-size: 2.25rem;
        }
        .gr-message {
            font-size: 1rem;
        }
        .gr-section-gap {
            margin-top: 1.75rem;
        }
    }

    /* ==== 992px+ ==== */
    @media (min-width: 992px) {
        .gr-container {
            max-width: 700px;
            padding: 3rem 1.5rem;
        }
        .gr-card {
            padding: 2rem;
        }
        .gr-heading {
            font-size: 2.5rem;
            margin-top: 1.5rem;
        }
        .gr-message {
            font-size: 1.0625rem;
        }
        .gr-score {
            font-size: 2.5rem;
        }
        .gr-svg {
            width: 9rem !important;
            height: 9rem !important;
        }
        .gr-btn-row {
            flex-direction: row;
        }
        .gr-btn-row .gr-btn {
            width: auto;
        }
        .gr-btn {
            font-size: 1rem;
            padding: 0.875rem 2rem !important;
        }
        .gr-section-gap {
            margin-top: 2rem;
        }
        .gr-icon-outer {
            width: 4rem !important;
            height: 4rem !important;
        }
        .gr-icon-outer i {
            font-size: 1.5rem;
        }
    }

    /* ==== 1200px+ ==== */
    @media (min-width: 1200px) {
        .gr-container {
            max-width: 760px;
            padding: 4rem 2rem;
        }
        .gr-card {
            padding: 2.5rem;
        }
        .gr-heading {
            font-size: 2.75rem;
        }
        .gr-score {
            font-size: 2.75rem;
        }
        .gr-svg {
            width: 10rem !important;
            height: 10rem !important;
        }
        .gr-section-gap {
            margin-top: 2.5rem;
        }
        .gr-btn {
            font-size: 1.125rem;
        }
    }

    /* ==== 1440px+ ==== */
    @media (min-width: 1440px) {
        .gr-container {
            max-width: 800px;
            padding: 5rem 2rem;
        }
        .gr-card {
            padding: 3rem;
        }
        .gr-heading {
            font-size: 3rem;
            margin-top: 1.5rem;
        }
        .gr-message {
            font-size: 1.125rem;
            margin-top: 0.75rem;
        }
        .gr-score {
            font-size: 3.75rem;
        }
        .gr-svg {
            width: 11rem !important;
            height: 11rem !important;
        }
        .gr-section-gap {
            margin-top: 2.5rem;
        }
        .gr-icon-outer {
            width: 5rem !important;
            height: 5rem !important;
        }
        .gr-icon-outer i {
            font-size: 2.25rem;
        }
    }
</style>

<main class="flex-1 min-h-screen gr-wrap bg-gradient-to-b from-slate-50 to-white">
    <?php if ($result): ?>
    <div class="gr-container">

        <div class="flex justify-center">
            <div class="gr-icon-outer rounded-full bg-emerald-100 flex items-center justify-center shadow-sm">
                <i class="fas fa-check-circle text-emerald-600"></i>
            </div>
        </div>

        <h1 class="gr-heading text-center font-extrabold tracking-tight text-slate-900">
            Assessment Complete
        </h1>

        <p class="gr-message text-center text-slate-500">
            You completed the <span class="font-semibold text-slate-700"><?= htmlspecialchars($result['title']) ?></span> Preview.
        </p>

        <div class="gr-card gr-section-gap bg-white rounded-[20px] border border-slate-200 shadow-sm text-center">
            <div class="relative inline-flex items-center justify-center">
                <svg class="gr-svg -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="54" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="60" cy="60" r="54" fill="none" stroke="url(#scoreGrad)" stroke-width="8"
                        stroke-dasharray="<?= 2 * pi() * 54 ?>"
                        stroke-dashoffset="<?= 2 * pi() * 54 * (1 - (int)$result['score'] / 100) ?>"
                        stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="scoreGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#6366f1"/>
                            <stop offset="100%" stop-color="#8b5cf6"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="gr-score font-extrabold text-indigo-600"><?= (int)$result['score'] ?>%</span>
                    <span class="text-[10px] sm:text-xs font-medium text-slate-400 mt-0.5">Preview Score</span>
                </div>
            </div>
            <p class="mt-3 text-xs sm:text-sm text-slate-400">This score is based on the preview assessment.</p>
        </div>

        <div class="gr-card gr-section-gap bg-white rounded-[20px] border border-slate-200 shadow-sm text-center">
            <h2 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900">Create a free account to unlock</h2>
            <ul class="mt-3 sm:mt-4 space-y-2 max-w-xs mx-auto text-left">
                <li class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-600">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 flex-shrink-0">
                        <i class="fas fa-star text-indigo-600 text-[8px] sm:text-[10px]"></i>
                    </span>
                    Unlock the full assessment
                </li>
                <li class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-600">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 flex-shrink-0">
                        <i class="fas fa-star text-indigo-600 text-[8px] sm:text-[10px]"></i>
                    </span>
                    Personalized career recommendations
                </li>
                <li class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-600">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 flex-shrink-0">
                        <i class="fas fa-star text-indigo-600 text-[8px] sm:text-[10px]"></i>
                    </span>
                    Save your progress
                </li>
            </ul>

            <div class="gr-btn-row gr-section-gap flex items-center justify-center gap-2 sm:gap-3">
                <a href="<?= BASE_URL ?>/index.php?page=register"
                   class="gr-btn inline-flex items-center justify-center gap-2 bg-gradient-to-r from-brand-start via-brand-mid to-brand-end text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/25 hover:shadow-2xl hover:shadow-indigo-500/35 hover:-translate-y-0.5 active:scale-[0.97] transition-all duration-300">
                    <i class="fas fa-user-plus text-xs sm:text-sm"></i>
                    Create Free Account
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=login"
                   class="gr-btn inline-flex items-center justify-center gap-2 bg-white text-slate-700 font-semibold rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-indigo-200 hover:text-indigo-600 hover:-translate-y-0.5 active:scale-[0.97] transition-all duration-300">
                    <i class="fas fa-sign-in-alt text-xs sm:text-sm"></i>
                    Log In
                </a>
            </div>
        </div>

        <div class="gr-section-gap text-center">
            <a href="<?= BASE_URL ?>/index.php?page=assessments"
               class="inline-flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                <i class="fas fa-arrow-left text-[10px] sm:text-xs"></i>
                Back to Assessments
            </a>
        </div>

    </div>

    <?php else: ?>
    <div class="flex items-center justify-center min-h-[70vh] px-3 sm:px-4">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-slate-100 border border-slate-200 mb-4 sm:mb-5">
                <i class="fas fa-file-alt text-xl sm:text-2xl text-slate-400"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">No Result Found</h1>
            <p class="mt-2 sm:mt-3 text-sm sm:text-base text-slate-500">You haven't completed any assessment yet.</p>
            <a href="<?= BASE_URL ?>/index.php?page=assessments"
               class="mt-6 sm:mt-8 inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-start via-brand-mid to-brand-end text-white font-bold px-6 sm:px-8 py-3 sm:py-3.5 shadow-xl shadow-indigo-500/25 hover:-translate-y-0.5 active:scale-[0.97] transition-all duration-300 text-sm sm:text-base">
                <i class="fas fa-arrow-left text-xs sm:text-sm"></i>
                Browse Assessments
            </a>
        </div>
    </div>
    <?php endif; ?>
</main>
