<?php

namespace Jimev\Forms\HTMLEditor;

interface HTMLEditorFieldLocation_ConfigInterface
{
    public function setConfig($name = 'cms');

    public function getNumberOfRows() : int;
}
