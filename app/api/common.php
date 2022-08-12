<?php
// 这是系统自动生成的公共文件

use think\Paginator;

if (!function_exists("renderResponse")) {

    function renderResponse(mixed $responseData = null)
    {
        $applyResponse = [
            'errCode' => 1000000,
            'errMessage' => 'success',
            'responseData' => $responseData,
        ];

        return json($applyResponse);
    }
}

if (!function_exists("renderPaginateResponse")) {

    function renderPaginateResponse(Paginator $paginator)
    {
        $applyResponse = ['listItem' => $paginator->items(), 'pageItem' => [
            'totalCount' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'perPage' => $paginator->listRows()],
        ];

        return renderResponse($applyResponse);
    }
}