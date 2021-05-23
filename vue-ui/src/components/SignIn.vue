<template>
  <div>
    <!--   User Name-->

    <b-card-header>Sign in:</b-card-header>
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.un">
          <b-icon-person-badge-fill v-bind:title="dt.un"/>
        </b-input-group-prepend>
        <b-form-input type="text" v-bind:placeholder="dt.un" v-model="tempUser.userName">
        </b-form-input>
      </b-input-group>
    </b-form-group>

    <!--   Password-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.pw">
          <b-icon-lock-fill v-bind:title="dt.pw"/>
        </b-input-group-prepend>
        <b-form-input type="password" v-bind:placeholder="dt.pw" v-model="tempUser.password">
        </b-form-input>
      </b-input-group>
    </b-form-group>

    <b-alert v-if="success === false" variant="danger" show>
      Unable to log in, please try again. Check your password and username. If you have not signed up use the link below!
    </b-alert>

    <p>Not a member? Click <b-link href="#" @click="swapForms()">Here</b-link> to sign up!</p>

    <b-button-group>
      <b-button variant="primary" class="mr-1" @click="logUserIn">
        <b-icon-upload/> Sign in
      </b-button>
    </b-button-group>
  </div>
</template>

<script>
export default {
name: "SignIn",
  data: function() {
    return {
      dt: {
        un: "User Name",
        pw: "Password"
      },
      tempUser: {},
      verifiedUser: {},
      success: null
    }
  },
  methods: {
    swapForms: function() {

      this.$emit("change_form", true);
    },
    logUserIn: function() {
      this.axios.get(this.USER_API_URL,
          {params:{username:this.tempUser.userName, password:this.tempUser.password}})
          .then((resp) => {
            this.verifiedUser = resp.data;

            if(this.verifiedUser.id !== null) {
              this.$emit('userSignedIn', this.verifiedUser);
              this.swapForms();
            }
          })
          .catch((err) =>{
            if(err.response){
              console.log(err.response);
              this.success = false;
            }
          });

    }
  }
}
</script>