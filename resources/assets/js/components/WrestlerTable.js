import Vuetable from 'vuetable-2/src/components/Vuetable'
Vue.component('wrestler-table', {
    data() {
        return {
            sort: {
                column: 'id',
                asc: true
            },
            statuses: null,
            status: 0,
            filters: {}
        }
    },

    props: ['wrestler_list'],

    components: {
        'wrestler-row': require('./WrestlerRow.vue'),
        'vuetable': Vuetable,
    },

    computed: {
        filteredList() {
            return _.filter(this.wrestler_list, this.filters)
        },

        wrestlers() {
            return _.orderBy(this.filteredList, [this.sort.column],[this.sort.asc ? 'asc' : 'desc']);
        }
    },

    mounted() {
        axios.get('/wrestler-statuses').then(({data}) => {
            this.statuses = data;
        }).catch((errors) => {
            console.log(errors);
        })
    },

    methods: {
        sort(column) {
            if(this.sort.column.toLowerCase() === column.toLowerCase()) {
                this.sort.asc = !this.sort.asc;
            } else {
                this.sort.column = column.toLowerCase();
                this.sort.asc = true;
            }
        }
    },

    watch: {
        status() {
            if(this.status != 0) {
                this.$set(this.filters, 'status_id', this.status)
            } else {
                this.$delete(this.filters, 'status_id');
            }

            this.$refs.vuetable.setData(this.filteredList);
        }
    }
});