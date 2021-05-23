<template>
<div>
  <h2>Filter By:</h2>
  <b-form-group>
    <b-input-group>
      <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.gn">
        <b-icon-book-fill v-bind:title="dt.gn"/>
      </b-input-group-prepend>
      <b-form-select v-model="genre" :options="options" @change="sendGenre"
      ></b-form-select>
    </b-input-group>
  </b-form-group>

  <b-form-group>
    <b-input-group>
      <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.av">
        <b-icon-check v-bind:title="dt.av"/>
      </b-input-group-prepend>
      <b-form-select v-model="filterIsAvailable" :options="availOptions" @change="sendAvailFilter"
      ></b-form-select>
    </b-input-group>
  </b-form-group>

  <b-form-group>
    <b-input-group>
      <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.ti">
        <b-icon-book v-bind:title="dt.ti"/>
      </b-input-group-prepend>
      <b-form-input
          type="text"
          v-bind:placeholder="dt.ti"
          v-model="titleFilter"
          trim
        @change="sendTitleFilter"
      >
      </b-form-input>
    </b-input-group>
  </b-form-group>
</div>
</template>

<script>
export default {
name: "FilterBox",
  data: function() {
  return {
    //placeholder text strings
    dt: {
      gn: "Genre",
      av: "Available",
      ti: "Search for a title"
    },
    //set up null values initially, default available to true
    genre: null,
    filterIsAvailable: true,
    titleFilter: "",
    //options array created on mount to display select box values
    options: [],
    //manual creation of available/unavailable select box values
    availOptions: [
      { value: true, text: 'Available' },
      { value: false, text: 'Unavailable' }
    ]
  }
  },
  methods: {
  //functions to emit new filters to parent component
   sendGenre: function() {
     this.$emit("newGenre", this.genre);
   },
    sendAvailFilter: function() {
      this.$emit("newAvailableFilter", this.filterIsAvailable);
    },
    sendTitleFilter: function() {
      this.$emit("newTitleFilter", this.titleFilter);
    }
  },
  mounted() {
  //set up the genre select box on load
    this.options = this.createOptions();
    this.options[0].text = "All Genres";
  }
}
</script>