<?php


namespace App\Tests\Controller\User;


use App\Entity\User;
use App\Tests\Controller\NeedLogin;
use App\Tests\Repository\UserRepositoryTest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends WebTestCase
{
    use NeedLogin;
    use UserRepositoryTest;

    const ROLE_USER = 'sim 0';
    const ROLE_ADMIN = 'sim 1';
    const ROLE_EDITOR = 'sim 2';


    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
    }



    public function testAdministratorAccessAdminOk()
    {
       $this->Login($this->client, self::ROLE_ADMIN);

        $this->client->request('GET', '/admin');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAdministratorAccessAdminNok()
    {
        $this->Login($this->client, self::ROLE_EDITOR);

        $this->client->request('GET', '/admin');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }


    public function testLostPasswordOK()
    {
        $crawler = $this->client->request('GET', '/password_lost');
        $button = $crawler->selectButton('Réinitialiser');
        $form = $button->form();
        $form['lost_password[login]']= self::ROLE_USER;
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testRegistrationOk()
    {
        $userLogin = $this->defineUser($this->client);
        $crawler = $this->client->request('GET', '/user/inscription');
        $button = $crawler->selectButton('Sauvegarder');
        $form = $button->form();
        $form['registration_user[login]']= $userLogin;
        $form['registration_user[emailUser]']= "demo@yahoo.fr";
        $form['registration_user[picture]']= __DIR__."/images/montagne.jpg";
        $form['registration_user[password][first]']= "demo";
        $form['registration_user[password][second]']= "demo";
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testRegistrationNok()
    {
        $userLogin = $this->findLastUser($this->client)->getLogin();
        $crawler = $this->client->request('GET', '/user/inscription');
        $button = $crawler->selectButton('Sauvegarder');
        $form = $button->form();
        $form->disableValidation();
        $form['registration_user[login]']= $userLogin;
        $form['registration_user[emailUser]']= "demo.yahoo.fr";
        $form['registration_user[picture]']->upload('');
        $form['registration_user[password][first]']= "demo";
        $form['registration_user[password][second]']= "do";
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertGreaterThan( //Login
            0,
            $crawler->filter('html:contains("Ce login existe déjà.")')->count()
        );
        $this->assertGreaterThan( //emailUser
            0,
            $crawler->filter('html:contains("Cette valeur n\'est pas une adresse email valide.")')->count()
        );
        $this->assertGreaterThan( //Image
            0,
            $crawler->filter('html:contains("Cette valeur ne doit pas être vide.")')->count()
        );
        $this->assertGreaterThan( //password
            0,
            $crawler->filter('html:contains("La confirmation du mot de passe est incorrecte")')->count()
        );
    }




}