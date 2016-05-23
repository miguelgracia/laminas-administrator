<?php

namespace Administrator\Service;

interface DatatableConfigInterface
{
    public function getDatatableConfig();

    public function getQueryConfig();

    public function getViewParams();
}