@echo off
:loop
echo Launch api and node front end with react
npm run startdev
echo Restarting in 10 seconds
timeout /t 10
goto loop