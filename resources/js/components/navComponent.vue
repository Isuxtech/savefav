<template>
    <nav>
        <div class="brand">
            <h2 class="logo"><a href="/" style="color:white;">SaveFav</a></h2>
<!--            <div class="hamburger-wrapper">-->
<!--                <div class="hamburger"></div>-->
<!--                <div class="hamburger"></div>-->
<!--                <div class="hamburger"></div>-->
<!--            </div>-->
        </div>

       <form @submit.prevent="makeSearch" class="search-form" method="POST">
            <div class="search-group">
<!--                <input type="hidden" name="_token" value>-->
                <input type="search" name="searchquery" v-model="searchquery" class="search-controls" @focus="removeSearchError" required>
                <button class="btn-search" type="submit">Search</button>
            </div>
           <span v-show="searchError" style="font-size:0.9rem">Cannot be less than 3</span>
        </form>
        <div class="navbar">

            <a href="/create" class="navlink"> <span style="font-size:1.6rem">+ &thinsp;</span> |</a>
            <a href="" class="navlink" v-if="accessToken && !show_public_post" @click="showPublicPosts"> General Posts |</a>
            <a href="" class="navlink" v-if="accessToken && show_public_post" @click="hidePublicPosts"> My Posts |</a>

            <a href="/login" class="navlink" v-show="!accessToken">Login </a>
            <a href="" class="navlink" v-show="accessToken" @click.prevent="logout">logout </a>
<!--            <a href="" class="navlink">About</a>-->
<!--            <a href="" class="navlink">Contact</a>-->
        </div>
    </nav>
</template>

<script>
    export default {
        data(){
            return{
                accessToken:null,
                show_public_post:true,
                searchquery:"",
                searchError:null,
            }
        },
        methods:{
            logout(){
                axios.post('../api/logout',{},{
                    headers:{
                        'Authorization' : `Bearer ${localStorage.getItem('accessToken')}`,
                        'Accept': 'Application/json'
                    },
                })
                .then(resolve =>{
                     localStorage.removeItem('accessToken');

                })
                .finally(()=>{
                    localStorage.removeItem('show_public_posts');
                    location.href='/login';
                })
            },
            showPublicPosts(){
                localStorage.setItem('show_public_posts',true);
                this.show_public_post = true;
            },
            hidePublicPosts(){
                localStorage.removeItem('show_public_posts');
                this.show_public_post =false;

            },
            makeSearch(){
                if(this.searchquery.trim('').length >2){
                    this.$emit('search',this.searchquery)
                }else{
                    this.searchError = true
                }
            },
            removeSearchError(){
                this.searchError = false
            }
            ,
        },
        created() {
            this.accessToken = localStorage.accessToken ? true :false;
            this.show_public_post = localStorage.getItem('show_public_posts') ? true : false;
        }
    }
</script>
