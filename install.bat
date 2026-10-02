@echo off
echo ================================================
echo   INSTALLER APLIKASI DISPENSASI SEKOLAH
echo   Dibuat untuk pemula - Tinggal double-click!
echo ================================================
echo.

REM Cek apakah Composer sudah terinstall
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Composer belum terinstall!
    echo Silakan download Composer di: https://getcomposer.org/download/
    echo Setelah install Composer, jalankan file ini lagi.
    pause
    exit /b 1
)

REM Cek apakah PHP sudah terinstall
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] PHP belum terinstall atau tidak ada di PATH!
    echo Pastikan XAMPP sudah terinstall dan PHP ada di PATH.
    echo Tambahkan C:\xampp\php ke Environment Variable PATH.
    pause
    exit /b 1
)

echo [OK] Composer ditemukan.
echo [OK] PHP ditemukan.
echo.

REM Buat proyek Laravel baru di folder sementara
echo [1/7] Membuat proyek Laravel baru...
echo       (Ini mungkin butuh 3-5 menit, tergantung kecepatan internet)
echo.
cd /d %~dp0
cd ..
composer create-project laravel/laravel dispensasi-temp --no-interaction

if %errorlevel% neq 0 (
    echo [ERROR] Gagal membuat proyek Laravel!
    echo Cek koneksi internet kamu dan coba lagi.
    pause
    exit /b 1
)

echo.
echo [2/7] Menyalin file-file aplikasi ke proyek Laravel...

REM Copy semua file dari folder dispensasi-sekolah ke dispensasi-temp
xcopy /E /Y /I "%~dp0app" "dispensasi-temp\app\"
xcopy /E /Y /I "%~dp0database" "dispensasi-temp\database\"
xcopy /E /Y /I "%~dp0resources" "dispensasi-temp\resources\"
xcopy /E /Y /I "%~dp0routes" "dispensasi-temp\routes\"
xcopy /Y "%~dp0bootstrap\app.php" "dispensasi-temp\bootstrap\"
xcopy /Y "%~dp0.env.example" "dispensasi-temp\"

echo [OK] File berhasil disalin.

REM Masuk ke folder proyek Laravel
cd dispensasi-temp

echo.
echo [3/7] Menginstall library tambahan (dompdf untuk PDF)...
composer require barryvdh/laravel-dompdf

echo.
echo [4/7] Setup file konfigurasi .env...
copy .env.example .env
php artisan key:generate

echo.
echo [5/7] Membuat link storage...
php artisan storage:link

echo.
echo ================================================
echo  LANGKAH SELANJUTNYA (Manual):
echo ================================================
echo.
echo  1. Buka phpMyAdmin: http://localhost/phpmyadmin
echo  2. Buat database baru bernama: dispensasi_sekolah
echo  3. Buka file .env dan sesuaikan:
echo       DB_DATABASE=dispensasi_sekolah
echo       DB_USERNAME=root
echo       DB_PASSWORD=
echo  4. Jalankan perintah ini di CMD:
echo       cd /d "%cd%"
echo       php artisan migrate --seed
echo       php artisan serve
echo  5. Buka browser: http://localhost:8000
echo.
echo  Login demo:
echo    Admin     : admin@sekolah.com / password
echo    Guru Piket: piket@sekolah.com / password
echo    Siswa     : siswa@sekolah.com / password
echo.
echo ================================================
echo  Folder proyek ada di:
echo  %~dp0..\dispensasi-temp\
echo ================================================
echo.
pause
