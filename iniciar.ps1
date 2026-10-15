# Sobe a plataforma com o CSS do Tailwind já compilado.
# Não depende do servidor do Vite em http://[::1]:5173.
$ErrorActionPreference = "Stop"

$env:Path = [Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [Environment]::GetEnvironmentVariable("Path", "User")

$project = Split-Path -Parent $MyInvocation.MyCommand.Path
$php = Join-Path $env:USERPROFILE ".config\herd\bin\php84\php.exe"
$public = Join-Path $project "public"
$router = Join-Path $project "vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php"
$hot = Join-Path $public "hot"

if (-not (Test-Path $php)) {
    throw "PHP do Herd não encontrado em $php"
}
if (-not (Test-Path $router)) {
    throw "Arquivo do Laravel não encontrado em $router"
}

Set-Location $project

if (Test-Path $hot) {
    Remove-Item $hot -Force
}

npm run build
if ($LASTEXITCODE -ne 0) {
    throw "A compilação do Tailwind falhou."
}

Write-Host ""
Write-Host "Site: http://127.0.0.1:8090"
Write-Host "Deixe esta janela aberta. Para parar, pressione Ctrl+C."
Write-Host ""

Set-Location $public
& $php -d max_execution_time=300 -S 127.0.0.1:8090 $router
