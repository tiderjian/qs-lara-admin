<?php

namespace Qs\Admin\Form\Field;

use Encore\Admin\Form\Field\MultipleFile as EncoreMultipleFile;

class MultipleFile extends EncoreMultipleFile
{
    use FixMultipleBug;
}
