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

    /**
     * @Route("/categories/show", name="categories_show")
     */
    public function categoriesAction(Request $request)
    {

        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('BlogBundle:Category')
            ->findAll();


        return $this->render('BlogBundle:Default:categories.html.twig', array(
                'categories' => $categories,
            )
        );
    }


    /**
     * @Route("/{slug}", name="slug_path")
     */
    public function slutAction(Request $request, $slug)
    {




        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('BlogBundle:Category')
            ->findAll();

        $em = $this->get('doctrine')->getManager();
        $posts = $em->getRepository('BlogBundle:Post')
            ->findAll();
            var_dump($slug);
        return $this->render('BlogBundle:Default:categories.html.twig', array(
                'categories' => $categories,
            )
        );
    }


    //LA ROUTE /{slug} bien la mettre a la fin sinon les autres routes ne match pas
    //article et catégorie sont sur /slug donc verifier a chaque fois si en base c'est une catégorie OU un article
    // refaire les donnée en base car maintenant elle devront integrer un slug !
    // ajouter la slug sur la création d'un post
    // AU MOMENT DU CALL DU SLUG si on affiche la catégorie c'est uniquement LES 5 premiers avec pagination
    // pour le slug faire une condition return soit categorie soit une catégorie (en principe)
    // CREATION d'un post susr sonata get Current user aussi
    //faire la pagination sur l'url catégorie puis afficher les 5 premiers articles de la catégories
    // crer des roles selon les consignes
    // securiser TOUTES les routes
    // MENU -> creer une reubrique pour chaque categorie


    /*
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
    */







}
