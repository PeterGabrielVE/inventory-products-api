<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;



class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;
  

    public function testIndex()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/products');
        $response->assertStatus(Response::HTTP_OK);
        
    }

    
    /**
     * Test for storing a new product.
     *
     * @return void
     */
    public function testStoreProduct()
    {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $data = [
            'name' => 'Test Product',
            'price' => 100,
            'quantity' => 1,
            'category_id' => 1,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201);
    
        $this->assertDatabaseHas('products', $data);
    }
}
