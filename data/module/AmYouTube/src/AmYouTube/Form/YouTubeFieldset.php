<?php

namespace AmYouTube\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmYouTube\Model\YouTubeTable;

class YouTubeFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = YouTubeTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'visibility' => array(
                    'public' => 'Público',
                    'private' => 'Privado',
                    'unlisted' => 'Oculto'
                ),
            )
        );
    }

    public function addFields()
    {
        $code = $this->get('code');
        $code->setAttribute('readonly','readonly');

        $channelId = $this->get('channelId');
        $channelId->setAttribute('readonly','readonly');

        $channelTitle = $this->get('channelTitle');
        $channelTitle->setAttribute('readonly','readonly');

        if ($this->formActionType == 'add') {
            $this->add(array(
                'name' => 'upload',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Video',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label'
                    ),
                    'partial_view' => 'administrator/form-partial/image-url',
                ),
                'attributes' => array(
                    'id' => 'upload',
                    'class' => 'form-control browsefile'
                )
            ),array(
                'priority' => -1000
            ));
        }
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        if ($this->formActionType == 'add') {
            //TODO: no funciona el validador cuando hemos añadido el campo a mano. REVISAR
            $inputFilter['upload'] = array(
                'name' => 'upload',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => MediaUri::class
                    )
                )
            );
        }

        return $inputFilter;
    }
}

