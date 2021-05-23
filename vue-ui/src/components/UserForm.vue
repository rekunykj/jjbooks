<template>
  <div>
    <b-overlay :show="isDisabled" opacity="0.25">
    <div>
      <h1 class="mr-4">Register</h1>
      <p>All fields below are required.</p>

      <h2>Login Information</h2>
      <p>This information will be used to login to our service so you can add, and check out new books!</p>
      <!--   User Name-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.un">
          <b-icon-person-badge-fill v-bind:title="dt.un"/>
        </b-input-group-prepend>
        <b-form-input
            type="text"
            v-bind:placeholder="dt.un"
            v-model="$v.fields.username.$model"
            :state="validateState('username')"
            aria-describedby="unfb"
            trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="unfb"
        >Username is a required field and must be less than 25 characters.</b-form-invalid-feedback>
        <b-form-invalid-feedback force-show
        >{{$data.errorMessage}}</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>


    <!--   Password-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.pw">
          <b-icon-lock-fill v-bind:title="dt.pw"/>
        </b-input-group-prepend>
        <b-form-input type="password" v-bind:placeholder="dt.pw"
                      v-model="$v.fields.password.$model"
                      :state="validateState('password')"
                      aria-describedby="pwfb"
                      trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="pwfb"
        >Password is a required field and must be at least 8 characters.</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

    <!--   Confirm Password-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.pwc">
          <b-icon-lock-fill v-bind:title="dt.pwc"/>
        </b-input-group-prepend>
        <b-form-input type="password" v-bind:placeholder="dt.pwc"  v-model="$v.fields.confirmPassword.$model"
                      :state="validateState('confirmPassword')"
                      aria-describedby="cfpw"
                      trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="cfpw"
        >Passwords must match</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

    </div>
    <!--   First Name-->
    <h2>Contact Information</h2>
    <p>This information will be shared with other users so that you can contact each other to share books.</p>

    <div>
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.fn">
          <b-icon-person v-bind:title="dt.fn"/>
        </b-input-group-prepend>
        <b-form-input type="text" v-bind:placeholder="dt.fn"  v-model="$v.fields.firstName.$model"
                      :state="validateState('firstName')"
                      aria-describedby="fnfb"
                      trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="fnfb"
        >First Name is a required field and must be less than 40 characters long.</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

    <!--   Last Name-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.ln">
          <b-icon-people-fill v-bind:title="dt.ln"/>
        </b-input-group-prepend>
        <b-form-input type="text" v-bind:placeholder="dt.ln" v-model="$v.fields.lastName.$model"
                      :state="validateState('lastName')"
                      aria-describedby="lnfb"
                      trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="lnfb"
        >Last Name is a required field and must be less than 35 characters long..</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>


    <!--   Address-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.sa">
          <b-icon-house-fill v-bind:title="dt.sa"/>
        </b-input-group-prepend>
        <b-form-input  type="text" v-bind:placeholder="dt.sa" v-model="$v.fields.streetAddress.$model"
                       :state="validateState('streetAddress')"
                       aria-describedby="safb"
                       trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="safb"
        >Street Address is a required field and must be less than 100 characters long..</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

    <!--   Email-->
    <b-form-group>
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.ea">
          <b-icon-globe v-bind:title="dt.ea"/>
        </b-input-group-prepend>
        <b-form-input type="email" v-bind:placeholder="dt.ea" v-model="$v.fields.emailAddress.$model"
                      :state="validateState('emailAddress')"
                      aria-describedby="eafb"
                      trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="eafb"
        >A valid email address is required</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

    <!--   Phone Number-->
    <b-form-group id="phone-group">
      <b-input-group>
        <b-input-group-prepend is-text v-b-tooltip.hover.right="dt.pn">
          <b-icon-phone-fill v-bind:title="dt.pn"/>
        </b-input-group-prepend>
        <b-form-input type="tel" v-bind:placeholder="dt.pn" v-model="$v.fields.phoneNum.$model"
                      :state="validateState('phoneNum')"
                      aria-describedby="pnfb"
                      trim
        >
        </b-form-input>
        <b-form-invalid-feedback
            id="pnfb"
        >A phone number is required and cannot be longer than 15 characters.</b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>

      <b-form-group>
        <b-input-group>
          <b-form-checkbox @change="$v.fields.hasConsented.$touch()"  ref="confirmBox" class="mr-3"
                           v-model="fields.hasConsented"
                           :state="validateState('hasConsented')"
                           aria-describedby="hcfb">{{dt.consent}}</b-form-checkbox>
          <b-form-invalid-feedback
              id="hcfb"
          >You must consent to terms and services.</b-form-invalid-feedback>
        </b-input-group>
      </b-form-group>

      <p>Already a member? Click <b-link href="#" @click="swapForms()">Here</b-link> to sign in</p>

    </div>

    </b-overlay>

    <b-button-group>
      <b-button variant="primary" @click="saveNewUser" class="mr-1">
        <b-icon-upload/> Register
      </b-button>
      <b-button variant="primary" @click="clearForms">
        <b-icon-x-square-fill/> Reset Form
      </b-button>
    </b-button-group>
  </div>

