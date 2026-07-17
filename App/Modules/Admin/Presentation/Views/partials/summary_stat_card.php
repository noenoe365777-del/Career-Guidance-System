<?php
if (!function_exists('renderAdminSummaryCard')) {
    function renderAdminSummaryCard(array $config): void
    {
        $title = (string)($config['title'] ?? '');
        $value = (string)($config['value'] ?? '');
        $valueNumber = (int)($config['valueNumber'] ?? 0);
        $suffix = (string)($config['suffix'] ?? '');
        $icon = (string)($config['icon'] ?? 'bi-collection');
        $iconBg = (string)($config['iconBg'] ?? '#eef2ff');
        $iconColor = (string)($config['iconColor'] ?? '#5B5FEF');
        $hint = (string)($config['hint'] ?? '');
        $active = (bool)($config['active'] ?? false);
        $delayClass = (string)($config['delayClass'] ?? 'd1');
        $counterId = (string)($config['counterId'] ?? '');
        $extraClass = (string)($config['extraClass'] ?? '');

        $classes = ['stat-card', 'card-in', $delayClass, $extraClass];
        $classAttr = implode(' ', array_filter($classes));
        $valueText = $value === '' ? '0' : $value;
        $dataValue = $valueNumber > 0 ? $valueNumber : 0;
        $cardStyle = 'background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:24px;box-shadow:0 6px 18px rgba(15,23,42,0.04);text-align:left;transition:transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out, background-color 0.3s ease-out;';
        $iconStyle = 'width:52px;height:52px;display:flex;align-items:center;justify-content:center;border-radius:14px;background:' . $iconBg . ';color:' . $iconColor . ';flex-shrink:0;transition:transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out;';
        $valueStyle = 'font-size:34px;font-weight:700;color:#0f172a;margin:8px 0 0 0;transition:transform 0.3s ease-out;';
        $titleStyle = 'font-size:16px;font-weight:600;color:#64748b;margin:0;';
        $hintStyle = 'font-size:13px;color:#94a3b8;margin:6px 0 0 0;';

        echo '<div class="' . htmlspecialchars($classAttr, ENT_QUOTES, 'UTF-8') . '" data-value="' . (int)$dataValue . '" data-suffix="' . htmlspecialchars($dataSuffix ?? '', ENT_QUOTES, 'UTF-8') . '" data-counter-id="' . htmlspecialchars($counterId, ENT_QUOTES, 'UTF-8') . '" style="' . htmlspecialchars($cardStyle, ENT_QUOTES, 'UTF-8') . '">';
        echo '<div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">';
        echo '<div style="flex:1;min-width:0;">';
        echo '<p style="' . htmlspecialchars($titleStyle, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p class="card-number" id="' . htmlspecialchars($counterId, ENT_QUOTES, 'UTF-8') . '" style="' . htmlspecialchars($valueStyle, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($valueText, ENT_QUOTES, 'UTF-8') . '</p>';
        if ($hint !== '') {
            echo '<p style="' . htmlspecialchars($hintStyle, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($hint, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        echo '</div>';
        echo '<div class="card-icon-bg" style="' . htmlspecialchars($iconStyle, ENT_QUOTES, 'UTF-8') . '">';
        echo '<i class="bi ' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" style="font-size:24px;"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
