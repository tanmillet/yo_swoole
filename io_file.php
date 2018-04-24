<?php
/**
 * 异步读取文件内容
 * swoole_async_readfile会将文件内容全部复制到内存，所以不能用于大文件的读取
 *如果要读取超大文件，请使用swoole_async_read函数
 *swoole_async_readfile最大可读取4M的文件，受限于SW_AIO_MAX_FILESIZE宏
 **/
$result = swoole_async_readfile(__DIR__ . '/data/1.txt', function ($file_name, $file_content) {
    echo 'file name : ' . $file_name . PHP_EOL;
    echo 'file content : ' . $file_content . PHP_EOL;
});
echo 'Start:' . PHP_EOL;

echo 'read file result : ' . $result . PHP_EOL;

/**
 * 异步读文件，使用此函数读取文件是非阻塞的，当读操作完成时会自动回调指定的函数。
 * 此函数与swoole_async_readfile不同，它是分段读取，可以用于读取超大文件。每次只读$size个字节，不会占用太多内存。
 */
//$result = swoole_async_read(__DIR__ . '/data/1.txt', function ($file_name, $file_content) {
//    echo 'file name : ' . $file_name . PHP_EOL;
//    echo 'file content : ' . $file_content . PHP_EOL;
//});
//echo 'Start:' . PHP_EOL;
//
//echo 'read file result : ' . $result . PHP_EOL;
