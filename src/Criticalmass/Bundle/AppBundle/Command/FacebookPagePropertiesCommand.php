<?php

namespace Criticalmass\Bundle\AppBundle\Command;

use Criticalmass\Bundle\AppBundle\Entity\City;
use Criticalmass\Bundle\AppBundle\Facebook\FacebookPageApi;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FacebookPagePropertiesCommand extends ContainerAwareCommand
{
    /**
     * @var Facebook $facebook
     */
    protected $facebook;

    protected function configure()
    {
        $this
            ->setName('criticalmass:facebook:pageproperties')
            ->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->doctrine = $this->getContainer()->get('doctrine');
        $this->manager = $this->doctrine->getManager();

        /**
         * @var FacebookPageApi $fpa
         */
        $fpa = $this->getContainer()->get('caldera.criticalmass.facebookapi.citypageproperties');

        $cities = $this->doctrine->getRepository('AppBundle:City')->findCitiesWithFacebook();

        /**
         * @var City $city
         */
        foreach ($cities as $city) {
            $output->writeln('Looking up ' . $city->getCity());

            $pageId = $this->getPageId($city);

            if ($pageId) {
                $output->writeln('Page ID is: ' . $pageId);

                $properties = $fpa->getPagePropertiesForCity($city);

                if ($properties) {
                    $this->manager->persist($properties);

                    $output->writeln('Saved properties');
                    $output->writeln('');
                }
            }
        }

        $this->manager->flush();
    }

    protected function getPageId(City $city)
    {
        $facebook = $city->getFacebook();

        if (strpos($facebook, 'https://www.facebook.com/') == 0) {
            $facebook = rtrim($facebook, "/");

            $parts = explode('/', $facebook);

            $pageId = array_pop($parts);

            return $pageId;
        }

        return null;
    }

}
