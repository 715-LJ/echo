<?php

use App\Constants\ErrorCode;
use App\Exceptions\ApiException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if (!function_exists('uuid')) {
    function uuid(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}

if (!function_exists('currentTime')) {
    function currentTime(): string
    {
        return strtotime(gmdate('Y-m-d H:i:s')); // 输出当前的 UTC 时间戳
    }
}

if (!function_exists('isValidTimestamp')) {
    function isValidTimestamp($timestamp): bool
    {
        // 检查时间戳是否在有效范围内 (1970年1月1日之后)
        if ($timestamp < 0 || $timestamp > PHP_INT_MAX) {
            return false;
        }

        // 使用 date 函数检查是否为有效日期
        $date = date('Y-m-d', $timestamp);
        if (strtotime($date) != $timestamp) {
            return false;
        }

        list($year, $month, $day) = explode('-', $date);
        return checkdate((int)$month, (int)$day, (int)$year);
    }
}

if (!function_exists('encryptData')) {
    /**
     * 对称加密
     *
     * @param string $data 要加密的数据
     * @param string $method 加密算法（例如：AES-256-CBC）
     * @param string $key 密钥
     * @param int $options 加密选项
     * @param string|null $iv 初始化向量
     *
     * @return string 加密后的数据
     */
    function encryptData(string $data, string $key = '', string $method = 'AES-256-CBC', int $options = 0, string $iv = null): string
    {
        // 生成初始化KEY
        if (!$key) {
            $key = getenv('APP_KEY');
        }

        // 生成初始化向量
        if ($iv === null) {
            $ivSize = openssl_cipher_iv_length($method);
            $iv     = openssl_random_pseudo_bytes($ivSize);
        }

        // 使用 openssl_encrypt 进行加密
        $encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);

        // 可以将初始化向量与加密后的数据一起存储，以便后续解密时使用
        return urlencode(base64_encode($iv . $encryptedData));
    }
}


