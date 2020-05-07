<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function userCanMakeOrder()
    {
        $products = factory(Menu::class, 20)->create();

        $firstProductId = $products->pluck('id')->splice(0,1)->first();
        $secondProductId = $products->pluck('id')->splice(1,1)->first();

        $orderData = [
            'products' => [
                $firstProductId => 4,
                $secondProductId => 5
            ] ,
            'name' => 'Oluwaseyi Adeogun',
            'address' => 'Lagos, Nigeria'
        ];

        $response = $this->post(route('order.store'), $orderData);

        $response->assertSuccessful()
            ->assertJsonFragment([ 'message' => 'Order created successfully' ]);

        $this->assertDatabaseHas('orders', [
            'name' => $orderData['name'],
            'address' => $orderData['address']
        ] );
    }

    /**
     * @test
     */
    public function userCanNotMakeOrderWithWrongMenu()
    {
        factory(Menu::class, 8)->create();

        $lastInsertId = \DB::getPdo()->lastInsertId();

        $orderData = [
            'products' => [
                ++$lastInsertId => 4
            ] ,
            'name' => 'Oluwaseyi Adeogun',
            'address' => 'Lagos, Nigeria'
        ];

        $response = $this->post(route('order.store'), $orderData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [ 'products' ]
            ]);

        $this->assertDatabaseMissing('orders', [
            'name' => $orderData['name'],
            'address' => $orderData['address']
        ] );
    }
}
