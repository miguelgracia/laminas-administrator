<?php

namespace Administrator\Form\Element;

use AmYouTube\Service\YoutubeService;
use Zend\Form\Element;

class ImageUrl extends Element
{
    public function __construct(YoutubeService $youtubeService)
    {
        parent::__construct('imageUrl', []);

        $youtubeVideos = $youtubeService->getVideosInDatabase();
        $this
            ->setOption('partial_view','administrator/form-partial/image-url')
            ->setOption('allow_add_multiple_files', true)
            ->setAttribute('type', 'text')
            ->setAttribute('class', 'browsefile')
            ->setAttribute('readonly', 'readonly')
            ->setAttribute('data-youtube',json_encode($youtubeVideos->toObjectArray()));
    }
}