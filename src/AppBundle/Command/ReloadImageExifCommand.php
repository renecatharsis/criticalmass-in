<?php

namespace AppBundle\Command;

use AppBundle\Entity\Photo;
use AppBundle\Image\ExifReader\DateTimeExifReader;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReloadImageExifCommand extends ContainerAwareCommand
{
    /**
     * @var Registry $doctrine
     */
    protected $doctrine;

    /**
     * @var EntityManager $manager
     */
    protected $manager;

    protected function configure()
    {
        $this
            ->setName('criticalmass:images:reloadExif')
            ->setDescription('Regenerate LatLng Tracks');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->doctrine = $this->getContainer()->get('doctrine');
        $this->manager = $this->doctrine->getManager();

        $photos = $this->doctrine->getRepository('AppBundle:Photo')->findAll();

        /**
         * @var DateTimeExifReader $dter
         */
        $dter = $this->getContainer()->get('caldera.criticalmass.image.exifreader.datetime');

        /**
         * @var Photo $photo
         */
        foreach ($photos as $photo) {
            $dateTime = $dter
                ->setPhoto($photo)
                ->execute()
                ->getDateTime();

            $photo->setDateTime($dateTime);

            $this->manager->merge($photo);
        }

        $this->manager->flush();
    }
}