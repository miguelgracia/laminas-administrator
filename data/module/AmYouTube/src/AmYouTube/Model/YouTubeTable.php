<?php

namespace AmYouTube\Model;

use Administrator\Model\AdministratorTable;

class YouTubeTable extends AdministratorTable
{
    protected $table = 'youtube_videos';

    protected $entityModelName =  YouTubeModel::class;
}