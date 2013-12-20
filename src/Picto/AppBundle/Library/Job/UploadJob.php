<?php

namespace Picto\AppBundle\Library\Job;

use Picto\AppBundle\Library\Job\AbstractJob;

class UploadJob extends AbstractJob
{

    /** @var string */
    public $queue = 'upload';

    public function run($args)
    {
        $image = $this->getEntityManager()
            ->getRepository('PictoAppBundle:Image')
            ->findOneById($args['imageId']);

        if ($image) {
            $s3Client = $this->get('picto_app.aws')
                ->get('s3');

            $uploadResult = $s3Client->putObject([
                'Bucket' => $this->getContainer()->getParameter('image_bucket'),
                'Key' => $image->getRemoteFileName(),
                'ContentType' => $image->getContentType(),
                'SourceFile' => $image->getFilePath(),
                'ACL' => \Aws\S3\Enum\CannedAcl::PUBLIC_READ
            ]);

            if ($uploadResult) {
                $image->setRemoteUrl($uploadResult['ObjectURL']);

                $entityManager = $this->getEntityManager();
                $entityManager->persist($image);
                $entityManager->flush();
            }
        }

        return true;
    }

}
