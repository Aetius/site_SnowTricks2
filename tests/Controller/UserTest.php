<?php


namespace App\Tests\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserTest extends WebTestCase
{
    use NeedLogin;

    const ROLE_USER = 'sim0';
    const ROLE_ADMIN = 'sim1';
    const ROLE_EDITOR = 'sim2';


    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
    }


    public function testLoginSubmitForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'login'=>'sim1',
            'password'=>'demo'
        ]);
       $this->client->submit($form);

        $this->assertResponseRedirects('/');
    }

    public function testLoginSubmitFailedForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'login'=>'sim1',
            'password'=>'falsePassword'
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }



    public function testAdministratorAccessAdminOk()
    {
       $this->Login($this->client, self::ROLE_ADMIN);

        $crawler = $this->client->request('GET', '/admin');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAdministratorAccessAdminNok()
    {
        $this->Login($this->client, self::ROLE_EDITOR);

        $crawler = $this->client->request('GET', '/admin');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }







}