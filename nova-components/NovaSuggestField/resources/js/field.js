Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-suggest-field', require('./components/IndexField'))
  Vue.component('detail-nova-suggest-field', require('./components/DetailField'))
  Vue.component('form-nova-suggest-field', require('./components/FormField'))
})