if (!function_exists('decryptData')) {
    /**
     * 对称解密
     *
     * @param string $encryptedData 加密后的数据
     * @param string $method 解密算法（例如：AES-256-CBC）
     * @param string $key 密钥
     * @param int $options 解密选项
     *
     * @return string 解密后的数据
     */
    function decryptData(string $encryptedData, string $key = '', string $method = 'AES-256-CBC', int $options = 0): string
    {
        try {
            // 生成初始化KEY
            if (!$key) {
                $key = getenv('APP_KEY');
            }

            // 解码 base64 编码的数据
            $decodedData = base64_decode(urldecode($encryptedData));

            // 提取 IV
            $ivSize = openssl_cipher_iv_length($method);
            $iv     = substr($decodedData, 0, $ivSize);

            // 提取加密后的数据
            $encryptedDataOnly = substr($decodedData, $ivSize);

            // 使用 openssl_decrypt 进行解密
            return openssl_decrypt($encryptedDataOnly, $method, $key, $options, $iv);
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('getRandom')) {
    function getRandom(int $length = 6): string
    {
        $numbers = range(0, 9);
        $letters = array_merge(range('a', 'z'), range('A', 'Z'));

        $result = [];

        // 确保至少有一个数字和一个字母
        $result[] = $numbers[mt_rand(0, count($numbers) - 1)];
        $result[] = $letters[mt_rand(0, count($letters) - 1)];

        // 剩余部分生成随机字符
        for ($i = 2; $i < $length; $i++) {
            $charset  = mt_rand(0, 1) ? $numbers : $letters;
            $result[] = $charset[mt_rand(0, count($charset) - 1)];
        }

        // 打乱字符串顺序
        return implode('', $result);
    }
}


if (!function_exists('unzip')) {
    function unzip(string $zipFile, string $path = '', string $uploadPath = ''): bool
    {
        $fileInfo = pathinfo($zipFile);
        $path     = $path ?: storage_path('/app/files/' . $fileInfo['filename']);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if ($zipFile instanceof UploadedFile) {
            $fileInfo = pathinfo($zipFile->getClientOriginalName());
        }

        $delFile = false;
        // 如果是远程链接，先下载到本地
        if (filter_var($zipFile, FILTER_VALIDATE_URL) !== false) {
            // 下载文件到本地
            $localFile   = sprintf('%s/%s', rtrim($path), $fileInfo['basename']);
            $fileContent = file_get_contents($zipFile);
            file_put_contents($localFile, $fileContent);
            $delFile = true;
            $zipFile = $localFile;
        }

        $result = false;
        if ($fileInfo['extension'] == 'zip' || $fileInfo['extension'] == 'docx') {
            $zip = new \ZipArchive();
            if ($zip->open($zipFile) === true) {
                // 解压文件
                $result = $zip->extractTo($path);
                $zip->close();
            }
        } else {
            $tmpZipFile = escapeshellarg($zipFile);
            $tmpPath    = escapeshellarg(rtrim($path, '/') . '/');
            $command    = "unrar x {$tmpZipFile} {$tmpPath} > /dev/null 2>&1";
            system($command, $code);
            Log::info("unrar command", [
                'command' => $command,
                'code'    => $code,
            ]);
            if ($code == 0) {
                $result = true;
            }
        }
        // 删除文件
        if ($delFile) {
            unlink($zipFile);
        }

        // 解压后需要上传到OSS
        if ($uploadPath) {
            $fileName       = $fileInfo['filename'];
            $unzipLocalPath = $path . '/' . $fileName;
            if (!file_exists($unzipLocalPath)) {
                $unzipLocalPath = $path;
            }
            $uploadPath = trim($uploadPath, '/');
            uploadDir($unzipLocalPath, $uploadPath);
        }

        return $result;
    }
}

if (!function_exists('is_json')) {
    function is_json(string $str)
    {
        json_decode($str);
        return json_last_error() == JSON_ERROR_NONE;
    }
}

if (!function_exists('encode')) {

    function encode($content)
    {
        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('decode')) {

    function decode($content)
    {
        return json_decode($content, true);
    }
}

if (!function_exists('cache_get')) {
    function cache_get($key)
    {
        return Redis::get($key);
    }
}

if (!function_exists('cache_set')) {
    function cache_set($key, $value, $duration = 0)
    {
        if ($duration) {
            return Redis::set($key, $value, 'EX', $duration, 'NX');
        } else {
            return Redis::set($key, $value);
        }
    }
}

if (!function_exists('cache_delete')) {
    function cache_delete($key)
    {
        Redis::del($key);
    }
}

if (!function_exists('data_must_get')) {
    function data_must_get($target, $key)
    {
        $result = data_get($target, $key);
        if (!$result) {
            throw new ApiException(ErrorCode::CUSTOM_ERROR_DATA_NOT_EXISTS, "The data $key does not exist");
        }
        return $result;
    }
}

if (!function_exists('deleteFolder')) {
    /**
     * 删除文件夹
     *
     * @param $dir
     * @return bool
     */
    function deleteFolder($dir): bool
    {
        if (!file_exists($dir)) {
            return false;
        }
        if (!is_dir($dir)) {
            return @unlink($dir);
        }
        $items = array_diff(scandir($dir), array('.', '..'));
        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                deleteFolder($path);
            } else {
                @unlink($path);
            }
        }
        $items = array_diff(scandir($dir), array('.', '..'));
        if (empty($items)) {
            return rmdir($dir);
        }
        return true;
    }
}

if (!function_exists('remoteFileExists')) {
    /**
     * 文件是否存在
     *
     * @param string $url
     * @return bool
     */
    function remoteFileExists(string $url): bool
    {
        $headers = @get_headers($url);
        if ($headers && strpos($headers[0], '200')) {
            return true;
        }
        return false;
    }
}


if (!function_exists('exportExcelToFile')) {
    /**
     * 导出excel到文件
     *
     * @param array $header
     * @param array $data
     * @param string $filename
     *
     * @throws \Exception
     */
    function exportExcelToFile(array $header, array $data, string $filename)
    {
        try {
            $spreadsheet = arrayToExcel($header, $data);
            $writer      = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($filename);
        } catch (\Exception $e) {
            throw new ApiException(ErrorCode::CUSTOM_ERROR_DATA_NOT_EXISTS, '导出excel到文件出错,msg:' . $e->getMessage());
        }
    }
}

if (!function_exists('exportExcelToBrowser')) {
    /**
     * 导出excel到浏览器
     *
     * @param array $header
     * @param array $data
     * @param string $filename
     *
     * @throws \Exception
     */
    function exportExcelToBrowser(array $header, array $data, string $filename)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        try {
            $spreadsheet = arrayToExcel($header, $data);
            $writer      = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            die;
        } catch (\Exception $e) {
            throw new ApiException(ErrorCode::CUSTOM_ERROR_DATA_NOT_EXISTS, '导出excel到浏览器出错,msg:' . $e->getMessage());
        }
    }
}

