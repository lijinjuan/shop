<?php
// 这是系统自动生成的公共文件

if (!function_exists("renderResponse")) {

    function renderResponse(mixed $responseData = null)
    {
        $applyResponse = [
            'errCode' => 1000000,
            'errMessage' => 'success',
            'responseData' => $responseData,
        ];

        return json($applyResponse, 200);
    }
}