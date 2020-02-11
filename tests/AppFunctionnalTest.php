<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppFunctionnalTest extends WebTestCase
{
    private $client = null;

  /*  protected function setup(): void
    {
        $this->client = static::createClient();
    }*/





    public function testH1Home()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertSelectorTextContains('h2', 'SnowTricks');
    }



    public function testFailedConnectionPageIsRestricted()
    {
        $client = static::createClient();
        $client->followRedirects(false);
        $client->request('GET', '/trick/new');


        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }



 /*   public function testRedirectToLogin()
    {
        //$this->client->followRedirects();
        $this->client->request('GET', '/trick/new');
        $this->assertResponseRedirects('/login');
    }*/

  /*  public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/user/profile');
        $this->assertResponseRedirects('/login');
    }*/

    /*public function urlProvider2()
    {
        //yield ['/trick/new'];
        yield ['/admin'];
        //yield ['/user/profile'];
        //yield ['/user/inscription'];
    }*/

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function urlProvider()
    {
        //yield ['/test/test'];
        yield ['/'];
        yield ['/trick/2'];
        yield ['/login'];
        //yield ['/2'];
        //yield ['/edit/images/{id}'];
     /*   yield ['/edit/images/{id}/delete'];

        yield ['/edit/images/{id}'];
        yield ['/trick/{id}/{pageComment}'];
        yield['/trick/{id}/comment/new'];
        yield ['/admin/delete_orphan'];

        yield ['/logout'];



        yield ['/edit/trick/{id}'];
        yield ['/edit/trick/{id}/delete'];



        yield ['/user/confirm_email/{token}'];
        yield ['/user/password_reset/{token}'];*/


    }
}