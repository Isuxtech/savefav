<template>
   <section>
       <nav-component @search="madeSearch"></nav-component>
       <div class="preloader" v-if="show_preloader">
           <img :src="`../imgs/loading.gif`" alt="" class="loader">
       </div>
       <div class="pagination-wrapper" v-if="linkContent.total !=0">
           <span class="pagelabel" > page:</span>
           <a @click.prevent="movePage" :href="linkContent.prev_page_url" v-if="linkContent.prev_page_url" class="moveLeft"> < </a>
           <span v-if="linkContent.current_page" class="page_number">  {{linkContent.current_page}} </span>
           <a @click.prevent="movePage" :href="linkContent.next_page_url" v-if="linkContent.next_page_url" class="moveRight"> > </a>
       </div>
       <div class="wrapper">
           <div  class="wrapper-links" v-for="links in linkContent.data" :key="links.id">

               <a class="description-wrapper"  :href="links.url" target="_blank">
                   <h3 class="link-title link-text" v-text="links.title" ></h3>
                   <p class="link-content" v-text="links.description"></p>
               </a>
               <div class="category-wrapper">
                   <span class="fixed-cat"> {{links.category.category_name}}</span>
<!--                   this is share share icon part-->
                   <div @click="shares(`/shared/${links.id}`,links.title)" :class="`share-wrapper`" v-if="can_share">
                       <img :src="'../imgs/reply-black-18dp.svg'" alt="" :class="`share-icon`">
<!--                       <span :class="`share-text`">share</span>-->
                   </div>
                   <a class="link" @click.prevent="activeLinkID" href="" :data-link="links.id"> Details</a>
               </div>

           </div>

       </div>
       <div v-if="linkContent.total ==0" :class="`empty-result`">
           <!--               <img :src="" alt="">-->
           <h2 :class="`empty-text`">No Result Found</h2>
       </div>
       <detailsModal v-bind:link_id="active_link_id" v-if="show_modal" @modal:close="showModal" v-bind:publisher="publisher"></detailsModal>
   </section>
</template>

<script>
    import detailsModal from './modalComponent';
    import navComponent from "./navComponent";

    export default {
        props:{
            'shared':String
        },
        data(){
            return{
                active_link_id:null,
                linkContent:{},
                show_modal: false,
                publisher:null,
                show_preloader:null,
                can_share:null,
            }
        },
        computed:{
            /***
             * this computed function is called on dashboard load when the user is logged in
             */
            defaultValues(){
                this.generalAxios('../api/dashboard')
            },
            /***
             * this computed function is called on dashboard load when the user is NOT logged in
             */
            getPublic(){
                this.generalAxios('../api/publicPosts')
             },

             isSharedLink(){
                 if(this.shared !=undefined || this.shared !=null){
                     //force a data key
                     let shared_link_object = { 'data':JSON.parse(this.shared) }
                     this.show_modal = true;
                     this.linkContent = shared_link_object;
                     this.publisher = this.linkContent.data[0].user_id;
                     this.active_link_id = this.linkContent.data[0].id;
                     console.log(this.linkContent.data)
                     return true
                 }
                 return false;
             }
        },

        methods:{
            shares(url,title){
                if (navigator.share()){
                    return navigator.share({
                        'title':'sending link from savefav.isuxtech.com',
                        'text':title,
                        'url':url
                    })
                }

            },
            /***
             * @return object
             *
             * @param apiurl
             *
             * @method get
             *
             * @description
             * this function sends a get axios request to the apiurl
             * it uses the status of the accesstoken to send a Authorization
             */
            generalAxios(apiurl){
                this.show_preloader = true
                axios.get(apiurl ,
                    {
                        headers:{
                            'Authorization' : `Bearer ${localStorage.getItem('accessToken') ? localStorage.getItem('accessToken'):null}`,
                            'Content-Type': 'Application/json',
                            'Accept': 'Application/json'
                        }
                    }
                )
                    .then(resolve =>{
                        this.show_preloader = false
                        this.linkContent = resolve.data.savedlink;
                        this.publisher = resolve.data.publisher;
                        /***
                         * the map is used to add the 3 dots at the end of the description
                         * it loops through the entire result, selects 100 character and adds the ... at the end
                         */
                        this.linkContent.data.map(currentValue=>{
                            currentValue.description  = `${currentValue.description.substring(0,120).trim()} ...`;
                        })
                    })
                    .catch(err=>{
                        // implement a catch block here
                    })
            }
            ,
            /***
             * @description
             * this function return the search term from the navComponent which as been $remited
             *
             * @param emittedvalue
             *
             * @default this is the second value form this this.$emit('model:search',searchquery)
             */
            madeSearch(emittedvalue){
                this.show_preloader = true
                axios.post('../api/search',
                    {
                        'searchquery':emittedvalue,
                    },
                    {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('accessToken') ? localStorage.getItem('accessToken') : null}`,
                            'Content-Type': 'Application/json',
                            'Accept': 'Application/json'
                        }
                    }
                )
                    .then(resolve =>{
                        this.show_preloader = false
                        this.linkContent = resolve.data.savedlink;
                        this.publisher = resolve.data.publisher;
                        /***
                         * the map is used to add the 3 dots at the end of the description
                         * it loops through the entire result, selects 100 character and adds the ... at the end
                         */
                        // this.linkContent.map(currentValue=>{
                        //     currentValue.description  = `${currentValue.description.substring(0,120).trim()} ...`;
                        // })
                        // console.log(resolve.data)
                        console.log(resolve.data.savedlink)
                    })
                    .catch(err=>{
                         console.log('implement a catch block here', err)
                    })

                // this.generalAxios(`../api/search/${emittedvalue}`)
            },

            /***
             * @param event
             *
             * @description
             *  moves to the next page of the pagination result from the dashboardController
             */
            movePage(event){
                let pageinfo = event.target;
                this.generalAxios(`..${pageinfo.pathname+pageinfo.search}`)
            },

            /***
             *
             * @param element
             *
             * @description
             *  element is the click event.
             *  This method is called when the use clicks on the 'view more' button
             *  it also makes the model show since it sets the show_model to be true
             *
             */
            activeLinkID(element){
                this.active_link_id = element.target.dataset.link;
                this.show_modal = true;
            },


            showModal(){
                this.show_modal = false;
            }
        },

        components:{
            detailsModal,
            navComponent
        },

        mounted() {

            console.log(this.shared)
            /***
             * determines whether to call the auth:api route of the normal route
             * this.defaultValue is for the auth:api route
             */
            if(!this.isSharedLink) {
                if (localStorage.getItem('accessToken') && !localStorage.getItem('show_public_posts')) {
                    this.defaultValues
                } else {
                    this.getPublic
                }
            }
            /***
             * this determines whether the share button will appear
             * first it checks if the navigator.share() is supportted by the browser
             * then it checks if the user has an access token which shows that the user is logged in
             */
            if(navigator.share() && localStorage.getItem('accessToken')){
                this.can_share = true
            }else{
                this.can_share = false
            }
        },

    }
</script>
