<template>
   <section>
       <div class="wrapper">
           <div class="wrapper-links" v-for="links in linkContent">
               <a href="" class="link-title link-text" v-text="links.title" @click.prevent="activeLinkID" :data-link="links.id"></a>
               <div class="description-wrapper">
                   <p class="link-content" v-text="links.description"></p>
               </div>
               <span class="fixed-cat"> Category: <a :href="`/category/${links.category.cat_id}`" v-text="links.category.category_name" class="link"></a></span>
           </div>
       </div>
       <detailsModal v-bind:link_id="active_link_id" v-if="show_modal" @modal:close="showModal" v-bind:publisher="publisher"></detailsModal>
   </section>
</template>

<script>
    import detailsModal from './modalComponent';

    export default {
        data(){
            return{
                active_link_id:null,
                linkContent:{},
                show_modal: false,
                publisher:null
            }
        },
        computed:{
            defaultValues(){
                axios.get('../api/dashboard',
                    {
                        headers:{
                            'Authorization' : `Bearer ${localStorage.getItem('accessToken')}`,
                            'Content-Type': 'Application/json',
                            'Accept': 'Application/json'
                        }

                    }
                )
                .then(resolve =>{
                    this.linkContent = resolve.data.savedlink;
                    this.publisher = resolve.data.publisher;
                    /***
                     * the map is used to add the 3 dots at the end of the description
                     * it loops through the entire result, selects 100 character and adds the ... at the end
                     */
                     this.linkContent.map(currentValue=>{
                         currentValue.description  = `${currentValue.description.substring(0,120).trim()} ...`;
                    })
                })
                .catch(err=>{
                    // implement a catch block here
                })
            },
            getPublic(){
                axios.get('../api/publicPosts')
                    .then(resolve =>{
                        this.linkContent = resolve.data.savedlink;
                        this.publisher = resolve.data.publisher;
                        /***
                         * the map is used to add the 3 dots at the end of the description
                         * it loops through the entire result, selects 100 character and adds the ... at the end
                         */
                        this.linkContent.map(currentValue=>{
                            currentValue.description  = `${currentValue.description.substring(0,120).trim()} ...`;
                        })
                    })
             }
        },
        methods:{
            activeLinkID(element){
                this.active_link_id = element.target.dataset.link;
                this.show_modal = true;
            },
            showModal(){
                this.show_modal = false;
            }
        },

        components:{
            detailsModal
        },
        mounted() {
            if(localStorage.getItem('accessToken') && !localStorage.getItem('show_public_posts')){
                this.defaultValues
            }else{
                this.getPublic
            }

        }
    }
</script>
