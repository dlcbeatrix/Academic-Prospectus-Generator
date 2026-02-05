@echo OFF
:: in case DelayedExpansion is on and a path contains ! 
setlocal DISABLEDELAYEDEXPANSION
C:\isw\xampp\php\php "%~dp0composer.phar" %*
