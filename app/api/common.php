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

if (!function_exists("assertTreeDatum")) {
    function assertTreeDatum(array $arrayAccess)
    {
        $items = [];
        foreach ($arrayAccess as $arrayItem) {
            $items[$arrayItem['id']] = $arrayItem;
        }
        $treeDatum = [];
        foreach ($items as $k => $item) {
            if (isset($items[$item['parentID']])) {
                $items[$item['parentID']]['categories'][] = &$items[$k];
            } else {
                $treeDatum[] = &$items[$k];
            }
        }
        return $treeDatum;
    }
}
if (!function_exists('makeOrderNo')) {
    function makeOrderNo(): string
    {
        $yCode = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        return $yCode[intval(date('Y')) - 2022] . strtoupper(dechex((int) date('m'))) . date(
                'd'
            ) . substr((string) time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d',
                rand(0, 99)
            );
    }
}