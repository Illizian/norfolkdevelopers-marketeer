Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'nova-resource-calendar',
            path: '/nova-resource-calendar',
            component: require('./components/Tool'),
        },
    ]);
});
