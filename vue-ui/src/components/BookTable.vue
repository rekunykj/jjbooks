<template>
  <div>
    <table v-if="filteredBooks.length" class="table table-striped table-hover">
      <caption>Current Books</caption>
      <thead>
      <tr>
        <th><h2>Books:</h2></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="item in filteredBooks" :key="item.id">
        <td><BookCard  :book="item" :activeUser="activeUser"></BookCard></td>
      </tr>
      </tbody>
    </table>
    <b-alert v-else variant="warning" show>
      No books found!
    </b-alert>
  </div>

</template>

<script>
import BookCard from "@/components/BookViewCard";
export default {
name: "BookTable",
  components: {BookCard},
  props: {
  //parameters being used to filter with.
    filterParams: Array,
    activeUser: Object
  },
  data: function() {
    return {
      //books is all books in memory
      books: [],
      //filtered books is all books based on active filters.
      filteredBooks: []
    }
  },
methods: {
  getBooks: function() {

    this.axios.get(this.BOOK_API_URL)
        .then((resp) => {

          //initially set up all books
          this.books = resp.data;
          //filteredBooks is what is mostly displayed
          this.filteredBooks = this.books;
        })
        .catch((err) =>{
          if(err.response){
            console.log(err.response);
          }
        }).finally(()=>{
      this.$emit('callingAPI', false);
    });
  },

  //apply our filters
  filterTable: function(){
    this.filteredBooks = this.books.filter(book =>
        book.title.toLowerCase().includes(this.filterParams[0].title.toLowerCase())
        && book.genre.includes(this.filterParams[1].genre)
        && book.isAvailable === this.filterParams[2].availability);
  }
}
,mounted(){
    this.getBooks();
  }
}
</script>