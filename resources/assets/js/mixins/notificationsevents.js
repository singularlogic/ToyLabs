export default {
    methods: {
        read() {
            this.$emit('read');
        },
        accept() {
            this.$emit('accept');
        },
        decline() {
            this.$emit('decline');
        }
    }
}