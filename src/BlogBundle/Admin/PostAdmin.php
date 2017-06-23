<?php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('author');
        $formMapper->add('title');
        $formMapper->add('description');
        $formMapper->add('content');
        $formMapper->add('categories');


    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('author');
        $datagridMapper->add('title');
        $datagridMapper->add('created');
        $datagridMapper->add('description');
        $datagridMapper->add('categories');


    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
        $listMapper->addIdentifier('author');
        $listMapper->add('created');
        $listMapper->add('description');
        $listMapper->add('categories');
        $listMapper->add('comment');
    }
}
