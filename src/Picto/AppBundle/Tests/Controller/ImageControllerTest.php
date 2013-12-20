<?php

namespace Picto\AppBundle\Tests\Controller;

use Picto\AppBundle\Library\Job\UploadJob;
use Picto\AppBundle\Tests\TestCase;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ImageControllerTest extends TestCase
{

    public function testViewingInvalidImage()
    {
        $fileName = basename($this->getContainer()->getParameter('image_not_found_path'));

        $client = static::createClient();
        $client->request('GET', $this->generateImageUrl('invalid-image'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($fileName, $client->getCrawler()->text());
    }

    public function testViewingInvalidRawImage()
    {
        $client = static::createClient();
        $client->request('GET', $this->generateRawImageUrl('invalid-image', 'jpg'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            $this->getContainer()->getParameter('image_not_found_mime_type'),
            $client->getResponse()->headers->get('Content-Type')
        );
    }

    public function testViewingInvalidRawImageCachesIt()
    {
        $imageNotFoundHash = $this->getContainer()
            ->getParameter('image_not_found_hash');

        $redis = $this->getContainer()->get('picto_app.cache');
        $redis->del($imageNotFoundHash);

        $this->assertFalse($redis->exists($imageNotFoundHash));

        $client = static::createClient();
        $client->request('GET', $this->generateRawImageUrl('invalid-image', 'jpg'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($redis->exists($imageNotFoundHash));
    }

    public function testUploadingImageByComputerRequiresFile()
    {
        $client = static::createClient();
        $client->request('GET', $this->getUrl('picto_app_index'));

        $form = $client->getCrawler()->selectButton('submit-computer')->form();
        $form['computerUpload[image]'] = null;
        $client->submit($form);

        $message = "Please select an image to upload.";
        $this->assertContains($message, $client->getCrawler()->text());
    }

    public function testUploadingImageByComputerRequiresValidImage()
    {
        $filePath = __FILE__;
        $fileName = basename($filePath);
        $file = new UploadedFile($filePath, $fileName, 'text/plain', filesize($filePath));

        $client = static::createClient();
        $client->request('GET', $this->getUrl('picto_app_index'));

        $form = $client->getCrawler()->selectButton('submit-computer')->form();
        $form['computerUpload[image]'] = $file;
        $client->submit($form);

        $message = "Please upload a valid image.";
        $this->assertContains($message, $client->getCrawler()->text());
    }

    public function testUploadingImageByComputerRequiresValidSize()
    {
        // @todo - Not sure how to test attempting to upload an 8MB
        // image without storing it in the repository.
    }

    public function testUploadingImageByComputer()
    {
        $parameters = $this->getContainer()->parameters;

        $imagePath = $parameters['picto_test_image_path'];
        $imageHash = $parameters['picto_test_image_hash'];
        $imageMimeType = $parameters['picto_test_image_mime_type'];

        $client = static::createClient();
        $client->request('GET', $this->getUrl('picto_app_index'));

        $form = $client->getCrawler()->selectButton('submit-computer')->form();
        $form['computerUpload[image]'] = new UploadedFile($imagePath, basename($imagePath), $imageMimeType);
        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertNotNull($client->getResponse()->headers->get('X-Picto-Image-Hash'));

        $resque = $this->getContainer()->get('bcc_resque.resque');
        $length = $resque->clearQueue('upload');

        $this->assertGreaterThan(0, $length);
    }

    /**
     * @dataProvider providerInvalidImageUrl
     */
    public function testUploadingImageByUrlRequiresValidUrl($url)
    {
        // Making real HTTP requests here. Assuming google.com is always
        // going to be up when this is executed.
        $client = static::createClient();
        $client->request('GET', $this->getUrl('picto_app_index'));

        $form = $client->getCrawler()->selectButton('submit-url')->form();
        $form['urlUpload[url]'] = $url;
        $client->submit($form);

        $message = "Please paste a valid image URL to upload.";
        $this->assertContains($message, $client->getCrawler()->text());
    }

    public function testUploadingImageByUrl()
    {
        // Hardcoded! Also assuming this image will always be valid.
        $url = 'https://s3.amazonaws.com/picto.images/_not_found.png';

        $client = static::createClient();
        $client->request('GET', $this->getUrl('picto_app_index'));

        $form = $client->getCrawler()->selectButton('submit-url')->form();
        $form['urlUpload[url]'] = $url;
        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertNotNull($client->getResponse()->headers->get('X-Picto-Image-Hash'));
    }

    public function providerInvalidImageUrl()
    {
        $provider = [
            [null],
            [''],
            ['invalid'],
            ['https://www.google.com']
        ];

        return $provider;
    }

    private function generateImageUrl($hash)
    {
        return $this->getUrl('picto_app_view_image', ['hash' => $hash]);
    }

    private function generateRawImageUrl($hash, $extension, $noCount=0)
    {
        $parameters = [
            'hash' => $hash,
            'extension' => $extension,
            'noCount' => (int)$noCount
        ];

        return $this->getUrl('picto_app_view_raw_image', $parameters);
    }

}
