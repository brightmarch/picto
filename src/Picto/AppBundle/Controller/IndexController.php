<?php

namespace Picto\AppBundle\Controller;

use Picto\AppBundle\Controller\Mixin\GetterMixin;
use Picto\AppBundle\Form\Type\ComputerUploadType;
use Picto\AppBundle\Form\Type\UrlUploadType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{

    use GetterMixin;

    public function indexAction(Request $request)
    {
        $image = $this->getEntityManager()
            ->getRepository('PictoAppBundle:Image')
            ->findOneByHash($request->query->get('hash'));

        $computerUploadForm = $this->createForm(new ComputerUploadType);
        $urlUploadForm = $this->createForm(new UrlUploadType);

        $parameters = [
            'computerUploadForm' => $computerUploadForm->createView(),
            'urlUploadForm' => $urlUploadForm->createView(),
            'image' => $image
        ];

        return $this->render('PictoAppBundle:Index:index.html.twig', $parameters);
    }

}
