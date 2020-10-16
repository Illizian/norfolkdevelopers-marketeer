Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-tweet-field', require('./components/IndexField').default)
  Vue.component('detail-nova-tweet-field', require('./components/DetailField').default)
  Vue.component('form-nova-tweet-field', require('./components/FormField').default)
})
