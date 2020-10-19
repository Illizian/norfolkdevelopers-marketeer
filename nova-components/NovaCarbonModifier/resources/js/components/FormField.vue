<style>
    .form-prepend {
        line-height: 2.25rem;
        background-color: var(--60);
    }

    .form-prepend + .form-control.form-input.form-input-bordered {
        box-shadow: none!important;
        border-top-left-radius: none!important;
        border-bottom-left-radius: none!important;
    }
</style>

<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
        <div class="flex">
            <select v-model="selected" class="form-control form-select w-2/3">
                <option disabled value="">Please select one</option>
                <option v-for="[ key, label, hasParams ], i in config" :key="key" :value="i">
                    {{ label }}
                </option>
            </select>

            <div class="flex w-1/3 pl-2">
                <div v-show="config[selected] && config[selected][2]" class="flex">
                    <span v-show="config[selected]" class="form-prepend rounded-l-lg px-2 text-sm font-bold">
                        {{ config[selected] ? config[selected][0] : '' }}:
                    </span>
                    <input
                        class="bg-white border border-60 px-3 text-80 rounded-lg rounded-l-none outline-none"
                        style=""
                        type="text"
                        v-model="param"
                    />
                </div>
                <button
                    type="button"
                    class="px-2 text-xl font-bold text-blue-500"
                    v-show="selected !== ''"
                    v-on:click="addModifier()"
                >
                    &plus;
                </button>
            </div>
        </div>

        <ol class="mt-2 p-0">
            <li class="inline-block">
                <label class="help-text font-semibold">
                    Popular:
                </label>
            </li>
            <li
                class="inline-block mr-2"
                v-for="modifiers, key in popular"
                :key="key"
            >
                <a href="#" class="help-text no-underline hover:underline" v-on:click="addModifiers(modifiers)">
                    {{ modifiers }}
                </a>
            </li>
        </ol>

        <ol class="mt-2 p-0">
            <li
                class="inline-block mr-2 mb-2 px-3 py-1 bg-gray-200 rounded-full text-sm font-semibold text-gray-700"
                v-for="modifier, key in modifiers"
                :key="key"
            >
                {{ modifier }}
                <button type="button" class="text-red-500 font-bold" v-on:click="removeModifier(key)">
                    ✖
                </button>
            </li>
        </ol>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

const MODIFIERS = [
    [ 'year', 'Set Year', true ],
    [ 'month', 'Set Month', true ],
    [ 'day', 'Set Day', true ],
    [ 'hour', 'Set Hour', true ],
    [ 'minute', 'Set Minute', true ],
    [ 'second', 'Set Second', true ],

    [ 'addCenturies' , 'Add Centuries', true ],
    [ 'subCenturies' , 'Sub Centuries', true ],
    [ 'addYears' , 'Add Years', true ],
    [ 'subYears' , 'Sub Years', true ],
    [ 'addQuarters' , 'Add Quarters', true ],
    [ 'subQuarters' , 'Sub Quarters', true ],
    [ 'addMonths' , 'Add Months', true ],
    [ 'subMonths' , 'Sub Months', true ],
    [ 'addDays' , 'Add Days', true ],
    [ 'subDays' , 'Sub Days', true ],
    [ 'addWeekdays' , 'Add Weekdays', true ],
    [ 'subWeekdays' , 'Sub Weekdays', true ],
    [ 'addWeeks' , 'Add Weeks', true ],
    [ 'subWeeks' , 'Sub Weeks', true ],
    [ 'addHours' , 'Add Hours', true ],
    [ 'subHours' , 'Sub Hours', true ],
    [ 'addMinutes' , 'Add Minutes', true ],
    [ 'subMinutes' , 'Sub Minutes', true ],
    [ 'addSeconds' , 'Add Seconds', true ],
    [ 'subSeconds' , 'Sub Seconds', true ],
    [ 'addMilliseconds' , 'Add Milliseconds', true ],
    [ 'subMillisecond' , 'Sub Millisecond', true ],
    [ 'addMicroseconds' , 'Add Microseconds', true ],
    [ 'subMicroseconds' , 'Sub Microseconds', true ],

    [ 'startOfCentury', 'Start Of Century', false ],
    [ 'endOfCentury', 'End Of Century', false ],
    [ 'startOfDecade', 'Start Of Decade', false ],
    [ 'endOfDecade', 'End Of Decade', false ],
    [ 'startOfYear', 'Start Of Year', false ],
    [ 'endOfYear', 'End Of Year', false ],
    [ 'startOfMonth', 'Start Of Month', false ],
    [ 'endOfMonth', 'End Of Month', false ],
    [ 'startOfWeek', 'Start Of Week', false ],
    [ 'endOfWeek', 'End Of Week', false ],
    [ 'startOfDay', 'Start Of Day', false ],
    [ 'endOfDay', 'End Of Day', false ],
    [ 'startOfHour', 'Start Of Hour', false ],
    [ 'endOfHour', 'End Of Hour', false ],
    [ 'startOfMinute', 'Start Of Minute', false ],
    [ 'endOfMinute', 'End Of Minute', false ],

    [ 'next', 'Next', true ],
    [ 'previous', 'Previous', true ],
];

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: () => ({
        selected: '',
        param: '',
        config: MODIFIERS,
        modifiers: [],
    }),

    computed: {
        popular() {
            return this.field.popular || [];
        }
    },

    methods: {
        /*
        * Set the initial, internal value for the field.
        */
        setInitialValue() {
            this.modifiers = this.field.value
                ? this.field.value.split(',')
                : [];
        },

        /**
        * Fill the given FormData object with the field's internal value.
        */
        fill(formData) {
            formData.append(this.field.attribute, this.modifiers.join(','));
        },

        removeModifier(index) {
            this.modifiers = this.modifiers.filter((modifier, key) => key !== index);
        },

        addModifiers(modifiers) {
            this.modifiers.push.apply(this.modifiers, modifiers.split(','));
        },

        addModifier(modifier) {
            if (!modifier) {
                let method = this.config[this.selected][0];

                if (this.config[this.selected][2]) {
                    modifier = `${method}:${this.param}`;
                } else {
                    modifier = method;
                }
            }

            this.modifiers.push(modifier);

            // Reset the form
            this.selected = '';
            this.param = '';
        }
    },
}
</script>
