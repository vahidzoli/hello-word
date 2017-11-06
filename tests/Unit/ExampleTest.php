<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
//        $this->get('/api/ajax', ['lat' => '35.7600092958542','lng' => '51.509870348870754']);

        $response = $this->json('get', '/api/ajax', ['lat' => '35.7600092958542','lng' => '51.509870348870754']);

//        $response
//            ->assertStatus(200);
    }
}
