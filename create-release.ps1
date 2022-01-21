Write-Host "second string"

$Version = Read-Host "What version would you like to release?"

$VersionRegEx = "define\('VERSION', '([\d\.]+)'\);"

$IndexPHP = (Get-Content -Path .\index.php -Raw) -Replace $VersionRegEx, "`define('VERSION', '$Version');" | Set-Content -Path .\index.php

IEX 'composer update --no-dev --with-all-dependencies --ignore-platform-req=php'
IEX 'npm update --only=production --no-optional'

Read-Host
