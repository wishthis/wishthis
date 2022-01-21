Write-Host "second string"

$Version = Read-Host "What version would you like to release?"

$VersionRegEx = "define\('VERSION', '([\d\.]+)'\);"

$IndexPHP = (Get-Content -Path .\index.php -Raw) -Replace $VersionRegEx, "`define('VERSION', '$Version');" | Set-Content -Path .\index.php

IEX 'composer install --no-dev'
IEX 'npm install --only=production --no-optional'

Read-Host
