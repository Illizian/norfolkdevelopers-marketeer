<template>
  <panel-item :field="field">
    <template slot="value">
      <excerpt
        :content="field.value"
        :plain-text="true"
        :should-show="true"
      />
    </template>
  </panel-item>
</template>

<script>
  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
  }
</script>
