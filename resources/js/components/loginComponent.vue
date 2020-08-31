<template>
    <div class="register-wrapper">
        <h3 class="access-title">Login</h3>
        <h3 v-if="credentialError" v-text="credentialError" :class="`invalid-credentials`"></h3>
        <form class="register-form" autocomplete="off" @submit.prevent="loginUser">
            <input type="hidden" name="_token" :value="csrf">

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
                <span class="btn-register--effect">lo</span>gin
            </button>
        </form>

        <span class="login-link">Have an account? <a href="/register" class="login-link-a">Register</a></span>

        <div class="useful-link">
            <a href="">About</a>
            <a href="/">Home</a>
        </div>
    </div>
</template>

<style scoped>
    .invalid-credentials{
        color: rgba(231, 52, 52, 0.89);
        padding:10px;
        width:100%;
        text-align: center;
        font-size:1rem;
    }
    .is-invalid{
        color: rgba(231, 52, 52, 0.89);
        font-size:0.8rem;
    }
</style>

<script>
    export default {
        props:['csrf'],
        data(){
            return {
                email:null,
                password:null,
                accessToken : null,
                myErrors:{},
                credentialError:null,
            }
        },
        methods:{
            loginUser(){
                axios.post('../api/login',{
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
                        if(err.response.status ==400){
                            this.credentialError = err.response.data.error;

                        }else{
                            this.myErrors = err.response.data.errors;
                        }
                    })
            }
        },
    }
</script>
