<?php


namespace App\Traits;


use App\Constants\ErrorCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use  \Illuminate\Http\Response as HttpResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait ApiResponse
{
    /**
     * @param $data
     * @param array $header
     * @return JsonResponse
     */
    public function success($data = null, array $header = []): JsonResponse
    {
        return $this->response(HttpResponse::HTTP_OK, 'success', $data, $header);
    }


    /**
     * @param int $code
     * @param string $message
     * @param $data
     * @param array $header
     * @return JsonResponse
     */
    public function error(int $code, string $message = '', $data = null, array $header = []): JsonResponse
    {
        return $this->response($code, $message, $data, $header);
    }


    /**
     * @param int $code
     * @param string $message
     * @param $data
     * @param array $header
     * @param int $options
     * @return JsonResponse
     */
    protected function response(int $code, string $message, $data, array $header = [], int $options = 0): JsonResponse
    {
        if (!$message) {
            if (isset(ErrorCode::CODE_MESSAGE[$code])) {
                $message = ErrorCode::CODE_MESSAGE[$code];
            } elseif (isset(HttpResponse::$statusTexts[$code])) {
                $message = HttpResponse::$statusTexts[$code];
            } else {
                $message = 'service error';
            }
        }

        // data数据处理
        if ($data instanceof Paginator) {
            $data = [
                'page'      => $data->currentPage(),
                'page_size' => $data->perPage(),
                'list'      => $data->items(),
            ];
        } elseif ($data instanceof JsonResource && $data->resource instanceof LengthAwarePaginator) {
            $data = [
                'total'     => $data->resource->total(),
                'page'      => $data->resource->currentPage(),
                'page_size' => $data->resource->perPage(),
                'list'      => $data,
            ];
        } elseif ($data instanceof LengthAwarePaginator) {
            $data = [
                'total'     => $data->total(),
                'page'      => $data->currentPage(),
                'page_size' => $data->perPage(),
                'list'      => $data->items(),
            ];
        } elseif ($data == null) {
            $data = [];
        }

        $response = [
            'code'       => $code,
            'message'    => ucfirst($message),
            'request_id' => REQUEST_ID,
            'data'       => $data,
        ];
        $status   = $code > 1000 ? HttpResponse::HTTP_OK : $code;
        return response()->json($response, HttpResponse::HTTP_OK, $header, $options);
    }
}
