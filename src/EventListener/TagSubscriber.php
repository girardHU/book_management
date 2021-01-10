<?php

namespace App\EventListener;

use App\Entity\Tag;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TagSubscriber implements \Doctrine\Common\EventSubscriber
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * Cette fonction se declenche juste avant l'insertion
     * d'un Tag dans la DB.
     * @param LifcycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->slugify($args);
    }

    /**
     * Retourne une version slugified de la string $toSlugify
     * @param string $toSlugify
     */
    public function slugify(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Tag) {
            return;
        }

        $slug = $this->slugger->slug($entity->getAlias());

        $entity->setSlug($slug->lower());
    }
}