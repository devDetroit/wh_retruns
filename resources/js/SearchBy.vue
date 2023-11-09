<template>
  <div>
    <label class="typo__label" for="ajax">{{this.title}}</label>
    <multiselect v-model="selectedRecord" id="ajax" track-by="data" label="data" placeholder="Type to search"  :options="records" :searchable="true" :loading="isLoading" :internal-search="false" :clear-on-select="false" :options-limit="300"  :limit-text="limitText" :max-height="600"  @search-change="handleSearchChange">
      <span slot="noResult">Oops! No elements found.</span>
    </multiselect>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect';

export default {
  components: {
    Multiselect,
  },
  props: ['url', 'title'],
  data() {
    return {
      selectedRecord: null,
      records: [],
      isLoading: false,
      searchTimeout: null,
    };
  },
  methods: {
    limitText(count) {
      return `and ${count} other countries`;
    },
    handleSearchChange(query) {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        if(query)
          this.fetchData(query);
      }, 2000);
    },
    async fetchData(query) {
      axios({
          method: 'get',
          url: this.url,
          params: {
              tracking: query
          }
      })
      .then((response) =>{
          this.records = response.data;
      }).catch(error => alert(error));
    },
    clearAll() {
      this.selectedRecord = null;
    },
  },
};
</script>
