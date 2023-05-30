<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Films, séries TV et popcorn en illimité.');


    }
    public function testShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/movies/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Community');
    }

    public function testAddReviewWithoutSecurity()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/movies/71/review/add');

        $this->assertResponseIsSuccessful();

        $submitButton = $crawler->selectButton('Ajouter');
        $form = $submitButton->form();
        $form['review[username]'] = 'Boris';
        $form['review[email]'] = 'boris@boris.com';
        $form['review[content]'] = "ce soir chez Boris, c'est soirée Disco";
        $form['review[rating]'] = 4;

        $form['review[reactions]'] = ["smile", "cry"];
        $form['review[watchedAt]'] = '2023-05-23';
        
        $client->submit($form);

        $this->assertResponseRedirects();

    }
}
