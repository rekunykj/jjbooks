<template>
  <div>
  <h1 class="mr-4">List a book</h1>

    <b-overlay :show="isDisabled || !loggedIn" opacity="0.25">
<!--    title input-->
  <b-form-group>
    <b-input-group>
      <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.ti">
        <b-icon-book v-bind:title="dt.ti"/>
      </b-input-group-prepend>
      <b-form-input
          type="text"
          v-bind:placeholder="dt.ti"
          v-model="$v.fields.title.$model"
          :state="validateState('title')"
          aria-describedby="tifb"
          trim
      >
      </b-form-input>
      <b-form-invalid-feedback
          id="tifb"
      >A title must be provided for this book and cannot be longer than 30 characters long.</b-form-invalid-feedback>
    </b-input-group>
  </b-form-group>

<!--    publisher input-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.pb">
          <b-icon-person-badge-fill v-bind:title="dt.pb"/>
        </b-input-group-prepend>
        <b-form-input
            type="text"
            v-bind:placeholder="dt.pb"
            v-model="$v.fields.publisher.$model"
            :state="validateState('publisher')"
            aria-describedby="pbfb"
            trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="pbfb"
        >A publisher must be provided for this book and be less than 40 characters long.</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

<!--    author input-->
    <b-form-group >
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.au">
          <b-icon-person v-bind:title="dt.au"/>
        </b-input-group-prepend>
        <b-form-tags  mr-3 v-model="$v.fields.authors.$model" is-text v-b-tooltip.hover.right="dt.au" v-bind:placeholder="dt.au" aria-describedby="aufb"
        :state="validateState('authors')"
        ></b-form-tags>
        <b-form-invalid-feedback
            id="aufb"
        >At least one author must be provided for this book.</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

<!--    summary input-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.su">
          <b-icon-chat-dots v-bind:title="dt.su"/>
        </b-input-group-prepend>
      <b-textarea type="text"
                  v-bind:placeholder="dt.su"
                  v-model="$v.fields.summary.$model"
                  :state="validateState('summary')"
                  aria-describedby="sufb"
                  trim>

      </b-textarea>
        <b-form-invalid-feedback
            id="sufb"
        >You must provide a brief summary (less than 140 characters) of this book.</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

<!--    genre input-->
      <b-form-group>
        <b-input-group>
          <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.gn">
            <b-icon-bookmark v-bind:title="dt.gn"/>
          </b-input-group-prepend>
          <b-form-select v-model="$v.fields.genre.$model" :options="options"
                         :state="validateState('genre')"
                         aria-describedby="gnfb"
                         trim
          ></b-form-select>
          <b-form-invalid-feedback
              id="gnfb"
          >You must list the genre of this book</b-form-invalid-feedback>
        </b-input-group>
      </b-form-group>


<!--    submit and reset buttons-->
    <b-button-group>
      <b-button variant="primary" @click="addBook" class="mr-1">
        <b-icon-upload/> List Book
      </b-button>
      <b-button variant="primary" @click="clearForms">
        <b-icon-x-square-fill/> Reset Form
      </b-button>
    </b-button-group>
    </b-overlay>
    <b-alert variant="success" dismissible :show="newBookAdded===true" >{{book.title}} has been added!!</b-alert>
    <b-alert variant="success" dismissible :show="newBookAdded===false" >Something went wrong adding {{book.title}}. Please try again later!</b-alert>
    <b-alert variant="danger"  :show="loggedIn===false" >You must log in to add a book.</b-alert>
  </div>
</template>

<script>
import { required, minLength, maxLength } from 'vuelidate/lib/validators';

export default {
name: "BookForm",
  data: function() {
  return {

    //placeholder text strings
    dt: {
      ti: "Title",
      pb: "Publisher",
      au: "Author(s)",
      su: "Summary",
      gn: "Genre"
    },
    //models for our object
    fields: {
      title: "",
      authors: [],
      summary: "",
      publisher: "",
      genre: null

    },
    //select box created on load for genre
    options: [],
    //flag to see if a new book was added recently
    newBookAdded: null,
    //book reference
    book: {},
    //check to see if we display overlay
    isDisabled: false,
    activeUser: {},
    loggedIn: false
  }
  },

  //vuelidate validation rules https://vuelidate.js.org/#sub-basic-form
  validations: {
    fields: {
      title: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(30)
      },
      authors: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(5)

      },
      summary : {
        required,
        maxLength: maxLength(140)
      },
      publisher: {
        required,
        maxLength: maxLength(40)
      },
      genre: {
        required,
        maxLength: maxLength(20)
      }
    }
  },
  methods: {
  //helper method to validate with vuelidate
    validateState(name) {
      const { $dirty, $error } = this.$v.fields[name];
      return $dirty ? !$error : null;
    },
    //method to clear form
    clearForms: function() {
      this.fields = {
        title: "",
        authors: [],
        summary: "",
        publisher: "",
        genre: null
      };
      //reset vuelidate object
      this.$nextTick(() => {
        this.$v.$reset();
      });
    },
    addBook: function() {
      //final check to see if book is valid
      this.$v.fields.$touch();

      if (!this.$v.fields.$anyError && this.activeUser.id > 0) {
          this.postBook();
      }



    },
    postBook(){
      //enable overlay
      this.isDisabled = true;

      //setup book object to JSON format
      let newBook = {};
      newBook.title = this.fields.title;
      newBook.genre = this.fields.genre;
      newBook.publisher = this.fields.publisher;
      newBook.authors = this.fields.authors;
      newBook.bookSummary = this.fields.summary;
      newBook.isAvailable = true;
      newBook.owner = this.activeUser;

      this.axios.post(this.BOOK_API_URL,
          newBook)
          .then((resp) => {
            console.log(resp.data);
            //assign book
            this.book = newBook;
            this.newBookAdded = true;

            //clear form tell parent component to refresh table
            this.clearForms();
            this.$emit('refreshTable');
          })
          .catch((err) =>{
            if(err.response.status){
              this.newBookAdded = false;
            }
          }).finally(()=>{
            //disabled overlay
        this.isDisabled = false;
      });
    }
  },
  mounted(){
  //create initial genre select box
    this.options = this.createOptions();
    this.activeUser = this.readUserCookie("user");
    if(this.activeUser.id > 0)
    {
      this.loggedIn = true;
    }
  }


}
</script>