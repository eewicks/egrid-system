@echo off
echo ========================================
echo Starting Laravel Server for Network Access
echo ========================================
echo.
echo This will make the server accessible from:
echo - Localhost: http://127.0.0.1:8000
echo - Network: http://192.168.3.13:8000
echo - Arduino can now connect!
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

php artisan serve --host=127.0.0.1 --port=8000

pause

