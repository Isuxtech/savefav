<template>
    <div class="register-wrapper">
        <h3 class="access-title">Register</h3>
        <form class="register-form" autocomplete="off" @submit.prevent="registerUser">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group">
                <label for="fullname">Fullname</label>
                <input type="name" name="fullname" class="form-controls" v-model="fullname" required>
                <span v-if="myErrors.fullname" v-text="myErrors.fullname" :class="`is-invalid`"></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="james@gmail.com" class="form-controls" v-model="email" required>
                <span v-if="myErrors.email" v-text="myErrors.email" :class="`is-invalid`"></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="" class="form-controls" v-model="password" required>
                <span v-if="myErrors.password" v-text="myErrors.password" :class="`is-invalid`"></span>
            </div>
            <button type="submit" name="register" class="btn-register">
                <span class="btn-register--effect">Regi</span>ster
            </button>
        </form>

        <span class="login-link">Have an account? <a href="" class="login-link-a">Log In</a></span>

        <div class="useful-link">
            <a href="">About</a>
            <a href="/">Home</a>
        </div>
    </div>
</template>

<script>
    export default {
         props:['csrf'],
        data(){
            return {
                fullname:null,
                email:null,
                password:null,
                accessToken : null,
                myErrors:{

                }
            }
        },
        methods:{
            registerUser(){
                axios.post('../api/register',{
                    fullname:this.fullname,
                    email:this.email,
                    password:this.password,
                })
                .then(resolve=>{
                    this.accessToken = resolve.data.accessToken;
                    if(localStorage.getItem('accessToken') ){
                        localStorage.removeItem('accessToken');
                    }
                    localStorage.setItem('accessToken', this.accessToken);
                    location.href="/dashboard";
                })
                .catch(err=>{
                    console.log(err)
                       this.myErrors = err.data.errors;
                })
            }
        },
    }
</script>
