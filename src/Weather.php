<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/4/26
 * Time: 16:13
 */

namespace Ym\Wather;


use GuzzleHttp\Client;
use Ym\Wather\Exceptions\Exception;
use Ym\Wather\Exceptions\HttpException;
use Ym\Wather\Exceptions\InvalidArgumentException;

class Weather
{
    protected $key;
    protected $guzzleOptions = [];

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setHttpClientOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    public function getWeather($city, $type = 'base', $format = 'json')
    {
        if (!in_array(strtolower($type), ['base', 'all']))
        {
            throw new InvalidArgumentException('Invalid value(base/all):' . $type);
        }

        if (!in_array(strtolower($format), ['json', 'xml']))
        {
            throw new InvalidArgumentException('Invalid value(json/xml):' . $format);
        }

        $url = 'https://restapi.amap.com/v3/weather/weatherInfo?parameters';

        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => strtolower($format),
            'extensions' => strtolower($type)
        ]);

        try
        {
            $respone = $this->getHttpClient()->get($url, [
                'query' => $query
            ])
                ->getBody()
                ->getContents();

            return $format === 'json' ? json_encode($respone) : $respone;
        }
        catch (\Exception $e)
        {
            throw  new HttpException($e->getMessage(), $e->getCode(), $e);
        }

    }
}