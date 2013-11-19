<?php


return array(
    'site_dom' => "http://fw.ru",
    'db' => array (
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'select' => 'fw_test',
        //'select' => 'exx',
    ),
    'adm' => 'adm',
    'tmpl' => 'tmpl', // каталог с шаблонами
    'tmpl_name' => 'first', // используемый шаблон
    'lang' => 'ru', // язык по-умолчанию
    'cookie_expire' => 3600, // вермя действия cookie, 3600 = 1 час
    'token' => 1, // включить или выключить проверку token (1 - включено, 0 - выключено)
    'files' => 'files',   
);

