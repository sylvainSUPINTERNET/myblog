<?php

namespace BlogBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\Event\LifecycleEventArgs;
use BlogBundle\Entity\Comment;
use Application\Sonata\UserBundle\Entity\User;


class CommentListener
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /*
    public function prePost(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Comment) {
            return;
        }

        $userId = $this->tokenStorage->getToken()->getUser()->getId();


        $em = $args->getEntityManager();
        $userToInsert = $em->getRepository('Application\Sonata\UserBundle\Entity\User')->find($userId);
        //$addNewCommentator = $em->getRepository('BlogBundle:Comment')->find(28);
        $addNewCommentator = $em->getRepository('BlogBundle:Comment')->findOneBy(array(), array("id" => "DESC"));


        $addNewCommentator->setCommentator($userToInsert);
        //setCommentator($userToInsert);
        $em->flush();

    }
   */
    public function getCurrentUser()
    {
        return $this->tokenStorage;
    }
}