</template>

<script>
import { required, minLength, maxLength, email, sameAs, helpers } from 'vuelidate/lib/validators';
const phoneRegex = helpers.regex('alpha', /(\d\D*){10}/)

export default {
name: "UserForm",
  props: {
  user:{
    type:Object
  }
  },
  data: function() {
  return {
    //placeholder strings
    dt: {
      ln: "Last Name",
      fn: "First Name",
      sa: "Street Address",
      pn: "Phone Number",
      ea:"Email Address",
      un: "User Name",
      pw: "Password",
      pwc: "Confirm Password",
      consent: "I consent to have the above personal information shared with other users of this service."
    },
    //models for the user object
    fields: {
      lastName: "",
      firstName: "",
      streetAddress: "",
      phoneNum: "",
      emailAddress: "",
      username: "",
      password: "",
      confirmPassword: "",
      hasConsented: false
    },
    //temporary user object
    tempUser: {},
    //checks to disable the or enable the overlay
    isDisabled: false,
    //string that will display error messages from server if user name exists in databse
    errorMessage: "",
    //flag to show said error message
    showUserNameError: false
  }
  },
  //validation rules for vuelidate https://vuelidate.js.org/#sub-basic-form
  validations: {
  fields: {
  lastName: {
    required,
    minLength: minLength(1),
    maxLength: maxLength(35)},
    firstName: {
      required,
      minLength: minLength(1),
      maxLength: maxLength(40)

    },
    streetAddress: {
      required,
      minLength: minLength(1),
      maxLength: maxLength(100)},
    phoneNum: {
      required,
      minLength: minLength(1),
      maxLength: maxLength(15),
      phoneRegex
    },
    emailAddress: {
      required,
      minLength: minLength(1),
      maxLength: maxLength(40),
      email
    },
    username: {
      required,
      minLength: minLength(1),
      maxLength: maxLength(25)},
    password: {
      required,
      minLength: minLength(8)},
    confirmPassword: {
    confirmPassword: sameAs('password'),
      required
    },
    hasConsented: {
      checked(val) {
        return val;
      }
    }
  }
  },
  methods: {
  //function to begin the validation process
  saveNewUser: function() {
    this.checkform();
  },
    //check all fields with vuelidate
    checkform: function()
    {
      this.$v.fields.$touch();

      if (!this.$v.fields.$anyError) {
        this.postNewUser();
      }

    },
    //helper method to check for any errors called on each field.
    validateState(name) {
      const { $dirty, $error } = this.$v.fields[name];
      return $dirty ? !$error : null;
    },
    //clears forms
    clearForms: function() {
    this.fields = {
            lastName: "",
            firstName: "",
            streetAddress: "",
            phoneNum: "",
            emailAddress: "",
            username: "",
            password: "",
            confirmPassword: "",
            hasConsented: false,
      };

    //manual reset of user name duplicate error
      this.showUserNameError = false;

      //this code clears the vuelidate object to ensure ererors are also cleared.
      this.$nextTick(() => {
        this.$v.$reset();
      });
    },
    //tells the parent component to swap forms
    swapForms: function() {
    this.$emit("change_form", false);
    },
    postNewUser() {

    //converting our models into the object matching expected json.
    let newUser = {};
    newUser.username = this.fields.username;
    newUser.password = this.fields.password;
    newUser.firstName = this .fields.firstName;
    newUser.lastName = this.fields.lastName;
    newUser.address = this.fields.streetAddress;
    newUser.email = this.fields.emailAddress;
    newUser.phone = this.fields.phoneNum;

    //post request
      this.axios.post(this.USER_API_URL,
          newUser)
          .then((resp) => {
            //get the id from the response and pass it to parent component to confirm user was created.
            newUser.id = resp.data;
            this.$emit('newUser', newUser);
            this.clearForms();
          })
          .catch((err) =>{
            if(err.response){
              //manual handling of username duplicate error display
              this.fields.username = "";
              this.showUserNameError = true;
              this.errorMessage = err.response.data.errorMessage + " Invalid username: " + newUser.username;
            }
          }).finally(()=>{

      });


    }
  }
}
</script>
