<template>
    <div class="mb-8">
        <label class="block font-bold">{{ label }}</label>
        <p class="mt-2 text-sm text-grey">{{ helpText }}</p>
        <div class="mt-4">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            label: String,
            helpText: String,
        },
    }
</script>
