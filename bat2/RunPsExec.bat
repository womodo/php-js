@echo off

PsExec.exe \\192.168.1.10 -u vboxuser -p changeme -i cmd /c "cd C:\Users\vboxuser\Desktop\ && YourScript0.bat"
