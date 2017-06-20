<?php

namespace BlogBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ManagerRegistry;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM BlogBundle:Post a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('BlogBundle:Default:index.html.twig', array(
                'posts' => $posts,
            )
        );
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


    public function getCategoriesAction()
    {

        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('BlogBundle:Category')
            ->findAll();

        return $this->render('BlogBundle:Default:post.html.twig', array(
                'categories' => $categories
            )
        );
    }







}
