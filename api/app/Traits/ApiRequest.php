<?php


namespace App\Traits;


use App\Constants\ErrorCode;
use App\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

trait ApiRequest
{
    /**
     * 最大重试次数
     *
     * @var int
     */
    protected int $maxRetries = 3;

    /**
     * 每次重试的时间间隔
     *
     * @var int
     */
    protected int $retryDelay = 1000;

    /**
     * 请求超时时间
     *
     * @var int
     */
    protected int $timeout = 30;

    /**
     * 默认header信息
     *
     * @var array|string[]
     */
    protected array $headers = [
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json',
    ];


    /**
     * Get请求
     *
     * @param $url
     * @param array $headers
     * @return array|mixed
     * @throws ApiException
     */
    public function get($url, array $headers = [])
    {
        return $this->request('get', $url, [], $headers);
    }

    /**
     * Post请求
     *
     * @param $url
     * @param array $data
     * @param array $headers
     * @return array|mixed
     * @throws ApiException
     */
    public function post($url, array $data = [], array $headers = [])
    {
        return $this->request('post', $url, $data, $headers);
    }

    /**
     * Put请求
     *
     * @param $url
     * @param array $data
     * @param array $headers
     * @return array|mixed
     * @throws ApiException
     */
    public function put($url, array $data = [], array $headers = [])
    {
        return $this->request('put', $url, $data, $headers);
    }

    /**
     * 设置重试次数
     *
     * @param int $count
     * @return $this
     */
    public function setMaxRetries(int $count): static
    {
        $this->maxRetries = $count;
        return $this;
    }


    /**
     * 设置重试间隔
     *
     * @param int $count
     * @return $this
     */
    public function setRetryDelay(int $retryDelay): static
    {
        $this->retryDelay = $retryDelay;
        return $this;
    }

    /**
     * 设置超时时间
     *
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array|mixed
     * @throws ApiException
     */
    public function request(string $method, string $url, array $data, array $headers)
    {
        $success = false;
        $result  = [];
        while ($this->maxRetries > 0) {
            try {
                $client = new Client([
                    'timeout' => $this->timeout,
                    'verify'  => false, // Disable SSL verification
                ]);

                $headers = array_merge($this->headers, $headers);

                $response = $client->request($method, $url, [
                    'headers' => $headers,
                    'json'    => $data,
                ]);

                $result  = $response->getBody()->getContents();
                $result  = json_decode($result, true) ?? $result;
                $success = true;
                break;
            } catch (\Exception $e) {
                Log::error('request fail', [
                    'code'   => $e->getCode(),
                    'msg'    => $e->getMessage(),
                    'params' => [
                        'count'   => $this->maxRetries,
                        'url'     => $url,
                        'headers' => $headers,
                        'data'    => json_encode($data),
                    ]]);

                $this->maxRetries--;
                usleep($this->retryDelay);
            } catch (GuzzleException $e) {
                Log::error('request GuzzleException fail', [
                    'code'   => $e->getCode(),
                    'msg'    => $e->getMessage(),
                    'params' => [
                        'count'   => $this->maxRetries,
                        'url'     => $url,
                        'headers' => $headers,
                        'data'    => json_encode($data),
                    ]]);

                $this->maxRetries--;
                usleep($this->retryDelay);
            }
        }

        if ($success) {
            return $result;
        } else {
            throw new ApiException(ErrorCode::SERVER_ERROR, 'request data failed');
        }
    }
}
