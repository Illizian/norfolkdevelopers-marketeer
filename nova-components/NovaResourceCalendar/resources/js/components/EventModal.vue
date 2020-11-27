<template>
    <modal @modal-close="handleClose">
        <form
            @submit.prevent="handleConfirm"
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            style="width: 460px"
        >
            <div class="p-6 pb-0 border-b-4 border-30">
                <heading :level="2" class="mb-6">{{ title }}</heading>
            </div>

            <div class="p-6">
                <slot />
            </div>

            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button
                        type="button"
                        data-testid="cancel-button"
                        dusk="cancel-general-button"
                        @click.prevent="handleClose"
                        class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
                    >
                        {{__('Close')}}
                    </button>

                    <a
                        class="btn btn-default btn-primary"
                        :href="url"
                    >
                        Edit
                    </a>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
    export default {
        name: "EventModal",
        props: {
            title: String,
            url: String,
        },
        methods: {
            handleClose() {
                this.$emit('close')
            },
            handleConfirm() {
                this.$emit('confirm')
            },
        },
        /**
         * Mount the component.
         */
        mounted() {
            // this.$refs.confirmButton.focus()
            console.log('Mounted');
        },
    }
</script>
