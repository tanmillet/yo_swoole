<?php
/**
 * 异步读取文件内容
 * swoole_async_readfile会将文件内容全部复制到内存，所以不能用于大文件的读取
 *如果要读取超大文件，请使用swoole_async_read函数
 *swoole_async_readfile最大可读取4M的文件，受限于SW_AIO_MAX_FILESIZE宏
 **/
//$result = swoole_async_readfile(__DIR__ . '/data/1.txt', function ($file_name, $file_content) {
//    echo 'file name : ' . $file_name . PHP_EOL;
//    echo 'file content : ' . $file_content . PHP_EOL;
//});
//echo 'Start:' . PHP_EOL;
//
//echo 'read file result : ' . $result . PHP_EOL;

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
/**
 * 参数1为文件的名称，必须有可写权限，文件不存在会自动创建。打开文件失败会立即返回false
 * 参数2为要写入到文件的内容，最大可写入4M
 * 参数3为写入成功后的回调函数，可选
 * 参数4为写入的选项，可以使用FILE_APPEND表示追加到文件末尾
 * 如果文件已存在，底层会覆盖旧的文件内容
 */
$write_content = 'test:test,aasdfsd';
swoole_async_writefile(__DIR__ . '/data/2.log', $write_content, function ($file_name) {
    echo 'write file name : ' . $file_name . PHP_EOL;
}, FILE_APPEND);

echo 'Start:' . PHP_EOL;

echo 'read file result : ' . $result . PHP_EOL;

//bool swoole_async_write(string $filename, string $content, int $offset = -1, mixed $callback = NULL);
/**
 * 当offset为-1时表示追加写入到文件的末尾
 * Linux原生异步IO不支持追加模式，并且$content的长度和$offset必须为512的整数倍。
 * 如果传入错误的字符串长度或者$offset写入会失败，并且错误码为EINVAL
 */
//swoole_async_write(__DIR__ . '/data/2.log' , $write_content , -1 , function ($file_name){
//    echo 'write file name : ' . $file_name . PHP_EOL;
//});
//echo 'Start:' . PHP_EOL;
//
//echo 'read file result : ' . $result . PHP_EOL;