import { format } from 'date-fns'

Nova.booting((Vue, router, store) => {
    Vue.filter('date-format', function(value) {
        if (value) {
            return format(value, 'PPPP pp');
        }
    });

    Vue.component('index-NovaRruleField', require('./components/IndexField'))
    Vue.component('detail-NovaRruleField', require('./components/DetailField'))
    Vue.component('form-NovaRruleField', require('./components/FormField'))
})
