import SearchBy from './SearchBy'

// register globally

const SearchTracking = {
  name: 'SearchTracking',
  components: {
    'multiselect': SearchBy
  },
  // Define the component's template
  template: `
  <div>
   <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <i class="fa-solid fa-magnifying-glass"></i>   Search Record
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Search</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
            <div class="mb-3">
              <multiselect url='/api/returns/tracking' title='TrackingNumber' ref="trackingMultiselect"></multiselect>
            </div>
            <div class="mb-3">
              <multiselect url='/api/returns/order' title='OrderNumber' ref="orderMultiselect"></multiselect>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="clearMultiselects">Clear</button>
            <button type="button" class="btn btn-primary" @click="search">Search</button>
          </div>
        </div>
      </div>
    </div>
    </div>

  `,

  // Define the component's methods
  methods: {
    clearMultiselects() {
      this.$refs.trackingMultiselect.clearAll();
      this.$refs.orderMultiselect.clearAll();
    },
    search() {

      if (this.$refs.trackingMultiselect.selectedRecord != null) {
        location.href = '/returns/' + this.$refs.trackingMultiselect.selectedRecord.id;
      }
      if (this.$refs.orderMultiselect.selectedRecord != null) {
        location.href = '/returns/' + this.$refs.orderMultiselect.selectedRecord.id;
      }

    }
  },

};

// Export the component
export default SearchTracking 
