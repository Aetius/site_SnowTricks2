<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{
    use NeedLogin;

    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
    }


    /**
     *@dataProvider targetProviderHomePage
     */
    public function testHomePage($target)
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink($target);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     *@dataProvider targetProviderHomePageUserAccess
     */
    public function testHomePageWithRoleUser($url)
    {
        $this->Login($this->client, UserTest::ROLE_USER);
        $this->client->request('GET', '/');
        $this->client->followRedirects(false);
       ($this->client->clickLink($url));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     *@dataProvider urlProviderAjax
     */
    public function testAjaxRequest($url)
    {
        $this->client->xmlHttpRequest('GET', $url);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    public function targetProviderHomePage()
    {
        yield ['Les tricks'];
        yield ['Accueil'];
        yield ["S'identifier"];
        //yield ['Créer un compte'];
        yield ['Vulcan 18'];
    }

    public function targetProviderHomePageUserAccess()
    {
        yield ['Mon espace'];
        yield ['Se déconnecter'];
    }
    public function targetProviderHomePageEditorAccess()
    {
        yield ['edit'];
        yield ['delete'];
    }

    public function targetProviderHomePageAdminAccess()
    {
        yield ['Espace Admin'];

    }

    public function urlProviderAjax()
    {
        yield ['http://localhost/page/2'];
    }



}