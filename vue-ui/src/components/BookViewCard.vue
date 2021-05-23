<template>
  <div>
    <b-overlay :show="isBusy" opacity="0.25">
    <b-card border-variant="info">
      <b-card-title>{{book.title}}</b-card-title>
      <b-card-text>
<!--        display the genre string from the mixin object-->
        Genre: {{bookDictionary[book.genre]}}
      </b-card-text>
      <b-card-text>
<!--        helper function to display all authors from the array-->
        {{printAuthors(book.authors)}}
      </b-card-text>
      <b-card-text>
        Summary: {{book.summary}}
      </b-card-text>
      <b-card-text v-if="book.isAvailable===false" style="color:tomato">
        <b-icon-x-square-fill></b-icon-x-square-fill>
        {{displayAvailable(book.isAvailable)}} - Check again later!
      </b-card-text>
      <b-card-text v-else >
        <b-icon-check style="color:lawngreen"></b-icon-check>
        {{displayAvailable(book.isAvailable)}}
      </b-card-text>
      <b-button  v-if="success!=true" :disabled="!book.isAvailable || loggedIn===false" pill @click="orderBook" variant="success" class="float-right"><b-icon-cart-plus-fill></b-icon-cart-plus-fill> Order now!</b-button>
    </b-card>
      </b-overlay>

    <b-alert variant="success" :show="success" >Book order for {{book.title}} placed!</b-alert>
    <b-alert variant="danger" :show="success===false" dismissible>Oh no something went wrong ordering {{book.title}}! Try again later</b-alert>
    <b-alert variant="danger" :show="loggedIn===false" dismissible>You must register, or log in to order a book!</b-alert>

  </div>

</template>

<script>
export default {
name: "BookCard",
  props: {
    book: Object
  },
  methods: {
  //method to print out all authors from a book.
    printAuthors: function(authorArray) {

      let authors = "Authors: "
      for (let i = 0; i < authorArray.length; i++) {
        if(i === authorArray.length -1) {
          authors += authorArray[i]
        }
        else {
          authors += authorArray[i] + ", ";
        }
      }
      return authors;

    },
    //simply show the string based on boolean value
    displayAvailable(isAvailable)
    {
      return isAvailable ? "Available" : "Unavailable";
    },
    //begin ordering a new book
    orderBook(){
      this.isBusy = true;

      if(this.loggedIn)
      {
        this.isBusy = true;
        this.createBookOrder();
        this.isBusy = false;
      }
    },
    //method to create the book order and post it to API
     createBookOrder(){

      //begin creating the object before sending it to API
      let newBookOrder = {};

        newBookOrder.receiver = this.readUserCookie('user');
        newBookOrder.book = this.book;


       this.axios.post(this.ORDER_API_URL,
           newBookOrder)
           .then((resp) => {
             if(resp.status===201)
             {
               this.success=true;
               this.$emit('newOrderPlaced');
             }

           }) .catch(() =>{
          this.success = false;
       });
      }

  },
  data: function() {
  //simple data messages to display if we are calling the API, or succeeded
  return {
        isBusy: false,
        success: null,
        loggedIn: false,
        activeUser: {}
    }
  },
  mounted(){
  this.activeUser = this.readUserCookie('user');

    if(this.activeUser.id > 0)
    {
    this.loggedIn = true;
    }
  }

}
</script>

<style scoped>

</style>