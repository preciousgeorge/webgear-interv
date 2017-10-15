new Vue({
    el: '#app',

    data: {
        vouchers: []
    },
    created: function() {
        this.fetchVouchers()
    },

    methods: {
        fetchVouchers: function() {
            var resource = this.$resource('api/vouchers')
            resource.get(function(result) {
                this.vouchers = result
            }.bind(this))

        },

        submitVoucher: function(id) {
            this.$http.put('api/submit-voucher', { 'id': id })
                .then(response => {
                        this.vouchers = response.data
                    },
                    response => {
                        console.log('Could not perform update')
                    })

        },

        refreshVouchers: function() {
            this.$http.get('api/vouchers')
                .then(response => {
                        this.vouchers = response.data
                    },
                    response => {
                        console.log('Could not perform update')
                    })
        }
    }


})