<template>
    <div>
        <div class="mb-4">
            <b-button v-b-toggle.pendingOrders>Toggle Pending Orders</b-button>
            <b-button v-b-toggle.currentOrders>Toggle Checked Out Books</b-button>
        </div>
        <ul class="list-group">
            <b-collapse id="pendingOrders">
                <li v-for="order in pendingOrderArray" :key="order.id" class="list-group-item">
                    <b-card v-bind:title="{'bookTitle': order.book.title}" v-bind:sub-title="{'receiverName': order.receiverID.firstname}" border-variant="info">
                        <b-card-text>
                            Requested on: {{ order.checkOutDate }}
                        </b-card-text>

                        <b-card-text>Location: {{ order.receiverID.address }}</b-card-text>

                        <b-collapse id="showMore">
                            <b-card-text>Last name: {{ order.receiverID.lastName }}</b-card-text>
                            <b-card-text>Email: {{ order.receiverID.email }}</b-card-text>
                            <b-card-text>Phone: {{ order.receiverID.phone }}</b-card-text>
                        </b-collapse>

                        <div class="text-center">
                            <b-button pill href="#" variant="success" class="float-left" @click="acceptPendingOrder">Accept</b-button>
                            <b-button pill href="#" variant="info" v-b-toggle.showMore>More Info On Requester</b-button>
                            <b-button pill href="#" variant="danger" class="float-right" @click="declinePendingOrder">Decline</b-button>
                        </div>
                    </b-card>
                </li>
            </b-collapse>
        </ul>
        <ul class="list-group">
            <b-collapse id="currentOrders">
                <li v-for="book in checkedOutBooksArray" :key="book.id" class="list-group-item">
                    <b-card title="Title of Book" sub-title="User who gave you the book" border-variant="info">
                        <b-card-text>
                            Checked in on: DATE HERE
                        </b-card-text>

                        <div class="text-center">
                            <b-button pill href="#" variant="success" class="float-right" @click="returnBook(book)">Return</b-button>
                        </div>
                    </b-card>
                </li>
            </b-collapse>
        </ul>

    </div>
</template>

<script>
    import GlobalMixin from "../mixins/global-mixin";
    export default {
        name: "CompBookOrder",
        mixins:[GlobalMixin],
        data: function() {
            return {
                pendingOrderArray: [],
                checkedOutBooksArray: [],
                currentUser: {}
            }
        },
        methods:{
            getPendingOrders: function () {
              this.axios.get(this.ORDER_API_URL,
                  {params:{id:this.currentUser.id}})
                  .then((resp) => {
                   this.pendingOrderArray = resp.data;
                   console.log(this.pendingOrderArray);
                  })
                  .catch((err) =>{
                    if(err.response){
                      console.log(err.response);
                    }
                  }).finally(()=>{
              });

              console.log("Should have saved");
              console.log(this.pendingOrderArray);
            },
            acceptPendingOrder: function () {

            },
            declinePendingOrder: function () {

            },
            returnBook: function (book) {
                return book;
            }
        },
        mounted() {
            this.currentUser = this.readUserCookie('user');
            this.getPendingOrders(); //make call to get orders when page loads
        }
    }
</script>

<style scoped>

</style>