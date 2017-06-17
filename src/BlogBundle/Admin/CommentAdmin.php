<?php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper->add('commentator');
        $formMapper->add('content');
        $formMapper->add('post');


    }

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
