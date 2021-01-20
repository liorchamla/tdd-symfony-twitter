<?php

namespace App\Tests\Feature\Account;

use App\Factory\TweetFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileTest extends WebTestCase
{

    /** @test */
    public function we_should_see_account_page()
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        $crawler = $client->request('GET', '/@' . $user->getUsername());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.username', $user->getUsername());
    }

    /** @test */
    public function we_cant_browse_an_unexisting_profile_page()
    {
        $client = static::createClient();

        $client->request('GET', '/@unexisting');

        $this->assertResponseStatusCodeSame(404);
    }

    /** @test */
    public function follow_button_should_be_seen_only_if_authenticated()
    {
        $client = static::createClient();

        $we = UserFactory::createOne();

        $user = UserFactory::createOne();

        $client->request('GET', '/@' . $user->getUsername());

        $this->assertSelectorNotExists('.follow');

        $client->loginUser($we->object());

        $client->request('GET', '/@' . $user->getUsername());

        $this->assertSelectorExists('.follow');
    }
}
