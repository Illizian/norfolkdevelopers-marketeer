<template>
    <default-field :field="field" :errors="errors" :show-help-text="showHelpText" :fullWidthContent="true">
        <template slot="field">
            <label
                v-if="showToggle"
                class="flex items-center select-none mr-4 pt-2"
            >
                <input
                    class="checkbox mr-2"
                    type="checkbox"
                    v-model="enabled"
                >
                Enabled
            </label>

            <div class="flex mt-4" v-if="enabled">
                <div class="w-3/5" @change="setUpdated()">
                    <field-container
                        label="Frequency"
                        help-text="How often does the recurrence occur."
                    >
                        <div class="flex flex-wrap">
                            <label
                                v-if="minFrequency <= Object.keys(RRULES).indexOf('YEARLY')"
                                class="flex items-center select-none mb-2 mr-4"
                            >
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.freq"
                                    :value="RRULES.YEARLY"
                                >
                                Yearly
                            </label>

                            <label
                                v-if="minFrequency <= Object.keys(RRULES).indexOf('MONTHLY')"
                                class="flex items-center select-none mb-2 mr-4"
                            >
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.freq"
                                    :value="RRULES.MONTHLY"
                                >
                                Monthly
                            </label>

                            <label
                                v-if="minFrequency <= Object.keys(RRULES).indexOf('WEEKLY')"
                                class="flex items-center select-none mb-2 mr-4"
                            >
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.freq"
                                    :value="RRULES.WEEKLY"
                                >
                                Weekly
                            </label>

                            <label
                                v-if="minFrequency <= Object.keys(RRULES).indexOf('DAILY')"
                                class="flex items-center select-none mb-2 mr-4"
                            >
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.freq"
                                    :value="RRULES.DAILY"
                                >
                                Daily
                            </label>

                            <label
                                v-if="minFrequency <= Object.keys(RRULES).indexOf('HOURLY')"
                                class="flex items-center select-none mb-2 mr-4"
                            >
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.freq"
                                    :value="RRULES.HOURLY"
                                >
                                Hourly
                            </label>

                            <label
                                v-if="minFrequency <= Object.keys(RRULES).indexOf('MINUTELY')"
                                class="flex items-center select-none mb-2 mr-4"
                            >
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.freq"
                                    :value="RRULES.MINUTELY"
                                >
                                Mintely
                            </label>

                            <label
                                v-if="minFrequency <= Object.keys(RRULES).indexOf('SECONDLY')"
                                class="flex items-center select-none mb-2 mr-4"
                            >
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.freq"
                                    :value="RRULES.SECONDLY"
                                >
                                Secondly
                            </label>
                        </div>
                    </field-container>

                    <div v-if="!field.hideDates" class="flex">
                        <div class="w-1/2 pr-1">
                            <field-container
                                label="Start Date"
                                help-text="If given, will specify the start of the recurrence."
                            >
                                <input
                                    class="w-full form-control form-input form-input-bordered"
                                    type="date"
                                    v-model="rrule.dtstart"
                                >
                            </field-container>
                        </div>

                        <div class="w-1/2 pl-1">
                            <field-container
                                label="End Date"
                                help-text="If given, will specify the end of the recurrence."
                            >
                                <input
                                    class="w-full form-control form-input form-input-bordered"
                                    type="date"
                                    v-model="rrule.until"
                                >
                            </field-container>
                        </div>
                    </div>

                    <div v-if="!field.hideOccurences" class="flex">
                        <div class="w-1/2 pr-1">
                            <field-container
                                label="Count"
                                help-text="How many occurrences will be generated."
                            >
                                <input
                                    class="w-full form-control form-input form-input-bordered"
                                    type="number"
                                    v-model.number="rrule.count"
                                >
                            </field-container>
                        </div>

                        <div class="w-1/2 pl-1">
                            <field-container
                                label="Interval"
                                help-text="The interval between each frequency iteration."
                            >
                                <input
                                    class="w-full form-control form-input form-input-bordered"
                                    type="number"
                                    v-model.number="rrule.interval"
                                >
                            </field-container>
                        </div>
                    </div>

                    <field-container
                        v-if="!field.hideWeekStarts"
                        label="Week Starts"
                        help-text="Specify the first day of the week. This will affect recurrences based on weekly periods. The default week start is Monday."
                    >
                        <div class="flex flex-wrap">
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.wkst"
                                    :value="RRULES.MO"
                                >
                                Monday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.wkst"
                                    :value="RRULES.TU"
                                >
                                Tuesday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.wkst"
                                    :value="RRULES.WE"
                                >
                                Wednesday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.wkst"
                                    :value="RRULES.TH"
                                >
                                Thursday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.wkst"
                                    :value="RRULES.FR"
                                >
                                Friday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.wkst"
                                    :value="RRULES.SA"
                                >
                                Saturday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="radio"
                                    v-model="rrule.wkst"
                                    :value="RRULES.SU"
                                >
                                Sunday
                            </label>
                        </div>
                    </field-container>

                    <field-container
                        v-if="!field.hideWhichDays"
                        label="Which Days"
                        help-text="Define the weekdays where the recurrence will be applied."
                    >
                        <div class="flex flex-wrap">
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.byweekday"
                                    :value="0"
                                >
                                Monday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.byweekday"
                                    :value="1"
                                >
                                Tuesday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.byweekday"
                                    :value="2"
                                >
                                Wednesday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.byweekday"
                                    :value="3"
                                >
                                Thursday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.byweekday"
                                    :value="4"
                                >
                                Friday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.byweekday"
                                    :value="5"
                                >
                                Saturday
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.byweekday"
                                    :value="6"
                                >
                                Sunday
                            </label>
                        </div>
                    </field-container>

                    <field-container
                        v-if="!field.hideWhichMonths"
                        label="Which Months"
                        help-text="Define the months to apply the recurrence in."
                    >
                        <div class="flex flex-wrap">
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="1"
                                >
                                Jan
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="2"
                                >
                                Feb
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="3"
                                >
                                Mar
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="4"
                                >
                                Apr
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="5"
                                >
                                May
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="6"
                                >
                                Jun
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="7"
                                >
                                Jul
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="8"
                                >
                                Aug
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="9"
                                >
                                Sep
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="10"
                                >
                                Oct
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="11"
                                >
                                Nov
                            </label>
                            <label class="flex items-center select-none mb-2 mr-4">
                                <input
                                    class="checkbox mr-2"
                                    type="checkbox"
                                    v-model.number="rrule.bymonth"
                                    :value="12"
                                >
                                Dec
                            </label>
                        </div>
                    </field-container>

                    <!-- The following fields, seem to cause considerable issues for rrule libraries. -->
                    <field-container
                        v-if="!field.hideOccurence"
                        label="Occurence"
                        help-text="Define an occurrence number, corresponding to the nth occurrence of the rule inside the frequency period."
                    >
                        <input
                            class="w-full form-control form-input form-input-bordered"
                            type="number"
                            v-model.number="rrule.bysetpos"
                        >
                    </field-container>

                    <field-container
                        v-if="!field.hideMonthDay"
                        label="Month Day"
                        help-text="Define the month days to apply the recurrence to."
                    >
                        <input
                            class="w-full form-control form-input form-input-bordered"
                            type="number"
                            v-model.number="rrule.bymonthday"
                        >
                    </field-container>

                    <field-container
                        v-if="!field.hideYearDay"
                        label="Year Day"
                        help-text="Define the year days to apply the recurrence to."
                    >
                        <input
                            class="w-full form-control form-input form-input-bordered"
                            type="number"
                            v-model.number="rrule.byyearday"
                        >
                    </field-container>

                    <field-container
                        v-if="!field.hideWeekNo"
                        label="Week No"
                        help-text="Define the week numbers to apply the recurrence to. Week numbers have the meaning described
                        in ISO8601, that is, the first week of the year is that containing at least four days of the new year."
                    >
                        <input
                            class="w-full form-control form-input form-input-bordered"
                            type="number"
                            v-model.number="rrule.byweekno"
                        >
                    </field-container>

                    <div v-if="!field.hideTimes" class="flex -mx-1">
                        <div class="w-1/3 px-1">
                            <field-container
                                label="Hour"
                                help-text="Define the hours to apply the recurrence to."
                            >
                                <input
                                    class="w-full form-control form-input form-input-bordered"
                                    type="number"
                                    v-model.number="rrule.byhour[0]"
                                >
                            </field-container>
                        </div>

                        <div class="w-1/3 px-1">
                            <field-container
                                label="Minute"
                                help-text="Define the minutes to apply the recurrence to."
                            >
                                <input
                                    class="w-full form-control form-input form-input-bordered"
                                    type="number"
                                    v-model.number="rrule.byminute[0]"
                                >
                            </field-container>
                        </div>

                        <div class="w-1/3 px-1">
                            <field-container
                                label="Second"
                                help-text="Define the seconds to apply the recurrence to."
                            >
                                <input
                                    class="w-full form-control form-input form-input-bordered"
                                    type="number"
                                    v-model.number="rrule.bysecond[0]"
                                >
                            </field-container>
                        </div>
                    </div>
                </div>
                <div class="w-2/5 pl-2">
                    <p class="block font-bold">Results</p>
                    <p class="mt-2 text-sm text-grey">These rules produce the following dates:</p>
                    <ol class="mt-4">
                        <li
                            class="mb-2"
                            v-for="date, index in dates"
                            v-if="index < 10"
                        >
                            <span v-if="index === 9">...</span>
                            <span v-else>{{ date | date-format }}</span>
                        </li>
                    </ol>
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova'
    import { format } from 'date-fns'
    import { RRule, rrulestr } from 'rrule'

    import FieldContainer from './partials/FieldContainer';

    const RRULES = {
        SECONDLY: RRule.SECONDLY,
        MINUTELY: RRule.MINUTELY,
        HOURLY: RRule.HOURLY,
        DAILY: RRule.DAILY,
        WEEKLY: RRule.WEEKLY,
        MONTHLY: RRule.MONTHLY,
        YEARLY: RRule.YEARLY,
        MO: RRule.MO,
        TU: RRule.TU,
        WE: RRule.WE,
        TH: RRule.TH,
        FR: RRule.FR,
        SA: RRule.SA,
        SU: RRule.SU,
    };

    export default {
        mixins: [FormField, HandlesValidationErrors],

        components: {
            'field-container': FieldContainer
        },

        props: ['resourceName', 'resourceId', 'field'],

        data() {
            return {
                RRULES,
                showToggle: this.field.showHidden || false,
                enabled: !(this.field.showHidden || false),
                dirty: false,
                rrule: {
                    freq: RRule.DAILY,
                    dtstart: format(new Date(), 'yyyy-MM-dd'),
                    until: null,
                    count: 30,
                    interval: 1,
                    wkst: null,
                    byweekday: [],
                    bymonth: [],
                    byhour: [],
                    byminute: [],
                    bysecond: [],
                    bysetpos: null,
                    bymonthday: null,
                    byyearday: null,
                    byweekno: null,
                }
            };
        },

        methods: {
            /*
            * Set the initial, internal value for the field.
            */
            setInitialValue() {
                if (this.field.value && this.field.value !== 'null') {
                    var rrule = rrulestr(this.field.value).origOptions;
                    this.enabled = true;
                    this.hasValue = true;
                } else {
                    var rrule = {};
                }

                this.rrule.freq = (rrule.freq != undefined)
                    ? rrule.freq
                    : this.rrule.freq;
                this.rrule.dtstart = (rrule.dtstart != undefined)
                    ? format(rrule.dtstart, 'yyyy-MM-dd')
                    : this.rrule.dtstart;
                this.rrule.until = (rrule.until != undefined)
                    ? format(rrule.until, 'yyyy-MM-dd')
                    : this.rrule.until;
                this.rrule.count = (rrule.count != undefined)
                    ? rrule.count
                    : this.rrule.count;
                this.rrule.interval = (rrule.interval != undefined)
                    ? rrule.interval
                    : this.rrule.interval;
                this.rrule.wkst = (rrule.wkst != undefined)
                    ? rrule.wkst
                    : this.rrule.wkst;

                this.rrule.byweekday = (rrule.byweekday != undefined)
                    ? rrule.byweekday.map(entry => entry.weekday.toString())
                    : this.rrule.byweekday;
                this.rrule.bymonth = (rrule.bymonth != undefined)
                    ? Array.isArray(rrule.bymonth) ? rrule.bymonth : [ rrule.bymonth ]
                    : this.rrule.bymonth;
                this.rrule.byhour = (rrule.byhour != undefined)
                    ? Array.isArray(rrule.byhour) ? rrule.byhour : [ rrule.byhour ]
                    : this.rrule.byhour;
                this.rrule.byminute = (rrule.byminute != undefined)
                    ? Array.isArray(rrule.byminute) ? rrule.byminute : [ rrule.byminute ]
                    : this.rrule.byminute;
                this.rrule.bysecond = (rrule.bysecond != undefined)
                    ? Array.isArray(rrule.bysecond) ? rrule.bysecond : [ rrule.bysecond ]
                    : this.rrule.bysecond;

                this.rrule.bysetpos = (rrule.bysetpos != undefined)
                    ? rrule.bysetpos
                    : this.rrule.bysetpos;
                this.rrule.bymonthday = (rrule.bymonthday != undefined)
                    ? rrule.bymonthday
                    : this.rrule.bymonthday;
                this.rrule.byyearday = (rrule.byyearday != undefined)
                    ? rrule.byyearday
                    : this.rrule.byyearday;
                this.rrule.byweekno = (rrule.byweekno != undefined)
                    ? rrule.byweekno
                    : this.rrule.byweekno;
            },
            /**
            * Fill the given FormData object with the field's internal value.
            */
            fill(formData) {
                if (this.hasValue && this.enabled) {
                    formData.append(this.field.attribute, this.getRrule().toString() || '');
                } else {
                    formData.append(this.field.attribute, '');
                }
            },
            setUpdated() {
                if (!this.hasValue) {
                    this.hasValue = true;
                }
            },
            /**
            * Create a new RRule from current configuration
            * - Here be dragons
            */
            getRrule() {
                let output = Object.assign(
                    {},
                    // Firstly, strip Vue's getters/setters
                    JSON.parse(JSON.stringify(this.rrule)),
                    // Then, Ensure date fields, are JS Dates
                    {
                        dtstart: this.rrule.dtstart ? new Date(this.rrule.dtstart) : null,
                        until: this.rrule.until ? new Date(this.rrule.until) : null,
                    }
                );

                // Then, Strip null values
                Object.keys(output).forEach((key) =>
                    (output[key] == null || output[key] === "" || output[key].length === 0) && delete output[key]
                );

                return new RRule(output);
            },
        },

        computed: {
            /*
            * Get the current rrule as an human readable string.
            */
            humanReadableRrule() {
                var rrule = this.field.value ? rrulestr(this.field.value).origOptions : {};
            },
            /*
            * Get the list of dates produced by the RRule
            */
            dates() {
                return this.getRrule().all((date, i) => i < 10)
            },

            /*
            * Determine the minFrequency as an integer
            */
            minFrequency() {
                return Object.keys(this.RRULES).indexOf(this.field.minFrequency);
            },
        }
    }
</script>
