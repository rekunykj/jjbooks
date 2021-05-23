/**
 * Mixins are a flexible way to distribute reusable functionalities for Vue components.
 * https://vuejs.org/v2/guide/mixins.html
 * The following mixin will only be imported to ALL components in this project (even the 3rd party components)
 * all the following methods and variables will be available to ALL components as this mixin will be imported in main.js
 */
const BOOK_API_URL = 'http://localhost:8003/bookapi.php';
const USER_API_URL = 'http://localhost:8003/userapi.php';
const ORDER_API_URL = 'http://localhost:8003/bookorderapi.php';
const GlobalMixin = ({
    data(){
        return {
            BOOK_API_URL,
            USER_API_URL,
            ORDER_API_URL,
            //this object is used to look up all of the enumerated genres.
            bookDictionary: {
                AA: "Action and Adventure",
                COMBGPN: "Comic Book/Graphic Novel",
                MYS: "Mystery",
                FAN: "Fantasy",
                CLAS: "Classic",
                HF: "Historical Fiction",
                HOR: "Horror",
                ROM: "Romance",
                SCIFI: "Science Fiction",
                SUSTHR: "Suspense/Thriller",
                BIOAUTO: "Biography/Autobiogrpahy",
                COOK: "Cooking",
                HIS: "History",
                TRUCRI: "True Crime",
                OTH: "Other"
            }
        }
    },
    methods: {
        //used to create an options array with key value pairs for select boxes.
        createOptions(){
            let options = [];
            options.push({value: null, text: 'Please select a genre' });
            Object.keys(this.bookDictionary).forEach(key => {
                options.push({value: key, text: this.bookDictionary[key]});
            });

            return options;
        },

        createUserCookie(name, value){
            let cookie = [name, '=', JSON.stringify(value), '; path=/;'].join('');
            console.log(cookie);
            document.cookie = cookie;
            console.log(this.readUserCookie("user"));
        },
        readUserCookie(name){
            let result = document.cookie.match(new RegExp(name + '=([^;]+)'));
            result && (result = JSON.parse(result[1]));
            return result;
        }
    }
});

export default GlobalMixin
