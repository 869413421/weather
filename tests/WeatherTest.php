<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/4/28
 * Time: 13:47
 */

namespace Ym\Weather\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Ym\Wather\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ym\Wather\Weather;

class WeatherTest extends TestCase
{
    public function testGetWeatherWithInvalidType()
    {
        $w = new Weather('3a1391561548f8a8f464e82d235f64ce');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Invalid value(base/all):foo');

        $w->getWather('深圳', 'foo');

        $this->fail('没有抛出正确异常');
    }

    public function testGetWeatherWithInvalidFormat()
    {
        $w = new Weather('3a1391561548f8a8f464e82d235f64ce');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Invalid value(json/xml):array');

        $w->getWather('广州', 'base', 'array');

        $this->fail('没有预期抛出异常');
    }

    public function testGetHttpClient()
    {

    }

    public function testSetGuzzleOptions()
    {
        $respone = new Response(['success' => true]);

        $clent = \Mockery::mock(Client::class);


    }


    public function testGetWather()
    {
        $respone = new Response(200, [], '{"success"=true}');

        $client = \Mockery::mock(Client::class);

        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo?parameters',
            [
                'query' => [
                    'key' => '3a1391561548f8a8f464e82d235f64ce',
                    'city' => '深圳',
                    'output' => 'json',
                    'extensions' => 'base',

                ]
            ])->andReturn($respone);

        $w = \Mockery::mock(Weather::class, ['3a1391561548f8a8f464e82d235f64ce']);

        $w->getHttpClient()->andReturn($client);

        $this->assertSame(['success' => true], $w->getWather());

    }
}