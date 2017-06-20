<?php

namespace BlogBundle\Admin;

use BlogBundle\EventListener\CommentListener;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use FOS\UserBundle\Model\UserManagerInterface;




class CommentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper->add('commentator');
        $formMapper->add('content');
        $formMapper->add('post');


    }




    //postPersist
        //$object = entityCOmment
    //public function prePersist($object) {

        /*
         * Get user id for setCommentator
        $container = $this->getConfigurationPool()->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $object->setCommentator();
        $entityManager->flush();
        */
    //}




    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $datagridMapper->add('post');
        $datagridMapper->add('commentator');
        $datagridMapper->add('created');

    }

    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper->addIdentifier('commentator');
        $listMapper->add('post');
        $listMapper->add('created');
    }


}
