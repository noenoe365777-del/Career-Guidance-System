$content = Get-Content 'C:\xampp\htdocs\career-guidance-system\App\Modules\Assessment\Presentation\Views\student\assessment_v2_dashboard.php' -Raw

$old = "</div>`n    </div>`n`n     `n          `n`n            <!-- Statistics Cards -->`n            <div class=`"stats-grid mb-10`" role=`"list`" aria-label=`"Assessment Statistics`">"

$new = "</div>`n    </div>`n`n    <!-- ========== COMPLETED VIEW ========== -->`n    <div x-show=`"view === 'completed'`" x-cloak class=`"mx-auto w-full max-w-3xl px-4 py-6 sm:px-6 lg:px-8`">`n            <!-- Statistics Cards -->`n            <div class=`"stats-grid mb-10`" role=`"list`" aria-label=`"Assessment Statistics`">"

if ($content.Contains($old)) {
    $content = $content.Replace($old, $new)
    Set-Content 'C:\xampp\htdocs\career-guidance-system\App\Modules\Assessment\Presentation\Views\student\assessment_v2_dashboard.php' -Value $content -NoNewline
    Write-Host 'Replacement successful'
} else {
    Write-Host 'OLD STRING NOT FOUND'
    $idx = $content.IndexOf("</div>`n    </div>")
    if ($idx -ge 0) {
        Write-Host $content.Substring($idx, 200)
    }
}