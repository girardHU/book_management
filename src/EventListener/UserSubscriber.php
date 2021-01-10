<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Events;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements \Doctrine\Common\EventSubscriber
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * Cette fonction se declenche juste avant l'insertion
     * d'un element dans la DB.
     * @param LifcycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->hashPassword($args);
    }

    /**
     * Permet de chiffrer un password avant l'insertion
     * d'un User
     * @param LifcycleEventArgs $args
     */
    public function hashPassword(LifecycleEventArgs $args)
    {
        # 1. Recuperation de l'objet concerne
        $entity = $args->getObject();

        # 2. Si objet n'est pas un User on quitte
        if (!$entity instanceof User) {
            return;
        }

        # 3. Sinon on hash le password
        $entity->setPassword(
            $this->encoder->encodePassword(
                $entity,
                $entity->getPassword()
            )
        );
    }
}