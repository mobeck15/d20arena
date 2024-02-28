@echo off
rem Check if the vendor directory exists
if exist vendor\ (
    rem If the vendor directory exists, run PHPUnit using the command with vendor
    php vendor\phpunit\phpunit\phpunit ddapi\tests --coverage-html coverage-report/
) else (
    rem If the vendor directory does not exist, run PHPUnit using the command without vendor
    php phpunit ddapi\tests --coverage-html coverage-report/
)