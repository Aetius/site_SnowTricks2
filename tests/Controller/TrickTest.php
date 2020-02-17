<?php


namespace App\Tests\Controller;


use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TrickTest extends WebTestCase
{
    use NeedLogin;
    use TrickRepository;

    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testHome()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     *@dataProvider targetProviderHomePage
     */
    public function testTargetHomePage($target)
    {
        $this->client->request('GET', '/');
        $this->client->clickLink($target);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     *@dataProvider targetProviderHomePageUserAccess
     */
    public function testTargetHomePageWithRoleUser($target)
    {
        $this->Login($this->client, UserTest::ROLE_USER);
        $this->client->request('GET', '/');
        $this->client->followRedirects(false);
        $this->client->clickLink($target);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    /**
     *@dataProvider targetProviderHomePageEditorAccess
     */
    public function testTargetHomePageWithRoleEditor($target)
    {
        $this->Login($this->client, UserTest::ROLE_EDITOR);
        $this->client->request('GET', '/');
        //$this->client->followRedirects(false);
        $this->client->clickLink($target);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     *@dataProvider targetProviderHomePageAdminAccess
     */
    public function testTargetHomePageWithRoleAdmin($target)
    {
        $this->Login($this->client, UserTest::ROLE_ADMIN);
        $this->client->request('GET', '/');
        $this->client->followRedirects(false);
        $this->client->clickLink($target);
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

    /**
     * @dataProvider targetProviderRedirectHomeUserAccess
     */
    public function testRedirectionHomePageWithRoleUser($target)
    {
        $this->Login($this->client, UserTest::ROLE_USER);
        $this->client->request('GET', '/');
        $this->client->followRedirects(false);
        $this->client->clickLink($target);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider targetProviderRedirectHomeUserAccess
     * @dataProvider targetProviderHomePageUserAccess
     * @dataProvider targetProviderHomePageEditorAccess
     * @dataProvider targetProviderHomePageAdminAccess
     */
    public function testAccessTargetNotAllowed($target)
    {
        $this->client->request('GET', $target);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }


//New trick
    public function testFormNewTrickOK()
    {
        $title = $this->defineTitle($this->client);
        $this->Login($this->client, UserTest::ROLE_EDITOR);
        $crawler = $this->client->request('GET', '/trick/new');
        $button = $crawler->selectButton('Sauvegarder');

        $form = $button->form();
        $form['create[trickGroup]']->select('2');
        $form['create[title]']= $title;
        $form['create[description]']= ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer bibendum id 
        dolor ut viverra. ';
         $form['create[videos][required]']='https://www.youtube.com/embed/V9xuy-rVj9w';
         $form[ 'create[pictureFiles][0]']=__DIR__."/images/montagne.jpg";

        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(
            $crawler = $this->client->getResponse()->isRedirect('/')
        );
    }

    public function testFormNewTrickNonOkTitleAlreadyUsed()
    {
        $title = $this->findLastTrick($this->client)->getTitle();
        $this->Login($this->client, UserTest::ROLE_EDITOR);
        $crawler = $this->client->request('GET', '/trick/new');
        $button = $crawler->selectButton('Sauvegarder');

        $form = $button->form();
        $form->disableValidation();
        $form['create[trickGroup]']->select('Invalid value');
        $form['create[title]']= $title;
        $form['create[description]']= " ";
        $form['create[videos][required]']='https://www.you3tube.com/embed/V9xuy-rVj9w';
        $form[ 'create[pictureFiles][0]']->upload(' ');

        $crawler = $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan( //title
            0,
            $crawler->filter('html:contains("Ce nom est déjà utilisé.")')->count(),
            $crawler->filter('html:contains("Cette chaîne est trop courte. Elle doit avoir au minimum 5 caractères.")')->count()
        );
        $this->assertGreaterThan( //description
            0,
            $crawler->filter('html:contains("Cette valeur ne doit pas être nulle.")')->count(),
            $crawler->filter('html:contains("Cette chaîne est trop courte. Elle doit avoir au minimum 10 caractères.")')->count()
        );
        $this->assertEquals( //video
            1,
            $crawler->filter('html:contains("La vidéo doit être une url provenant de Youtube/Dailymotion. ")')->count()
        );
        $this->assertGreaterThan( //image
            0,
            $crawler->filter('html:contains("The value "image" is not valid.")')->count()
            );
    }


//Delete Trick
    public function testDeleteTrickOk()
    {

        self::bootKernel();
        $token = self::$container->get(CsrfTokenManagerInterface::class)->getToken('delete_1')->getValue();
        /*$title = $this->findLastTrick($this->client);
        $this->Login($this->client, UserTest::ROLE_EDITOR);
         $crawler = $this->client->request('GET', '/');

        //$crawler =$this->client->clickLink('delete');
        dd($crawler->filter('.delete_30'));
        dd($crawler->filter('body > delete_44 '));*/

        //$this->execu










/*


        //$linkForm = $crawler->selectLink('delete')->link();
        dd($this->client->clickLink('delete')->siblings());
        dd($crawler->selectButton('Supprimer'));
        ($test = $linkForm->getNode()->parentNode->getElementsByTagName('input'));
       dd($test->item(0));
        $button = $crawler->selectButton('Supprimer');
        $form = $button->form(); dd($form);
        $this->client->submit($form);*/

       /* $this->client->clickLink('delete');


        $test = $this->client->submitForm('Supprimer');
*/

        //$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }











//Edit Page




//home page
    public function targetProviderHomePage()
    {
        yield ['Les tricks'];
        yield ['Accueil'];
        yield ["S'identifier"];
        yield ['Créer un compte'];
        yield ['Vulcan 18'];
    }

    public function targetProviderRedirectHomeUserAccess()
    {
        yield ['Se déconnecter'];
    }

    public function targetProviderHomePageUserAccess()
    {
        yield ['Mon espace'];

    }
    public function targetProviderHomePageEditorAccess()
    {
        yield ['Nouveau trick'];
        yield ['edit'];
        yield ['delete'];
    }

    public function targetProviderHomePageAdminAccess()
    {
        yield ['Espace admin'];

    }

    public function urlProviderAjax()
    {
        yield ['http://localhost/page/2'];
    }



}