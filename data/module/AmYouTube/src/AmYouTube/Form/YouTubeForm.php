<?php

namespace AmYouTube\Form;

use Administrator\Form\AdministratorForm;

class YouTubeForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                YouTubeFieldset::class => array(),
            )
        );
    }
}