<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppFunctionnalTest extends WebTestCase
{
    /**
     * @dataProvider urlProviderRoleUser
     */
    public function testFailedConnectionPageIsRestricted($url)
    {
        $client = static::createClient();
        //$client->followRedirects(false);
        $crawler = $client->request('GET', $url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider urlProviderRoleUser
     */
    public function testRedirectionToLogin($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
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

    /**
     * @dataProvider urlProviderAccessPublicPage
     */
    public function testAccessPublicPage($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);
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


    public function testPermanentRedirection()
    {
        $client = static::createClient();
        $client->request('GET', '/logout');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider urlProviderAjaxRoleUser
     */
    public function testAjaxRequestWithNoCredentials($url)
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', $url);
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
    }


    public function urlProviderAjaxRoleUser()
    {
        yield ['/video/edit/5'];
    }

    /**
     * @dataProvider urlProviderAjax
     */
    public function testAjaxRequest($url)
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function urlProviderAjax()
    {
        yield ['/page/2'];
        yield ['/trick/3/page_comment/2'];
    }

}