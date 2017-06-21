<?php

namespace BlogBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ManagerRegistry;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Category;
use BlogBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DefaultController extends Controller
{


    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM BlogBundle:Post a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $em = $this->get('doctrine')->getManager();
        $rubriques = $em->getRepository('BlogBundle:Category')
            ->findAll();

        return $this->render('BlogBundle:Default:index.html.twig', array(
                'posts' => $posts,
                'rubriques' => $rubriques
            )
        );
    }


    /**
     * @Route("/categories/{name_cat}", name="category_info")
     */
    public function categoryInfoAction(Request $request, $name_cat)
    {


        $em = $this->get('doctrine')->getManager();
        $rubriques = $em->getRepository('BlogBundle:Category')
            ->findAll();

        $em = $this->get('doctrine')->getManager();
        $category = $em->getRepository('BlogBundle:Category')
            ->findBy(array('name' => $name_cat));

        $idCat = $category[0]->getId();


        $em = $this->get('doctrine.orm.entity_manager');
        //recuperer les post de LA CATEGORIES dans l'ordre decroissant
        $dql = "SELECT p.id, p.title as post_title, p.created as post_date_creation, c.id as category_id, c.name as category_name FROM BlogBundle:Post p JOIN p.categories c WHERE c.id ='" . $idCat ."'";

       // $dql = "SELECT c.id, p.id as post_id, p.title as post_title, p.content as post_content FROM BlogBundle:Category c JOIN c.posts p";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $postOfRubriques = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('BlogBundle:Default:category_info.html.twig', array(
                'postOfRubriques' => $postOfRubriques,
                'rubriques' => $rubriques,
                'category' => $category
            )
        );
    }




    /**
     * @Route("/post/{id}", name="post_info")
     */
    public function postInfoAction(Request $request, $id)
    {

        $em = $this->get('doctrine')->getManager();
        $rubriques = $em->getRepository('BlogBundle:Category')
            ->findAll();

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($currentUser != "anon.") {
            $currentUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        } else {
            $currentUser = "";
        }


        $em = $this->get('doctrine')->getManager();
        $post = $em->getRepository('BlogBundle:Post')
            ->find($id);

        return $this->render('BlogBundle:Default:post_info.html.twig', array(
                'posts' => $post,
                'userId' => $currentUser,
                'rubriques' => $rubriques
            )
        );
    }

    /**
     * @Route("/post/{id}/add/comment", name="post_comment")
     */
    public function postAddCommentAction(Request $request, $id)
    {

        $em = $this->get('doctrine')->getManager();
        $rubriques = $em->getRepository('BlogBundle:Category')
            ->findAll();

        $em = $this->get('doctrine')->getManager();
        $post = $em->getRepository('BlogBundle:Post')
            ->find($id);


        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($currentUser != "anon.") {
            $currentUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        } else {
            $currentUser = "";
        }

        $setUser = $this->container->get('security.token_storage')->getToken()->getUser();

        $comment = new Comment();
        $comment->setCommentator($setUser);
        $comment->setPost($post);
        //date mis auto

        $form = $this->createFormBuilder($comment)
            ->add('content', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Add comment'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $comment = $form->getData();

            $em = $this->get('doctrine')->getManager();

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_info', array(
                'id' => $id
            ));
        }

        return $this->render('BlogBundle:Default:post_new_comment.html.twig', array(
                'form' => $form->createView(),
                'rubriques' => $rubriques
            )
        );
    }


    /**
     * @Route("/post/{id}/edit/comment/{id_comment}", name="post_comment_edit")
     */
    public function postEditCommentAction(Request $request, $id, $id_comment)
    {

        $em = $this->get('doctrine')->getManager();
        $rubriques = $em->getRepository('BlogBundle:Category')
            ->findAll();

        $em = $this->get('doctrine')->getManager();
        $post = $em->getRepository('BlogBundle:Post')
            ->find($id);

        $em = $this->get('doctrine')->getManager();
        $commentToEdit = $em->getRepository('BlogBundle:Comment')
            ->find($id_comment);


        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($currentUser != "anon.") {
            $currentUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        } else {
            $currentUser = "";
        }


        $setUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $comment = new Comment();
        $comment->setCommentator($setUser);
        $comment->setPost($post);
        //date mis auto

        $form = $this->createFormBuilder($comment)
            ->add('content', TextType::class, array(
                'attr' => array(
                    'placeholder' => $commentToEdit->getContent(),
                )))
            ->add('save', SubmitType::class, array('label' => 'Change comment'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->get('doctrine')->getManager();
            $rubriques = $em->getRepository('BlogBundle:Category')
                ->findAll();

            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $comment = $form->getData();

            $em = $this->get('doctrine')->getManager();
            $commentToEdit->setContent($comment->getContent());
            $em->flush();

            return $this->redirectToRoute('post_info', array(
                'id' => $id,
                'rubriques' => $rubriques
            ));
        }

        return $this->render('BlogBundle:Default:post_edit_comment.html.twig', array(
                'form' => $form->createView(),
                'userId' => $currentUser,
                'rubriques' => $rubriques
            )
        );
    }

    /**
     * @Route("/post/{id}/delete/comment/{id_comment}", name="post_comment_delete")
     */
    public function postDeleteCommentActio(Request $request, $id, $id_comment)
    {

        $em = $this->get('doctrine')->getManager();
        $rubriques = $em->getRepository('BlogBundle:Category')
            ->findAll();

        $em = $this->get('doctrine')->getManager();
        $post = $em->getRepository('BlogBundle:Post')
            ->find($id);

        $commentToDelete = $em->getRepository('BlogBundle:Comment')
            ->find($id_comment);

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($currentUser != "anon.") {
            $currentUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        } else {
            $currentUser = "";
        }


        $em->remove($commentToDelete);
        $em->flush();


        return $this->render('BlogBundle:Default:post_info.html.twig', array(
                'posts' => $post,
                'userId' => $currentUser,
                'rubriques' => $rubriques
            )
        );

    }


    /**
     * @Route("/{slug}", name="slug_path")
     */
    public function slutAction(Request $request, $slug)
    {

        $em = $this->get('doctrine')->getManager();
        $rubriques = $em->getRepository('BlogBundle:Category')
            ->findAll();


        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('BlogBundle:Category')
            ->findBy(array('slug' => $slug));

        $em = $this->get('doctrine')->getManager();
        $posts = $em->getRepository('BlogBundle:Post')
            ->findBy(array('slug' => $slug));

        if (count($categories) == 0 && count($posts) > 0) {
            //return post
            return $this->render('BlogBundle:Default:slug.html.twig', array(
                    'posts' => $posts,
                    'rubriques' => $rubriques
                )
            );
        } else if (count($categories) > 0 && count($posts) == 0) {
            //return categories

            $em = $this->get('doctrine.orm.entity_manager');
            $dql = "SELECT a FROM BlogBundle:Post a";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $allPost = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
            );


            return $this->render('BlogBundle:Default:slug.html.twig', array(
                    'categories' => $categories,
                    'allPosts' => $allPost,
                    'rubriques' => $rubriques

                )
            );
        } else if (count($categories) == 0 && count($posts) == 0) {
            // return error
            $errorMsg = "Aucun résultats trouvé !";
            return $this->render('BlogBundle:Default:slug.html.twig', array(
                    'error' => $errorMsg,
                    'rubriques' => $rubriques

                )
            );
        } else {
            //return post par défaut (post > categorie)
            return $this->render('BlogBundle:Default:slug.html.twig', array(
                    'posts' => $posts,
                    'rubriques' => $rubriques

                )
            );
        }

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


    // MENU SECU COMMENTAIRE FOR USER sur toutes les routes slug etc + AJOUT LIEN A CHAQUE FOIS QU IL A LARTICLE SUR LA ROUTE /post/id pour voir les infos
    //Connexion / login etc dans le mmenu + champs de recherche pour les slug


    //TO DO : ACL ROLE / securisation route + dynamique twig en fonction des granted
    //MENU => categorie en form de lien + champ recherche pour le slug
    // ATTRIBUTION A UN USER LORS DE LA CREATION DUN NOUVEAU POST (le post doit avoir un author et le faire apparaitre sur les pages index et post_info)

}
