param(
  [Parameter(Mandatory=$false)]
  [string]$LocalDb = "benson",

  [Parameter(Mandatory=$false)]
  [string]$LocalUser = "root",

  [Parameter(Mandatory=$false)]
  [string]$LocalPassword = "",

  [Parameter(Mandatory=$true)]
  [string]$CloudHost,

  [Parameter(Mandatory=$false)]
  [int]$CloudPort = 3306,

  [Parameter(Mandatory=$true)]
  [string]$CloudDb,

  [Parameter(Mandatory=$true)]
  [string]$CloudUser,

  [Parameter(Mandatory=$false)]
  [string]$CloudPassword = "",

  [Parameter(Mandatory=$false)]
  [string]$MySqlBin = "",

  [Parameter(Mandatory=$false)]
  [string]$DumpPath = ""
)

$ErrorActionPreference = "Stop"

function Resolve-MySqlBin {
  param([string]$MySqlBin)

  if ($MySqlBin -and (Test-Path $MySqlBin)) {
    return (Resolve-Path $MySqlBin).Path
  }

  $commonWamp = "C:\\wamp64\\bin\\mysql"
  if (Test-Path $commonWamp) {
    $candidate = Get-ChildItem -Path $commonWamp -Directory |
      Sort-Object Name -Descending |
      Select-Object -First 1
    if ($candidate) {
      $bin = Join-Path $candidate.FullName "bin"
      if (Test-Path $bin) {
        return (Resolve-Path $bin).Path
      }
    }
  }

  $mysqldumpCmd = Get-Command mysqldump -ErrorAction SilentlyContinue
  if ($mysqldumpCmd) {
    return Split-Path $mysqldumpCmd.Path -Parent
  }

  throw "Could not locate MySQL bin folder. Pass -MySqlBin (example: C:\\wamp64\\bin\\mysql\\mysql8.0.xx\\bin)."
}

$binPath = Resolve-MySqlBin -MySqlBin $MySqlBin

$mysqldump = Join-Path $binPath "mysqldump.exe"
$mysql = Join-Path $binPath "mysql.exe"

if (-not (Test-Path $mysqldump)) { throw "mysqldump.exe not found at: $mysqldump" }
if (-not (Test-Path $mysql)) { throw "mysql.exe not found at: $mysql" }

if (-not $DumpPath) {
  $DumpPath = Join-Path (Get-Location) ("{0}_dump_{1}.sql" -f $LocalDb, (Get-Date -Format "yyyyMMdd_HHmmss"))
}

Write-Host "Exporting local DB '$LocalDb' to $DumpPath" -ForegroundColor Cyan

$env:MYSQL_PWD = $LocalPassword
& $mysqldump --no-tablespaces --single-transaction --quick --routines --triggers --events --default-character-set=utf8mb4 -u $LocalUser $LocalDb | Out-File -FilePath $DumpPath -Encoding utf8
Remove-Item Env:MYSQL_PWD -ErrorAction SilentlyContinue

Write-Host "Importing into cloud DB '$CloudDb' on $CloudHost:$CloudPort" -ForegroundColor Cyan

$env:MYSQL_PWD = $CloudPassword
Get-Content -Path $DumpPath -Raw | & $mysql -h $CloudHost -P $CloudPort -u $CloudUser $CloudDb
Remove-Item Env:MYSQL_PWD -ErrorAction SilentlyContinue

Write-Host "Done. Next step on Laravel Cloud: run 'php artisan migrate --force'" -ForegroundColor Green
