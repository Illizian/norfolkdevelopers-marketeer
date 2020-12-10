<template>
    <span>{{ humanReadableRrule }}</span>
</template>

<script>
    import { rrulestr } from 'rrule'

    export default {
        props: ['resourceName', 'field'],
        computed: {
            /*
            * Get the current rrule as an human readable string.
            */
            humanReadableRrule() {
                if (this.field.value) {
                    return rrulestr(this.field.value).toText();
                }

                return "Non repeating";
            },
        }
    }
</script>
