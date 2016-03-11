<?php

namespace AppBundle\Command\Cron;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Image;
use AppBundle\Importer\CustomFileInfo;

class ImageImportCommand extends ContainerAwareCommand
{
    /**
     * image import
     */
    public function configure()
    {
        $this
            ->setName('app:image:import')
            ->setDescription('Import all image');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $uploadable = $this->getContainer()->get('stof_doctrine_extensions.listener.uploadable');

        $image = new Image();

        $uploadable->addEntityFileInfo($image, new CustomFileInfo('http://media.bigshinyrobot.com/uploads/2014/04/captain_planet.jpg'));

        $manager->persist($image);
        $manager->flush();
    }
}
