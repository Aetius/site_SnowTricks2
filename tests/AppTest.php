<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppTest extends WebTestCase
{
    /**
     *@dataProvider urlProviderRoleUser
     */
    public function testFailedConnectionPageIsRestricted($url)
    {
        $client = static::createClient();
        //$client->followRedirects(false);
        $crawler = $client->request('GET', $url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     *@dataProvider urlProviderAccessPublicPage
     */
    public function testAccessPublicPage($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }




    public function testPermanentRedirection()
    {
        $client = static::createClient();
        //$client->followRedirects(false);
        $crawler = $client->request('GET', '/logout');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }


    /**
     *@dataProvider urlProviderRoleUser
     */
    public function testRedirectionToLogin($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/login')
        );
    }

    /**
     *@dataProvider urlProviderAjaxRoleUser
     */
    public function testAjaxRequestWithNoCredentials($url)
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', $url);
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/login')
        );
    }

    /**
     *@dataProvider urlProviderAjax
     */
    public function testAjaxRequest($url)
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }



    public function urlProviderAccessPublicPage()
    {
        yield ['/'];
        yield ['/trick/2'];
        yield ['/login'];
        yield ['trick/4/page_comment/2'];
        yield ['/user/inscription'];
        yield ['/password_lost'];
    }

    public function urlProviderRoleUser()
    {
        yield ['/image/3/delete'];
        yield ['/image/edit/4'];
        yield ['/trick/delete/5'];
        yield ['/profile'];
        yield ['/admin'];
        yield ['/video/delete/5'];
        yield ['/trick/new'];
        yield ['/edit/trick/3'];

    }

    public function urlProviderAjaxRoleUser()
    {
        yield ['http://localhost/video/edit/5'];
    }

    public function urlProviderAjax()
    {
        yield ['http://localhost/page/2'];
        yield ['http://localhost/trick/3/page_comment/2'];
    }

    public function urlProviderPostMethodOnly()
    {
        yield ['/trick/1/comment/new'];
    }



//à définir
    /*public function testH1Page($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);
        $this->assertSelectorExists('h1');
    }*/


    /**
     *@dataProvider urlProviderPostMethodOnly
     */
    /* public function testPostMethodOnly($url)
     {
         $client = static::createClient();
         $client->request('POST', $url);
         $this->assertEquals(200, $client->getResponse()->getStatusCode());
     }*/



//token :  yield ['/confirm_email/{token}'];
//token :  yield ['/password_reset/{token}'];

}