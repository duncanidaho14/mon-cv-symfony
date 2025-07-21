<?php

namespace Tests\Func\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GoogleControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Additional setup if needed
    }

    public function testCvLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Check if the response is successful
        $this->assertResponseIsSuccessful();

        // Check if the page contains the expected text
        $this->assertSelectorTextContains('h1', 'Directeur de Projet & Architecte SI');
        $this->assertArrayHasKey('email', $crawler->filter('form')->getvalues());
        $this->assertArrayHasKey('password', $crawler->filter('form')->first()->getValues());
    }


    public function testGoogleLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connect/google');

        // Check if the response is successful
        $this->assertResponseIsSuccessful();

        // Check if the page contains the expected text

        $this->assertSelectorTextContains('h1', 'Directeur de Projet & Architecte SI');
        $this->assertSelectorTextContains('h2', 'Connect with Google');
        $this->assertSelectorExists('a[href="/connect/google"]');
        $this->assertSelectorExists('form[action="/connect/google"]');
        $this->assertSelectorExists('input[name="email"]');
        $this->assertSelectorExists('input[name="password"]');
        $this->assertSelectorExists('button[type="submit"]');
        $this->assertSelectorExists('a[href="/login"]');
        $this->assertSelectorExists('a[href="/register"]');
        $this->assertSelectorExists('a[href="/connect/google"]');
    }

    public function testGoogleRedirect(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/connect/check/google');

        // Check if the response is successful after redirect
        $this->assertResponseIsSuccessful();
        
        // Check if the page contains the expected text after redirect
        $this->assertSelectorTextContains('h1', 'Directeur de Projet & Architecte SI');
    }
}