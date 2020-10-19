<?php

namespace Illizian\NovaSuggestField;

use Laravel\Nova\Fields\Field;

class NovaSuggestField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-suggest-field';

    /**
     * Sets the trigger character.
     *
     * @param  string $string
     * @return $this
     */
    public function trigger($string) : NovaSuggestField
    {
        return $this->withMeta([ 'trigger' => $string ]);
    }

    /**
     * Set a list of suggestions.
     *
     * @param  array $list
     * @return $this
     */
    public function suggestions($list) : NovaSuggestField
    {
        return $this->withMeta([ 'suggestions' => $list ]);
    }
}
