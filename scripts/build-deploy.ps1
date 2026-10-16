<#
.SYNOPSIS
  Monta a pasta DEPLOY só com o que o servidor precisa.

.EXAMPLE
  powershell -ExecutionPolicy Bypass -File .\scripts\build-deploy.ps1

.EXAMPLE
  # Só materiais do curso PHP (sem vídeo/xlsx de teste ~20 MB)
  powershell -ExecutionPolicy Bypass -File .\scripts\build-deploy.ps1 -SlimCursoPhp

.EXAMPLE
  # Se o servidor NÃO tem Composer (ocupa ~180 MB a mais)
  powershell -ExecutionPolicy Bypass -File .\scripts\build-deploy.ps1 -IncludeVendor
#>
[CmdletBinding()]
param(
    [switch]$SlimCursoPhp,
    [switch]$IncludeVendor,
    [switch]$IncludeNodeModules
)

$ErrorActionPreference = 'Stop'
$Root = Split-Path -Parent $PSScriptRoot
if (-not (Test-Path (Join-Path $Root 'artisan'))) {
    $Root = (Get-Location).Path
}
$Dest = Join-Path $Root 'DEPLOY'

function Copy-Tree {
    param([string]$From, [string]$To, [string[]]$ExcludeDirs = @())
    if (-not (Test-Path $From)) { return }
    New-Item -ItemType Directory -Force -Path $To | Out-Null
    $xd = @()
    foreach ($d in $ExcludeDirs) { $xd += @('/XD', $d) }
    & robocopy $From $To /E /NFL /NDL /NJH /NJS /NC /NS /NP @xd | Out-Null
    $code = $LASTEXITCODE
    if ($code -ge 8) { throw "robocopy falhou ($code): $From -> $To" }
}

function Ensure-EmptyDir {
    param([string]$Path)
    New-Item -ItemType Directory -Force -Path $Path | Out-Null
    $keep = Join-Path $Path '.gitignore'
    if (-not (Test-Path $keep)) {
        Set-Content -Path $keep -Value "*`n!.gitignore`n" -Encoding utf8
    }
}

Write-Host "Origem: $Root"
Write-Host "Destino: $Dest"

if (Test-Path $Dest) {
    Remove-Item -LiteralPath $Dest -Recurse -Force
}
New-Item -ItemType Directory -Force -Path $Dest | Out-Null

