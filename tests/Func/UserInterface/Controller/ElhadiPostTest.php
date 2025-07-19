<?php

namespace Tests\Func\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ElhadiPostTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Additional setup if needed
    }
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Directeur de Projet & Architecte SI');
        $this->assertSelectorTextContains('h1', 'Directeur de Projet & Architecte SI');
        $this->assertSelectorTextContains('h3', 'Elhadi Beddarem');
    }

    public function tearDown(): void
    {
        // Clean up after tests if necessary
        parent::tearDown();
    }
}
