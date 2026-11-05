# PowerShell script to replace all pink colors with cream/white palette
# Run this to update all blade files

$files = Get-ChildItem -Path resources/views -Recurse -Include *.blade.php

$colorMap = @{
    '#FFB6D9' = '#DCCBBC'  # Bright pink → Oat (soft cream)
    '#FFC9E0' = '#F5F0E8'  # Light pink → Cream  
    '#D84797' = '#947765'  # Hot pink → Mocha (warm brown)
    '#FFE5EC' = '#F5F0E8'  # Pale pink → Cream
    '#FFF0F5' = '#FFFFFF'  # Lightest pink → White
}

$totalReplacements = 0

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    $originalContent = $content
    
    foreach ($oldColor in $colorMap.Keys) {
        $newColor = $colorMap[$oldColor]
        $count = ([regex]::Matches($content, [regex]::Escape($oldColor))).Count
        if ($count -gt 0) {
            $content = $content -replace [regex]::Escape($oldColor), $newColor
            Write-Host "  $($file.Name): Replaced $count instances of $oldColor → $newColor"
            $totalReplacements += $count
        }
    }
    
    if ($content -ne $originalContent) {
        Set-Content -Path $file.FullName -Value $content -NoNewline
        Write-Host "✓ Updated: $($file.Name)"
    }
}

Write-Host "`nTotal replacements: $totalReplacements" -ForegroundColor Green
Write-Host "All blade files updated successfully!" -ForegroundColor Green
