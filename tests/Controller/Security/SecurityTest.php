<?php


namespace App\Tests\Controller\Security;


use App\Tests\Controller\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SecurityTest extends WebTestCase
{
    use NeedLogin;

    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }


    public function testLoginSubmitForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'login' => 'sim1',
            'password' => 'demo'
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects();
    }

    public function testLoginSubmitFailedForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'login' => 'sim1',
            'password' => 'falsePassword'
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

}