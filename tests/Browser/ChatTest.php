<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ChatPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChatTest extends DuskTestCase
{
    /**
     * @test A user can send a message
     *
     * @return void
     */
    public function a_user_can_send_a_message()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
          $browser->loginAs($user)
              ->visit(new ChatPage)
              ->typeMessage('Hi there')
              ->sendMessage()
              ->assertInputValue('@body', '')
              ->with('@chatMessages', function ($messages) use ($user) {
                $messages->assertSee('Hi there')
                         ->assertSee($user->name);
              });
        });
    }
}