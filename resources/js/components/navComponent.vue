<template>
    <nav>
        <div class="brand">
            <h2><a href="/" style="color:white;">SaveFav</a></h2>
            <div class="hamburger-wrapper">

            </div>
        </div>
        <div class="navbar">
<!--            <a href="" class="navlink">About</a>-->
            <a href="/create" class="navlink"> <span style="font-size:1.4rem">+</span> |</a>
            <a href="" class="navlink" v-if="accessToken && !show_public_post" @click="showPublicPosts"> General Posts |</a>
            <a href="" class="navlink" v-if="accessToken && show_public_post" @click="hidePublicPosts"> My Posts |</a>

            <a href="/login" class="navlink" v-show="!accessToken">Login</a>
            <a href="" class="navlink" v-show="accessToken" @click.prevent="logout">logout </a>
        </div>
    </nav>
</template>

<script>
    export default {
        data(){
            return{
                accessToken:null,
                show_public_post:true,
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
                console.log(typeof this.show_public_post)

                // localStorage.getItem('show_public_posts') ? localStorage.getItem('show_public_posts') :
                // localStorage.setItem('show_public_posts', true);
                // this.show_public_post = localStorage.getItem('show_public_posts');
                // console.log(this.show_public_post)
            },
            hidePublicPosts(){
                localStorage.removeItem('show_public_posts');
                this.show_public_post =false;

            },
            showGeneral(){
                // if(this.accessToken){
                //     this.show_public_post = false;
                // }

            }
        },
        created() {
            this.accessToken = localStorage.accessToken ? true :false;
            this.show_public_post = localStorage.getItem('show_public_posts') ? true : false;
            console.log(this.show_public_post)
        }
    }
</script>
