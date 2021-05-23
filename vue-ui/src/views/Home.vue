<template>
  <div>
    <header><img alt="Library logo" src="../assets/TheLibraryHeader2.png" class="img-fluid"></header>
    <div class="col-12">
    <AboutUs></AboutUs>
    <div class="row">
      <img  class="col-md-8 col-lg-7 order-md-1 img-fluid rounded float-left" alt="Library Image" src="../assets/10325404_1190587370959203_4900351276516855581_n_LIBRARY_IMAGE1.jpg">
      <UserForm v-if="displayRegister && loggedInUser.id == null" class="col-md-4 col-lg-5 order-md-0" @change_form="handleSwap" @newUser="updateNewUser"/>
      <SignIn v-if="displayRegister===false && loggedInUser.id == null" class="col-md-4 col-lg-5 order-md-0" @change_form="handleSwap" @userSignedIn="handleUserLogin"></SignIn>
      <div v-if="loggedInUser.id > 0 || newUser===true" class="col-md-4 col-lg-5 order-md-0">
        <h2>Welcome back! {{loggedInUser.firstName}}</h2>
        <p>Thank you for registering for our service!</p>
      </div>
    </div>
    </div>
  </div>
</template>

<script>

import UserForm from "@/components/UserForm";
import AboutUs from "@/components/AboutUs";
import SignIn from "@/components/SignIn";
export default {
  name: 'Home',
  components: {SignIn, AboutUs, UserForm},
  data: function() {
    return {
      //flag for dislaying sign in or reigster
      displayRegister: true,
      //object to store logged in user
      loggedInUser: {},
      newUser: false
    }
  },
  methods: {
    //function to handle the switch between log in and register
    handleSwap: function(bool){
      this.displayRegister = bool;
    },
    //function that checks if the user has an ID after logging in.
    updateNewUser: function(user){
      if(user.id != null && user.id > 0)
      {
        this.loggedInUser = user;
        this.newUser = true;
        this.createUserCookie("user", this.loggedInUser);

      }

    },
    //simply assigns user logged in from the sign in component
    handleUserLogin: function(user) {
      this.loggedInUser = user;
      this.createUserCookie("user", this.loggedInUser);


    }


  }
}

</script>
