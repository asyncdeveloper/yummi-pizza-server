<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShowMenuTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function userCanViewAllMenu()
    {
        factory(Menu::class, 10)->create();

        $response = $this->get(route('menu.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [ 'id' , 'name' , 'description', 'price' ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function userCanViewOneMenu()
    {
        $menus = factory(Menu::class, 8)->create();
        $singleMenu = $menus->first();

        $response = $this->get(route('menu.show', $singleMenu->id ));

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $singleMenu->id,
                    'name' => $singleMenu->name ,
                    'description' => $singleMenu->description,
                    'price' => $singleMenu->price
                ]
            ]);
    }

    /**
     * @test
     */
    public function userCanNotViewInvalidMenu()
    {
        factory(Menu::class, 8)->create();

        $lastInsertId = \DB::getPdo()->lastInsertId();

        $response = $this->get(route('menu.show', ++$lastInsertId ));

        $response->assertStatus(404);
    }
}
