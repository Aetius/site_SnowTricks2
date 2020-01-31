<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppFunctionnalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

       $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }


    public function testFailedConnectionPageIsRestricted()
    {
        $client = static::createClient();
        $client->request('GET', "/user/profile");

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

  /*  public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/user/profile');
        $this->assertResponseRedirects('/login');
    }*/

    public function urlProvider2()
    {
        yield ['/edit/trick/new'];
        yield ['/admin'];
        yield ['/user/profile'];
        yield ['/user/inscription'];
    }

    public function urlProvider()
    {
        yield ['/test/test'];
        yield ['/'];
        yield ['/trick/22'];
        //yield ['/edit/images/{id}'];
     /*   yield ['/edit/images/{id}/delete'];

        yield ['/edit/images/{id}'];
        yield ['/trick/{id}/{pageComment}'];
        yield['/trick/{id}/comment/new'];
        yield ['/admin/delete_orphan'];
        yield ['/login'];
        yield ['/logout'];
        yield ['/{id}'];


        yield ['/edit/trick/{id}'];
        yield ['/edit/trick/{id}/delete'];



        yield ['/user/confirm_email/{token}'];
        yield ['/user/password_reset/{token}'];*/


    }
}