if (!function_exists('arrayToExcel')) {
    /**
     * 生成Spreadsheet,注意$header和$data要对应
     *
     * @param array $header 标题，格式如:['姓名', '年龄']
     * @param array $data 内容，格式如:[['张三', 20], ['李四', 30]]
     *
     * @return Spreadsheet|null
     * @throws \Exception
     */
    function arrayToExcel(array $header, array $data)
    {
        try {
            $spreadsheet = new Spreadsheet();
            $objSheet    = $spreadsheet->getActiveSheet();  //获取当前操作sheet的对象

            $rowIdx = 1;

            // 标题部分
            foreach ($header as $index => $item) {
                $objSheet->setCellValueExplicitByColumnAndRow(
                    $index + 1,
                    $rowIdx,
                    $item,
                    DataType::TYPE_STRING
                );
            }
            $rowIdx++;

            // 内容部分
            foreach ($data as $row) {
                foreach ($row as $index => $item) {
                    $objSheet->setCellValueExplicitByColumnAndRow(
                        $index + 1,
                        $rowIdx,
                        $item,
                        DataType::TYPE_STRING
                    );
                }
                $rowIdx++;
            }
            $spreadsheet->setActiveSheetIndex(0);
            return $spreadsheet;
        } catch (\Exception $e) {
            throw new ApiException(ErrorCode::CUSTOM_ERROR_DATA_NOT_EXISTS, $e->getMessage());
        }
    }
}


if (!function_exists('resizeImag')) {
    /**
     * 修改图片大小
     *
     * @param string $filePath
     * @param int $maxWidth
     * @return bool
     */
    function resizeImage(string $filePath, int $maxWidth = 794): bool
    {
        ini_set('memory_limit', '256M');

        // 获取图片的原始尺寸
        list($width, $height) = getimagesize($filePath);

        // 如果图片宽度小于或等于最大宽度，则不需要调整
        if ($width <= $maxWidth) {
            return true;
        }

        // 计算新的高度以保持比例
        $newHeight = ($maxWidth / $width) * $height;

        // 创建新的图片资源
        $imageResized = imagecreatetruecolor($maxWidth, $newHeight);

        // 创建原始图片资源
        $image = imagecreatefromstring(file_get_contents($filePath));

        // 调整大小并保存图片
        imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);

        // 获取文件扩展名
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // 根据扩展名保存图片
        switch ($ext) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($imageResized, $filePath);
                break;
            case 'png':
                imagepng($imageResized, $filePath);
                break;
            case 'gif':
                imagegif($imageResized, $filePath);
                break;
            default:
                return false; // 不支持的格式
        }

        // 释放资源
        imagedestroy($image);
        imagedestroy($imageResized);

        return true;
    }
}
