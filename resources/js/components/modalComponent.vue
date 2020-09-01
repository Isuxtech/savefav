<template>
        <section class="modal-container">
           <div class="modal-wrapper">
               <div class="modal">
                   <div class="modal-group">
                       <label for="" class="title">Name</label>
                       <p class="content-wrapper">{{linkInfo.title}}</p>
                   </div>
                   <div class="modal-group">
                       <label for="" class="title">Url:</label>
                       <a :href="linkInfo.url" target="_blank" class="content-wrapper link">{{linkInfo.url}}</a>
                   </div>
                   <div class="modal-group">
                       <label for="" class="title">date created:</label>
                       <p class="content-wrapper ">{{linkInfo.created_at}}</p>
                   </div>
                   <div class="modal-group">
                       <label for="" class="title">Category</label>
                       <p class="content-wrapper ">{{linkInfo.category.category_name}}</p>
                   </div>
                   <div class="modal-group">
                       <label for="" class="title">Description</label>
                       <p class="content-wrapper ">{{linkInfo.description}}</p>
                   </div>
<!--                   <div class="modal-group">-->
<!--                       <label for="" class="title">last viewed</label>-->
<!--                       <p class="content-wrapper ">last viewed</p>-->
<!--                   </div>-->
               </div>

               <div class="close-menu" @click="btnCloseModal">
                   <span class="add-close-icon">X</span>
                   <strong class="add-text">Close</strong>
               </div>
               <div class="side-menu">
                   <a href="/create"  class="icon-wrapper add" >
                       <span class="add-icon">+</span>
                       <strong class="add-text">New</strong>
                   </a>
                   <a href="/" class="icon-wrapper remove" @click.prevent="deleteLink" v-show="show_btn_delete">
                       <span class="add-icon">-</span>
                       <strong class="add-text">Del</strong>
                   </a>
               </div>
           </div>
        </section>
</template>

<script>
    export default{
        props:['link_id','publisher'],

        data(){
            return{
                logedIn:null,
                show_btn_delete:false,
             linkInfo:{
                 category:[]
             },

            }
        },
        methods:{
            btnCloseModal(){
                this.$emit('modal:close');
            },
            deleteLink(){
                let delete_confirmation = confirm(' Are you sure?');
                if(!delete_confirmation){
                    return false;
                }
                axios.delete(`../api/dashboard/delete/${this.link_id}`,
                    {
                        headers:{
                            'Authorization' : `Bearer ${localStorage.getItem('accessToken')}`,
                            'Content-Type': 'Application/json',
                            'Accept': 'Application/json'
                        },
                    },

                )
                    .then(resolve =>{
                        // this.linkInfo = resolve.data;
                        alert(resolve.data);
                        location.href="/"
                    })
                    .catch(err =>{
                        console.log(err)
                    })

            },

            /***
             * @return boolean
             * only works you my posts and not general
             * compares the user_id and the publisher id and return true
             * which helps to display the delete button
             */
            showBtnDelete(){
                return this.publisher == this.linkInfo.user_id
            }
        },
        mounted() {

            this.logedIn = localStorage.getItem('accessToken')
            let axios_request;
            if(localStorage.getItem('accessToken')){
                axios_request =  axios.get(`../api/dashboard/getInfo/${this.link_id}`,
                    {
                        headers:{
                            'Authorization' : `Bearer ${localStorage.getItem('accessToken')}`,
                            'Content-Type': 'Application/json',
                            'Accept': 'Application/json'
                        }

                    }
                )
            }else{
                axios_request =  axios.get(`../api/dashboard/getPublicInfo/${this.link_id}`)
            }
            axios_request.then(resolve =>{
                    this.linkInfo = resolve.data;

                this.show_btn_delete = this.showBtnDelete()
                })
                .catch(err =>{
                    console.log(err)
                })
        }
    }

</script>
