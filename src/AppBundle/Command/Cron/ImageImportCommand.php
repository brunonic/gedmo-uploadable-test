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

    /**
     * @param string $image
     * @param string $newPath
     * @param int    $imageWidth
     * @param int    $imageHeight
     * @param int    $maxWidth
     * @param int    $maxHeight
     * @param string $imgType
     *
     * @return resource $newImg
     */
    public function createSquare($image, $newPath, $imageWidth, $imageHeight, $maxWidth, $maxHeight, $imgType)
    {
        $destX = ($maxWidth - $imageWidth) / 2;
        $destY = ($maxHeight - $imageHeight) / 2;

        $color = 'white';
        if ($imgType == IMAGETYPE_PNG or $imgType == IMAGETYPE_GIF) {
            $color = 'transparent';
        }

        try {
            $imaGick = new \Imagick($image);
        } catch (\Exception $e) {
            return false;
        }

        $imaGick = $imaGick->coalesceImages();


        if ($imageHeight < 1) {
            $imageHeight = 1;
        }

        if ($imageWidth < 1) {
            $imageWidth = 1;
        }

        do {
            $imaGick->setImageCompressionQuality(100);
            $imaGick->thumbnailImage($imageWidth, $imageHeight);
            $imaGick->setImageBackgroundColor(new \ImagickPixel($color));
            $imaGick->extentimage($maxWidth, $maxHeight, -$destX, -$destY);
        } while ($imaGick->nextImage());

        $imaGick->writeimages($newPath, true);

        return true;
    }
}
