<?php

namespace AmYouTube\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmYouTube\Model\YouTubeTable;

class YouTubeFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = YouTubeTable::class;

    public function addElements()
    {
        $code = $this->get('code');
        $code->setAttribute('readonly', 'readonly');

        $channelId = $this->get('channelId');
        $channelId->setAttribute('readonly', 'readonly');

        $channelTitle = $this->get('channelTitle');
        $channelTitle->setAttribute('readonly', 'readonly');

        if ($this->formActionType == 'add') {
            $this->add([
                'name' => 'upload',
                'type' => 'Text',
                'options' => [
                    'label' => 'Video',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label'
                    ],
                    'partial_view' => 'administrator/form-partial/image-url',
                ],
                'attributes' => [
                    'id' => 'upload',
                    'class' => 'form-control browsefile'
                ]
            ], [
                'priority' => -1000
            ]);
        }
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        if ($this->formActionType == 'add') {
            //TODO: no funciona el validador cuando hemos añadido el campo a mano. REVISAR
            $inputFilter['upload'] = [
                'name' => 'upload',
                'required' => true,
                'filters' => [
                    [
                        'name' => MediaUri::class
                    ]
                ]
            ];
        }

        return $inputFilter;
    }
}
