<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testHealthCheck()
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
        $response->assertJson([ 'message' => 'success']);
    }
}
