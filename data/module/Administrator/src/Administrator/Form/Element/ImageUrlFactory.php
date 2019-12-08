<?php

namespace Administrator\Form\Element;

use AmYouTube\Service\YoutubeService;
use Interop\Container\ContainerInterface;
use Zend\Form\Element;
use Zend\ServiceManager\Factory\FactoryInterface;

class ImageUrlFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $imageUrl = $container->get('FormElementManager')->build(Element::class, $options);
        $youtubeVideos = $container->get(YoutubeService::class)->getVideosInDatabase();

        return $imageUrl
            ->setOption('partial_view', 'administrator/form-partial/image-url')
            ->setOption('allow_add_multiple_files', true)
            ->setAttribute('type', 'text')
            ->setAttribute('class', 'browsefile')
            ->setAttribute('readonly', 'readonly')
            ->setAttribute('data-youtube', json_encode($youtubeVideos->toObjectArray()));
    }
}