# Código e assets
$dirs = @('app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'public')
foreach ($d in $dirs) {
    Copy-Tree (Join-Path $Root $d) (Join-Path $Dest $d) -ExcludeDirs @('node_modules', '.git')
}

# Arquivos raiz obrigatórios
$files = @(
    'artisan',
    'composer.json',
    'composer.lock',
    'package.json',
    'package-lock.json',
    'vite.config.js',
    'tailwind.config.js',
    'postcss.config.js',
    'phpunit.xml'
)
foreach ($f in $files) {
    $src = Join-Path $Root $f
    if (Test-Path $src) {
        Copy-Item $src (Join-Path $Dest $f) -Force
    }
}

Copy-Item (Join-Path $Root '.env.production.example') (Join-Path $Dest '.env.example') -Force
Copy-Item (Join-Path $Root '.env.production.example') (Join-Path $Dest '.env.production.example') -Force

# Ponte Hostinger: public_html precisa de index na raiz
$bridge = Join-Path $Root 'hosting\public_html_bridge'
if (Test-Path $bridge) {
    Copy-Item (Join-Path $bridge 'index.php') (Join-Path $Dest 'index.php') -Force
    Copy-Item (Join-Path $bridge '.htaccess') (Join-Path $Dest '.htaccess') -Force
}

# Storage limpo (sem logs gigantes)
Ensure-EmptyDir (Join-Path $Dest 'storage\logs')
Ensure-EmptyDir (Join-Path $Dest 'storage\framework\cache\data')
Ensure-EmptyDir (Join-Path $Dest 'storage\framework\sessions')
Ensure-EmptyDir (Join-Path $Dest 'storage\framework\views')
Ensure-EmptyDir (Join-Path $Dest 'storage\app\private')
New-Item -ItemType Directory -Force -Path (Join-Path $Dest 'storage\app\public') | Out-Null

$publicStorage = Join-Path $Root 'storage\app\public'
$destPublicStorage = Join-Path $Dest 'storage\app\public'
if (Test-Path $publicStorage) {
    if ($SlimCursoPhp) {
        $curso3 = Join-Path $publicStorage 'conteudos\curso-3'
        if (Test-Path $curso3) {
            Copy-Tree $curso3 (Join-Path $destPublicStorage 'conteudos\curso-3')
        }
        # capas / matriz se existirem na raiz de public storage
        Get-ChildItem $publicStorage -File -ErrorAction SilentlyContinue | ForEach-Object {
            Copy-Item $_.FullName (Join-Path $destPublicStorage $_.Name) -Force
        }
        Write-Host "Modo SlimCursoPhp: só materiais de conteudos/curso-3 (+ arquivos soltos)."
    } else {
        Copy-Tree $publicStorage $destPublicStorage
    }
}

# Bootstrap cache dir
Ensure-EmptyDir (Join-Path $Dest 'bootstrap\cache')

# Remover lixo dentro de public
$junkPublic = @(
    (Join-Path $Dest 'public\hot'),
    (Join-Path $Dest 'public\storage')
)
foreach ($j in $junkPublic) {
    if (Test-Path $j) { Remove-Item $j -Recurse -Force -ErrorAction SilentlyContinue }
}

# Opcional: vendor / node_modules
if ($IncludeVendor -and (Test-Path (Join-Path $Root 'vendor'))) {
    Write-Host "Incluindo vendor (pesado)..."
    Copy-Tree (Join-Path $Root 'vendor') (Join-Path $Dest 'vendor')
}
if ($IncludeNodeModules -and (Test-Path (Join-Path $Root 'node_modules'))) {
    Write-Host "Incluindo node_modules (pesado)..."
    Copy-Tree (Join-Path $Root 'node_modules') (Join-Path $Dest 'node_modules')
}

# README do deploy
@'
LMS Codar — pacote DEPLOY (enxuto)
=================================

Este pacote NÃO inclui:
- vendor/          (~180 MB) — rode no servidor: composer install --no-dev --optimize-autoloader
- node_modules/    (~60 MB)  — CSS/JS já vão em public/build
- backups/, PDFS/, docs/, tests/, .git/

1) Preencha o banco
   - Copie .env.production.example para .env
   - Preencha DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD e APP_URL
   - Gere a chave: php artisan key:generate

2) Dependências PHP
   composer install --no-dev --optimize-autoloader

3) Ligação do storage
   php artisan storage:link

4) Banco
   - MySQL: php artisan migrate --force
     (depois importe/cadastre os dados; o SQLite local NÃO sobe automático para MySQL)
   - Ou use SQLite se a hospedagem permitir (já vai database/database.sqlite)

5) Permissões (Linux)
   chmod -R ug+rwx storage bootstrap/cache

Configuração enxuta já prevista no .env de produção:
- LOG_LEVEL=error + LOG_DAILY_DAYS=3
- SESSION_DRIVER=file
- CACHE_STORE=file
- QUEUE_CONNECTION=sync
- APP_DEBUG=false

Isso evita logs enormes e tabelas de sessão/cache inchando o banco.
'@ | Set-Content -Path (Join-Path $Dest 'LEIA-ME-DEPLOY.txt') -Encoding utf8

function Get-DirMB([string]$path) {
    if (-not (Test-Path $path)) { return 0 }
    $sum = (Get-ChildItem -LiteralPath $path -Recurse -Force -File -ErrorAction SilentlyContinue | Measure-Object Length -Sum).Sum
    if ($null -eq $sum) { return 0 }
    return [math]::Round($sum / 1MB, 2)
}

$total = Get-DirMB $Dest
Write-Host ""
Write-Host "DEPLOY pronto: $Dest"
Write-Host ("Tamanho total: {0} MB" -f $total)
Get-ChildItem $Dest -Force | ForEach-Object {
    $mb = if ($_.PSIsContainer) { Get-DirMB $_.FullName } else { [math]::Round($_.Length/1MB, 2) }
    '{0,-28} {1,8} MB' -f $_.Name, $mb
} | Write-Host
