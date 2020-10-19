<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
        <at-ta
            :at="trigger"
            :members="suggestions"
        >
            <textarea
                class="py-3 h-auto w-full form-control form-input form-input-bordered text-sm"
                rows="8"
                v-model="value"
            ></textarea>
        </at-ta>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import AtTa from 'vue-at/dist/vue-at-textarea'

export default {
    components: {
        AtTa
    },

    mixins: [
        FormField,
        HandlesValidationErrors
    ],

    props: [
        'resourceName',
        'resourceId',
        'field'
    ],

    computed: {
        trigger() {
            return this.field.trigger || ':';
        },
        suggestions() {
            return this.field.suggestions || [];
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || '';
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },
    },
}
</script>
