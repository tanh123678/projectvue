<template>
<div class="app flex-row align-items-center">
 <div class="container">
	 <div class="row justify-content-center">
		 <div class="col-md-8">
			 <div class="card-group mb-0">
				 <div class="card p-4">
					 <form v-on:submit.prevent="reset()" novalidate="true">
						 <div class="card-body">
							 <h1>Reset Password</h1>
							 <p class="text-muted">Enter new password</p>
							 <!-- <p class="text-muted">{{this.$route.params.token}}</p> -->
							 <div class="input-group mb-3">
								 <span class="input-group-addon"><i class="icon-user"></i></span>
								 <input type="password" v-model="password" class="form-control" placeholder="Password">
							 </div>
							 <div class="input-group mb-4">
								 <span class="input-group-addon"><i class="icon-lock"></i></span>
								 <input type="password" v-model="passwordConfirm" class="form-control" placeholder="Password">
							 </div>
							 <p class="text-danger" v-if="errors.length">
								 <b>Please correct the following error(s):</b>
								 <ul>
									 <li v-for="error in errors">{{ error }}</li>
								 </ul>
							 </p>
							 <div class="row">
								 <div class="col-6">
									 <button type="submit" class="btn btn-primary px-4">Reset Password</button>
								 </div>
								 <div class="col-6 text-right">
									 <button type="button" class="btn btn-link px-0" @click="reset()">Forgot password?</button>
								 </div>
							 </div>
						 </div>
					 </form>
				 </div>
				 <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
					 <div class="card-body text-center">
						 <div>
							 <h2>Sign up</h2>
							 <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
							 <button type="button" class="btn btn-primary active mt-3">Register Now!</button>
						 </div>
					 </div>
				 </div>
			 </div>
		 </div>
	 </div>
 </div>
</div>
</template>

<script>
	export default {
	name: 'ResetPassword',
	data: function() {
		return {
			context: 'login context',
			// email: null,
			password: null,
			passwordConfirm: null,
			errors: [],
			token: this.$route.params.token,
		};
	},
	watch: {
		'$route' (to,from) {
			this.token = to.params.token;
		}
	},
	beforeRouteEnter(to, from, next) {
		console.log(to.params.token);
		axios.get('/password/find/' + to.params.token)
			.then(response => {
				// console.log(response.data.message);
				// if(response.data.message === 'Success'){
				// 	// next('/auth');
				// }
				next();
			})
			.catch(error => {
				if (error.response.status === 404) {
					next("/invalid-token");
				}	
			})
		next();
	},
	methods: {
		checkForm: function() {
		 this.errors = [];

		if (!this.password) {
			this.errors.push('Password required.');
		} else if (!this.validPass(this.password)) {
			this.errors.push('Valid password required.');
		}

		if (!this.passwordConfirm) {
			this.errors.push('Password required.');
		} else if (!this.validPass(this.passwordConfirm)) {
			this.errors.push('Valid password required.');
		}

		if (!this.errors.length) {
			return true;
		}
		return false;
		},
		validPass: function(password){
			var pass=  /^[0-9]/;
			return pass.test(password);
		},
		reset: function() {
		if (!this.checkForm()) {
			return false;
		}
		
		axios.post('/password/reset', {password: this.password,password_confirmation: this.passwordConfirm, token: this.token})
		.then(response => {
				// console.log(response.data.result)
				this.$router.push('/');
		})
		.catch(error => {
				// console.log(error);
			if (error.response) {
				console.log(error.response.data);
				console.log(error.response.status);
				console.log(error.response.headers);
			}
			if(error.response.status === 422){
				this.errors.push('Password do not match, please try again');
			}
		})
	},
	}

}
</script>
