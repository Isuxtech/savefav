<template>
    <section>
        <div class="pagination-wrapper">
            <span class="pagelabel"> page:</span>
            <a @click.prevent="movePage" :href="linkContent.prev_page_url" v-if="linkContent.prev_page_url" class="moveLeft"> < </a>
            <span v-if="linkContent.current_page" class="page_number">  {{linkContent.current_page}} </span>
            <a @click.prevent="movePage" :href="linkContent.next_page_url" v-if="linkContent.next_page_url" class="moveRight"> > </a>
        </div>
        <div class="wrapper">
            <div class="wrapper-links" v-for="links in linkContent.data">
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
        props:{
            'result': String
        },
        data(){
            return{
                active_link_id:null,
                linkContent:{},
                show_modal: false,
                publisher:null
            }
        },
        // computed:{
        //     getResult(){
        //         axios.get('../api/publicPosts')
        //             .then(resolve =>{
        //                 this.linkContent = resolve.data.savedlink;
        //                 this.publisher = resolve.data.publisher;
        //                 /***
        //                  * the map is used to add the 3 dots at the end of the description
        //                  * it loops through the entire result, selects 100 character and adds the ... at the end
        //                  */
        //                 this.linkContent.data.map(currentValue=>{
        //                     currentValue.description  = `${currentValue.description.substring(0,120).trim()} ...`;
        //                 })
        //             })
        //     }
        // },
        // methods:{
        //     movePage(event){
        //         let pageinfo = event.target;
        //         console.log(`..${pageinfo.pathname+pageinfo.search}`)
        //         axios.get(`..${pageinfo.pathname+pageinfo.search}`,
        //             {
        //                 headers:{
        //                     'Authorization' : (localStorage.getItem('accessToken') && !localStorage.getItem('show_public_posts'))? `Bearer ${localStorage.getItem('accessToken')}` : '',
        //                     'Content-Type': 'Application/json',
        //                     'Accept': 'Application/json'
        //                 }
        //
        //             }
        //         )
        //             .then(resolve =>{
        //                 this.linkContent = resolve.data.savedlink;
        //                 this.publisher = resolve.data.publisher;
        //                 /***
        //                  * the map is used to add the 3 dots at the end of the description
        //                  * it loops through the entire result, selects 100 character and adds the ... at the end
        //                  */
        //                 this.linkContent.data.map(currentValue=>{
        //                     currentValue.description  = `${currentValue.description.substring(0,120).trim()} ...`;
        //                 })
        //             })
        //             .catch(err=>{
        //                 // implement a catch block here
        //                 console.log(err)
        //             })
        //     },
        //     activeLinkID(element){
        //         this.active_link_id = element.target.dataset.link;
        //         this.show_modal = true;
        //     },
        //     showModal(){
        //         this.show_modal = false;
        //     }
        // },

        components:{
            detailsModal
        },
        mounted() {
            // if(localStorage.getItem('accessToken') && !localStorage.getItem('show_public_posts')){
            //     this.defaultValues
            // }else{
            //     this.getPublic
            // }
            console.log(this.result)

        }
    }
</script>
