<?php
return array(
'language' => array(
    'default'   => 'en',
    'detect'    => 'off',
    'map'       => array(
        'zh_cn'    => 'zh',
        'en_us'    => 'en',
    ),
),

'CountryLanguageMap' => array(
    'US' => 'en',
    'CN' => 'zh',),

'timezone'          => 'Asia/Shanghai',
'LanguageCodeList'  => require dirname(__FILE__).DIRECTORY_SEPARATOR.'LanguageCodeList.php',
);