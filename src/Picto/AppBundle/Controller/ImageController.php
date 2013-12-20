<?php

namespace Picto\AppBundle\Controller;

use Picto\AppBundle\Controller\Mixin\GetterMixin;
use Picto\AppBundle\Entity\ComputerUpload;
use Picto\AppBundle\Entity\Image;
use Picto\AppBundle\Entity\UrlUpload;
use Picto\AppBundle\Form\Type\ComputerUploadType;
use Picto\AppBundle\Form\Type\UrlUploadType;
use Picto\AppBundle\Library\Job\UploadJob;
use Picto\AppBundle\Library\Utility;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;
use Symfony\Component\Stopwatch\Stopwatch;

use Intervention\Image\Exception\ImageNotFoundException;

use \RuntimeException;

use \SplFileInfo,
    \Exception;

class ImageController extends Controller
{

    use GetterMixin;

    public function viewAction($hash, Request $request)
    {
        $image = $this->getImage($hash);

        if (!$image) {
            $image = $this->getNotFoundImage();
        } else {
            $image->incrementViewCount();

            $entityManager = $this->getEntityManager();
            $entityManager->persist($image);
            $entityManager->flush();
        }

        $parameters = [
            'image' => $image
        ];

        return $this->render('PictoAppBundle:Image:view.html.twig', $parameters);
    }

    public function viewRawAction($hash, $extension, Request $request)
    {
        $image = $this->getImage($hash);

        if (!$image) {
            $image = $this->getNotFoundImage();
        } else {
            // If the image is being embedded with a noCount flag,
            // then don't count views against it.
            $noCount = $request->query->getInt('noCount');

            if (!$noCount) {
                $image->incrementViewCount();

                $entityManager = $this->getEntityManager();
                $entityManager->persist($image);
                $entityManager->flush();
            }
        }

        // Cache the image if necessary.
        $imageCacher = $this->get('picto_app.image_cacher');
        $imageCacher->cache($image->getHash(), $image);
        $rawImage = $imageCacher->get($image->getHash());

        $headers = [
            'Length' => $image->getFileSize(),
            'Content-Type' => $image->getContentType(),
            'ETag' => $image->getRawHash()
        ];

        $response = new Response($rawImage, 200, $headers);

        return $response;
    }

    public function uploadAction(Request $request)
    {
        $successfulUpload = false;

        $computerUpload = new ComputerUpload;
        $computerUploadForm = $this->createForm(new ComputerUploadType, $computerUpload)
            ->handleRequest($request);

        $urlUpload = new UrlUpload;
        $urlUploadForm = $this->createForm(new UrlUploadType, $urlUpload)
            ->handleRequest($request);

        // Create the initial Image entity. Going to assume
        // here that this image will actually be unique.
        $image = new Image;
        $image->setHash(Utility::getRandomString());

        if ($computerUploadForm->isValid() && $computerUpload->getImage()->isValid()) {
            $sourceImage = $computerUpload->getImage();

            // Save the image to local disk.
            $localImage = $sourceImage->move(
                $this->container->getParameter('image_directory'),
                $sourceImage->getClientOriginalName()
            );

            // Start to populate the Image entity.
            $image->setContentType($sourceImage->getClientMimeType());

            $successfulUpload = true;
        }

        if ($urlUploadForm->isValid()) {
            $fileName = $urlUpload->getBasename();
            $filePath = $this->container->getParameter('image_directory') . $fileName;

            $downloadParameters = [
                'save_to' => $filePath
            ];

            // Attempt to download the file to the local harddrive.
            $httpClient = $this->get('picto_app.http_client');
            $response = $httpClient->get($urlUpload->getUrl(), [], $downloadParameters)
                ->send();

            if ($response->isSuccessful()) {
                $localImage = new SplFileInfo($filePath);

                // Start to populate the Image entity.
                $image->setContentType($response->getContentType());

                $successfulUpload = true;
            }
        }

        if ($successfulUpload) {
            $imageHash = null;

            // Ensure the image is actually an image.
            $rawImage = $this->get('picto_app.image_editor')
                ->open($localImage->getPathname());

            // Populate the image with more data.
            $image->setFileName($localImage->getFilename())
                ->setFileSize($localImage->getSize())
                ->setFileExtension($localImage->getExtension())
                ->setFilePath($localImage->getPathname())
                ->setWidth($rawImage->width)
                ->setHeight($rawImage->height)
                ->setRawHash(sha1_file($image->getFilePath()));

            $existingImage = $this->getEntityManager()
                ->getRepository('PictoAppBundle:Image')
                ->findOneByRawHash($image->getRawHash());

            if (!$existingImage) {
                // Record the amount of memory used up to this point.
                $memoryUsage = (memory_get_peak_usage() / 1024);
                $image->setMemoryUsage($memoryUsage)
                    ->setFilePath($localImage->getPathname());

                $entityManager = $this->getEntityManager();
                $entityManager->persist($image);
                $entityManager->flush();

                // Cache the image in Redis.
                $imageCacher = $this->get('picto_app.image_cacher');
                $imageCacher->cache($image->getHash(), $image);

                // Enqueue a job to upload the image to S3 in the background.
                $uploadJob = new UploadJob;
                $uploadJob->args = [
                    'imageId' => $image->getId()
                ];

                $this->get('bcc_resque.resque')
                    ->enqueue($uploadJob, true);

                // Redirect them back to the index with the new image.
                $imageHash = $image->getHash();
            } else {
                $imageHash = $existingImage->getHash();
            }

            if ($imageHash) {
                $parameters = [
                    'hash' => $imageHash
                ];

                $headers = [
                    'X-Picto-Image-Hash' => $imageHash
                ];

                // Manually construct the response to include the custom header.
                $response = new RedirectResponse($this->generateUrl('picto_app_index', $parameters), 302, $headers);

                return $response;
            }
        }

        $parameters = [
            'computerUploadForm' => $computerUploadForm->createView(),
            'urlUploadForm' => $urlUploadForm->createView(),
            'image' => null
        ];

        return $this->render('PictoAppBundle:Index:index.html.twig', $parameters);
    }

}
