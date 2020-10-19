Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-carbon-modifier', require('./components/IndexField'))
  Vue.component('detail-nova-carbon-modifier', require('./components/DetailField'))
  Vue.component('form-nova-carbon-modifier', require('./components/FormField'))
})
