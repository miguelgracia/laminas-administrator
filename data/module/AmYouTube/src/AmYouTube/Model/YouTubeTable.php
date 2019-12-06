<?php

namespace AmYouTube\Model;

use Administrator\Model\AdministratorTable;

class YouTubeTable extends AdministratorTable
{
    protected $table = 'youtube_videos';

    public const ENTITY_MODEL_CLASS =  YouTubeModel::class;
}