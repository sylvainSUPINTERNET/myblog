<?php

namespace BlogBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ManagerRegistry;
use BlogBundle\Entity\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('BlogBundle:Default:index.html.twig');
    }


    /**
     * @Route("/post/list", name="post_list")
     */
    public function postAction()
    {

        $em = $this->get('doctrine')->getManager();
        $posts = $em->getRepository('BlogBundle:Post')
            ->findAll();

        return $this->render('BlogBundle:Default:post.html.twig', array(
                'posts' => $posts
            )
        );
    }
}
