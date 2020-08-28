<template>
    <div class="create-wrapper">
        <h3 class="access-title">Add Link</h3>
        <form class="register-form" autocomplete="off" @submit.prevent="createLink">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group">
                <label for="title">Name</label>
                <input type="text" name="title" class="form-controls" v-model="title">
                <span v-if="myErrors.title" v-text="myErrors.title" :class="`is-invalid`"></span>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="" class="form-controls" v-model="category">
                    <option v-for="value in all_cat" :value="value.cat_id" v-text="value.category_name"></option>
                </select>
                 <span v-if="myErrors.category" v-text="myErrors.category" :class="`is-invalid`"></span>
            </div>
            <div class="form-group">
                <label for="url">url (site address)</label>
                <input type="text" name="url" class="form-controls" v-model="url">
                <span v-if="myErrors.url" v-text="myErrors.url" :class="`is-invalid`"></span>
            </div>
            <div class="form-group-checkbox" v-if="accessToken">
                <input type="checkbox" v-model="access_type" id="access_type">
                <label for="access_type"></label>Link should be private
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" rows="8" class="form-controls" v-model="description">
                </textarea>
                <span v-if="myErrors.description" v-text="myErrors.description" :class="`is-invalid`"></span>
            </div>

            <button type="submit" name="register" class="btn-register">
                <span class="btn-register--effect">Save</span>
            </button>
        </form>
    </div>
</template>

<script>
    export default{
        props:['csrf', 'categories'],
        data(){
            return{
                title:null,
                url:null,
                description:null,
                category:null,
                access_type:null,
                all_cat:null,
                accessToken:null,
                myErrors:{},
            }
        },
        methods:{
            createLink(){
                let axiosSend;
                if(!this.accessToken){
                    axiosSend = axios.post('../api/dashboard/createPublic',{
                        title:this.title,
                        description:this.description,
                        category:this.category,
                        url:this.url,
                        access_type:1,
                    });
                }else{
                    axiosSend = axios.post('../api/dashboard/create',{
                        title:this.title,
                        description:this.description,
                        category:this.category,
                        url:this.url,
                        access_type:this.access_type? '0':'1',
                    },{
                        headers:{
                            'Authorization' : `Bearer ${localStorage.getItem('accessToken')}`,
                            'Content-Type': 'Application/json',
                            'Accept': 'Application/json'
                        },
                    });
                }
                axiosSend
                .then(resolve=>{
                    console.log(resolve.data)
                    location.href="/"
                })
                .catch(err=>{
                    console.log('this is an error',err)
                })
            }
        },
        mounted() {
            this.all_cat = JSON.parse(this.categories) ;
            this.accessToken = localStorage.getItem('accessToken')
        }
    }
</script>
