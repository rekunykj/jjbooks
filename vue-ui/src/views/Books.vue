<template>
  <div>
    <br/>
    <br/>
    <div class="row">
          <FilterBox class="col-6" @newGenre="handleGenre" @newAvailableFilter="handleAvail" @newTitleFilter="handleTitle"></FilterBox>
          <BookForm class="col-6" @refreshTable="handleRefresh"></BookForm>

          <BookTable :filter-params="parameters"  ref="bookTable" class="col-12"> </BookTable>
      </div>
  </div>

</template>

<script>
import BookForm from "@/components/BookForm";
import FilterBox from "@/components/FilterBox";
import BookTable from "@/components/BookTable";
export default {
name: "Books",
  components: {BookTable, FilterBox, BookForm},
  data: function() {
  return {
    //set up initial parameters for filter as default values
    parameters: [{title: ""}, {genre: ""}, {availability: true}]
    }
  },
  methods: {
  //set the filter parameter for title
    handleTitle: function(title) {
      this.parameters[0].title = title;
      this.$refs.bookTable.filterTable();
    },
    //set the filter parameter for genre
    handleGenre: function(genre) {
      //check for null as the options menu can select null again breaking the filter by passing in null.
      this.parameters[1].genre = genre != null ? genre : "";
      this.$refs.bookTable.filterTable();
    },
    //set the availability parameter to filter by
    handleAvail: function(availability) {
      this.parameters[2].availability = availability;
      this.$refs.bookTable.filterTable();
    },
    //tell the child component to re-filter itself with new values.
    handleRefresh: function() {
      this.$refs.bookTable.getBooks();
    }

  }
}
</script>