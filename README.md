php8 mysql8

`0 0 * * * check.php host db user pass` - проверка валидности адресов

`0 12 * * * send.php host db user pass` - рассылка напоминаний

`php worker.php host db user pass &` - демон, запустить столько - сколько необходимо