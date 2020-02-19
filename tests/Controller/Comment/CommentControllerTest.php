<?php


namespace App\Tests\Controller\Comment;


use App\Tests\Controller\NeedLogin;
use App\Tests\Controller\User\UserControllerTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{
    use NeedLogin;

    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCommentCreate()
    {
        $this->Login($this->client, UserControllerTest::ROLE_EDITOR);
        $crawler = $this->client->request('GET', '/trick/19');
        $button = $crawler->selectButton('Valider le commentaire');

        $form = $button->form();
        $form['create[content]']= 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer bibendum id 
        dolor ut viverra. ';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testCommentCreateNok()
    {
        $this->Login($this->client, UserControllerTest::ROLE_EDITOR);
        $crawler = $this->client->request('GET', '/trick/19');
        $button = $crawler->selectButton('Valider le commentaire');
        $form = $button->form();
        $form->disableValidation();
        $form['create[content]']= '';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->request('GET', '/trick/19');
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Echec lors de l\'enregistrement du commentaire.")')->count()
        );
    }
}