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

<!--       <form @submit.prevent="makeSearch" class="search-form" method="POST">-->
<!--            <div class="search-group">-->
<!--&lt;!&ndash;                <input type="hidden" name="_token" value>&ndash;&gt;-->
<!--                <input type="search" name="searchquery" v-model="searchquery" class="search-controls">-->
<!--                <button class="btn-search" type="submit">Search</button>-->
<!--            </div>-->
<!--        </form>-->
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
            // makeSearch(){
            //     axios.post(`../api/search`,
            //         {
            //             searchquery:this.searchquery,
            //         },
            //         {
            //             headers:{
            //                 'Authorization' : (localStorage.getItem('accessToken') && !localStorage.getItem('show_public_posts'))? `Bearer ${localStorage.getItem('accessToken')}` : '',
            //                 'Content-Type': 'Application/json',
            //                 'Accept': 'Application/json'
            //             }
            //
            //         }
            //     )
            //     .then(resolve =>{
            //         // location.href=`/result/${resolve.data}`
            //         console.log(resolve)
            //     })
            //     .catch(err=>{
            //             // implement a catch block here
            //             console.log(err)
            //         })
            //
            // }
        },
        created() {
            this.accessToken = localStorage.accessToken ? true :false;
            this.show_public_post = localStorage.getItem('show_public_posts') ? true : false;
        }
    }
</script>
