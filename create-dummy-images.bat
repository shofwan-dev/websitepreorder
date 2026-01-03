@echo off
echo Creating dummy product images...

REM Create products directory if not exists
if not exist "storage\app\public\products" mkdir "storage\app\public\products"

REM Create dummy images using PowerShell
powershell -Command "$img = New-Object System.Drawing.Bitmap(400, 400); $graphics = [System.Drawing.Graphics]::FromImage($img); $graphics.Clear([System.Drawing.Color]::FromArgb(212, 160, 23)); $font = New-Object System.Drawing.Font('Arial', 48, [System.Drawing.FontStyle]::Bold); $brush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White); $graphics.DrawString('Kaligrafi', $font, $brush, 50, 150); $img.Save('storage\app\public\products\kaligrafi-allah-1.jpg', [System.Drawing.Imaging.ImageFormat]::Jpeg); $img.Dispose(); $graphics.Dispose();"

powershell -Command "$img = New-Object System.Drawing.Bitmap(400, 400); $graphics = [System.Drawing.Graphics]::FromImage($img); $graphics.Clear([System.Drawing.Color]::FromArgb(212, 160, 23)); $font = New-Object System.Drawing.Font('Arial', 48, [System.Drawing.FontStyle]::Bold); $brush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White); $graphics.DrawString('Muhammad', $font, $brush, 50, 150); $img.Save('storage\app\public\products\kaligrafi-muhammad-1.jpg', [System.Drawing.Imaging.ImageFormat]::Jpeg); $img.Dispose(); $graphics.Dispose();"

echo.
echo Dummy images created successfully!
echo - storage\app\public\products\kaligrafi-allah-1.jpg
echo - storage\app\public\products\kaligrafi-muhammad-1.jpg
echo.
pause
