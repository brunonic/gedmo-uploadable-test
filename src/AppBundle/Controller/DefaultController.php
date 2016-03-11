<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Entity\Image;
use AppBundle\Form\Type\ImageType;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="uploadable")
     * @Template("default/uploadable.html.twig")
     */
    public function indexAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm(new ImageType(), $image);

        $manager = $this->getDoctrine()->getManager();

        if ($form->handleRequest($request)->isValid()) {

            if ($file = $image->getFile()) {
                $this->get('stof_doctrine_extensions.uploadable.manager')->markEntityToUpload($image, $file);
            }

            $manager->persist($image);
            $manager->flush();

            return $this->redirectToRoute('uploadable');
        }

        $images = $manager->getRepository('AppBundle:Image')->findAll();

        return [
            'form' => $form->createView(),
            'images' => $images,
        ];
    }
}
