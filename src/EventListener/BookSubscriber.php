<?php

namespace App\EventListener;

use App\Entity\Book;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class BookSubscriber implements \Doctrine\Common\EventSubscriber
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(MailerInterface $mailer, SluggerInterface $slugger)
    {
        $this->mailer = $mailer;
        $this->slugger = $slugger;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist
        ];
    }

    /**
     * Cette fonction se declenche juste avant l'insertion
     * d'un Book dans la DB.
     * @param LifcycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->slugify($args);
    }

    /**
     * Cette fonction se declenche juste avant l'insertion
     * d'un element dans la DB.
     * @param LifcycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->sendNewBookMail($args);
    }

    /**
     * Retourne une version slugified de la string $toSlugify
     * @param string $toSlugify
     */
    public function slugify(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Book) {
            return;
        }

        $slug = $this->slugger->slug($entity->getTitle());

        $entity->setSlug($slug->lower());
    }

    public function sendNewBookMail(LifecycleEventArgs $args)
    {

        $entity = $args->getObject();

        if (!$entity instanceof Book) {
            return;
        }

        $email = (new Email())
        ->from('bookmanagement@noreply.com')
        ->to('marketing@eshop.com')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Nouveau Livre ajoute !')
        ->text('Le livre ' . $entity->getTitle() . ' a ete ajoute')
        ->html('<p>Le livre' . $entity->getTitle() . ' a ete ajoute</p>');

        $this->mailer->send($email);

    }
}