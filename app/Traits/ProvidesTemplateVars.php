<?php

namespace App\Traits;

trait ProvidesTemplateVars {
    /**
     * Retrieve this models attributes as returned from toArray(), and
     * return them as TemplateVars array that we can use as suggestions
     *
     * @return array
     */
    public function getTemplateVarsAttribute() : array
    {
        return collect($this->toArray())
            ->mapWithKeys(function ($value, $key) {
                return [":$key" => $value];
            })
            ->toArray();
    }
}
