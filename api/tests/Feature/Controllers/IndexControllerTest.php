<?php

namespace Tests\Feature\Controllers;

use Illuminate\Http\Response;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testHealthCheck(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(Response::HTTP_OK   );
        $response->assertJson([ 'message' => 'success']);
    }
}
