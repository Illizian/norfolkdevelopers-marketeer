<?php

namespace Illizian\NovaCarbonModifier;

use Laravel\Nova\Fields\Field;

class NovaCarbonModifier extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-carbon-modifier';

    /**
     * Set a list of popular modifiers.
     *
     * @param  array $list
     * @return $this
     */
    public function popular($list) : NovaCarbonModifier
    {
        return $this->withMeta([ __FUNCTION__ => $list ]);
    }
}
