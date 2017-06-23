<?php


namespace BlogBundle\EventListener;


use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class UserCreationListener implements EventSubscriberInterface
{
    protected $em;
    protected $user;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $this->user = $event->getForm()->getData();
        $group_name = 'users';
        $entity = $this->em->getRepository('Application\Sonata\UserBundle\Entity\Group')->findOneByName($group_name);
        $this->user->addGroup($entity);
        $this->em->flush();

    }
}
