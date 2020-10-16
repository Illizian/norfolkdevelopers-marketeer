<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <div class="field__emoji-wrapper">
        <textarea
          :id="field.name"
          class="w-full form-control form-input form-input-bordered min-h-textarea py-3"
          :class="errorClasses"
          :placeholder="field.name"
          v-model="value"
        />
        <button type="button" class="emoji-wrapper__toggle" v-on:click="togglePicker">
          🙂
        </button>
        <div v-show="open" class="emoji-wrapper__picker">
          <VEmojiPicker @select="selectEmoji" :emojiWithBorder="false" :emojisByRow="6" />
        </div>
      </div>
      <div class="field__footer">
        <p :class="indicatorClass">{{ count }}/280</p>
      </div>
    </template>
  </default-field>
</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova';
  import VEmojiPicker from 'v-emoji-picker';
  import twitter from 'twitter-text';

  export default {
    mixins: [FormField, HandlesValidationErrors],

    components: {
      VEmojiPicker,
    },

    data: () => ({ open: false }),

    props: ['resourceName', 'resourceId', 'field'],

    computed: {
      count() {
        return twitter.parseTweet(this.value).weightedLength;
      },
      valid() {
        return twitter.parseTweet(this.value).valid;
      },
      indicatorClass() {
        let classes = "text-right font-bold opacity-50 text-80 text-xs mt-2";

        if (this.valid) {
          return classes;
        } else {
          return classes + " text-danger";
        }
      }
    },

    methods: {
      togglePicker() {
        this.open = !this.open;
      },

      selectEmoji(emoji) {
        this.value += emoji.data;
      },

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

      /**
       * Update the field's internal value.
       */
      handleChange(value) {
        this.setValue(value);
      },
    },
  }
</script>
