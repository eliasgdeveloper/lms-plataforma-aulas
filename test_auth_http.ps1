#!/usr/bin/pwsh
# Teste HTTP completo com autenticação

$baseUrl = "http://localhost:8000"
$loginUrl = "$baseUrl/login"
$adminUrl = "$baseUrl/admin/usuarios/4"
$debugUrl = "$baseUrl/debug/admin/user/4"

Write-Host "=====================================================================" -ForegroundColor Cyan
Write-Host "TESTE HTTP COM AUTENTICAÇÃO - User Admin (ID 1)" -ForegroundColor Cyan
Write-Host "=====================================================================" -ForegroundColor Cyan

# 1. Obter CSRF token da página de login
Write-Host "`n1️⃣ Obtendo CSRF token..." -ForegroundColor Yellow
$loginPage = curl.exe -s "$loginUrl"
$csrfMatch = [regex]::Match($loginPage, 'name="csrf__token"[^>]*value="([^"]*)"')
if (-not $csrfMatch.Success) {
    $csrfMatch = [regex]::Match($loginPage, 'name="_token"[^>]*value="([^"]*)"')
}
if (-not $csrfMatch.Success) {
    Write-Host "❌ CSRF token não encontrado" -ForegroundColor Red
    exit 1
}
$csrfToken = $csrfMatch.Groups[1].Value
Write-Host "✅ CSRF token obtido: $($csrfToken.Substring(0, 20))..." -ForegroundColor Green

# 2. Fazer login
Write-Host "`n2️⃣ Fazendo login com admin@example.com..." -ForegroundColor Yellow
$response = curl.exe -s -c "c:\temp\cookies.txt" -X POST "$loginUrl" `
    -H "Content-Type: application/x-www-form-urlencoded" `
    -d "_token=$csrfToken&email=admin%40example.com&password=password&remember=on" `
    -i 2>&1

Write-Host "Response headers:" -ForegroundColor Yellow
$response | Select-Object -First 20

# 3. Acessar pág de admin usuários
Write-Host "`n3️⃣ Acessando /admin/usuarios (autenticado)..." -ForegroundColor Yellow
curl.exe -s -b "c:\temp\cookies.txt" "$baseUrl/admin/usuarios" -o "c:\temp\admin_usuarios.html" 2>&1
$size = (Get-Item "c:\temp\admin_usuarios.html").Length
Write-Host "✅ Obtido ($size bytes)" -ForegroundColor Green

# 4. Acessar rota de debug com auth
Write-Host "`n4️⃣ Acessando /debug/admin/user/4 (com auth)..." -ForegroundColor Yellow
curl.exe -s -b "c:\temp\cookies.txt" "$debugUrl" 2>&1 | Write-Host

# 5. Tentar acessar /admin/usuarios/4 (show)
Write-Host "`n5️⃣ Acessando /admin/usuarios/4 (show)..." -ForegroundColor Yellow
curl.exe -s -b "c:\temp\cookies.txt" "$adminUrl" -o "c:\temp\admin_usuario_show.html" 2>&1
$page = Get-Content "c:\temp\admin_usuario_show.html"

# Checar se tem DEBUG
if ($page -match "DEBUG:") {
    Write-Host "✅ DEBUG section encontrado na resposta" -ForegroundColor Green
    $page | Select-String "DEBUG:" -A 15 | Write-Host
} else {
    if ($page -match "HTTP/1.1 500") {
        Write-Host "❌ HTTP 500 retornado" -ForegroundColor Red
    } else {
        Write-Host "⚠️ Resposta recebida mas sem DEBUG section" -ForegroundColor Yellow
    }
    $page | Select-String "error|ERROR|Exception|Fatal" | Select-Object -First 5
}

Write-Host "`n=====================================================================" -ForegroundColor Cyan
