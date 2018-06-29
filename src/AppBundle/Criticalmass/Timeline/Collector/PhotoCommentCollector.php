<?php declare(strict_types=1);

namespace AppBundle\Criticalmass\Timeline\Collector;

use AppBundle\Entity\Post;
use AppBundle\Criticalmass\Timeline\Item\PhotoCommentItem;

class PhotoCommentCollector extends AbstractTimelineCollector
{
    protected $entityClass = Post::class;

    protected function convertGroupedEntities(array $groupedEntities): AbstractTimelineCollector
    {
        /** @var Post $postEntity */
        foreach ($groupedEntities as $postEntity) {
            $item = new PhotoCommentItem();

            $item
                ->setUser($postEntity->getUser())
                ->setRideTitle($postEntity->getPhoto()->getRide()->getFancyTitle())
                ->setPhoto($postEntity->getPhoto())
                ->setText($postEntity->getMessage())
                ->setDateTime($postEntity->getDateTime());

            $this->addItem($item);
        }

        return $this;
    }

    public function getRequiredFeatures(): array
    {
        return ['photos'];
    }
}