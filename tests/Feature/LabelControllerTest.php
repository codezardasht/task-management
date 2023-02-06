<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index method.
     *
     * @return void
     */
    public function testIndex()
    {
        $labels = factory(Label::class, 3)->create();

        $response = $this->get(route('labels.index'));

        $response->assertStatus(200)
            ->assertJson([
                'data' => $labels->toArray()
            ]);
    }

}
