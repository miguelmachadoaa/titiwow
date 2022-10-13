<?php 

phpinfo();


#declare(strict_types=1);

$ffi = FFI::cdef(
    'unsigned long GetCurrentProcessId(void);',
    "C:\\xampp\\htdocs\\titiwow\\public\\CliSiTef32I.dll"
);
var_dump($ffi->GetCurrentProcessId());


 ?>