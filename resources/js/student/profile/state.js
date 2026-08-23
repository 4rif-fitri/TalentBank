export const profileState = {

    data: null,
    loading: false,

    setData(data) {
        this.data = data;
    },

    setLoading(value) {
        this.loading = value;
    },

    reset() {
        this.data = null;
        this.loading = false;
    }

};
