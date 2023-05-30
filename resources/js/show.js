import SearchTracking from "./search-component";

new Vue({
    el: '#app',
    components: {
        'search-tracking': SearchTracking
    },
    data: {
        photosModal: new bootstrap.Modal(document.getElementById('photosModal')),
        photos: [],
        currentPartNumber: '',
    },
    methods: {
        getPhotos(partnumber_id, partnumber) {

            let instance = this;
            this.currentPartNumber = partnumber;
            this.photos = [];
            document.getElementById('bntHiddenModal').click();
            axios({
                method: 'get',
                url: '/api/photos',
                params: {
                    partNumber_id: partnumber_id
                }
            })
                .then(function (response) {
                    instance.photos = response.data.photos;
                }).catch(error => sweetAlertAutoClose('error', "no photos to show"));
        }
    },
})