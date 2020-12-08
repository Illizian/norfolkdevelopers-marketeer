<template>
    <panel-item :field="override" />
</template>

<script>
    import { rrulestr } from 'rrule'

    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],
        computed: {
            /*
            * Get the current rrule as an human readable string.
            */
            override() {
                if (this.field.value) {
                    return {
                        name: this.field.name,
                        value: rrulestr(this.field.value).toText(),
                    };
                }

                return this.field;
            },
        }
    }
</script>
