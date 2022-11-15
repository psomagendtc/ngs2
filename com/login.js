Vue.component("login", {
template:
	`<div class="login">
		<maintop></maintop>
		<div class="background_wrap">
			<img class="background" src="src/login_back.png"/>
		</div>
		<div class="title">
			<h1>Psomagen NGS Service</h1>
			<h2>Report and Result Delivery Drive</h2>
		</div>
		<div class="dialog_wrap">
			<div class="dialog" v-if="pw_rst_mode===false">
				<div class="title">CUSTOMER LOGIN</div>
				<div class="input_wrap">
					<input type="text" v-model.trim="id" autofocus ref="id" @keydown.enter="login" placeholder="User ID"/>
					<img class="icon" src="src/id_icon.png"/>
				</div><br/>
				<div class="input_wrap">
					<input type="password" v-model.trim="pw" ref="pw" @keydown.enter="login" placeholder="Password"/>
					<img class="icon" src="src/pw_icon.png"/>
				</div><br/>
				<button @click="login">Login</button>
				<div class="reset" @click="pw_rst_mode=true">Set or reset password</div>
				<div class="edge b1"></div><div class="edge b2"></div><div class="edge b3"></div><div class="edge b4"></div>
			</div>
			<div class="dialog" v-else>
				<div class="title">PASSWORD RESET</div>
				<div class=input_wrap>
					<input type="text" v-model.trim="id_to_reset" autofocus ref="id_to_reset" @keydown.enter="reset" placeholder="User ID"/>
					<img class="icon" src="src/id_icon.png"/>
				</div><br/>
				<button @click="reset">Submit</button><br/>
				<div class="reset" @click="pw_rst_mode=false">Return to login</div>
				<div class="edge b1"></div><div class="edge b2"></div><div class="edge b3"></div><div class="edge b4"></div>
			</div>
		</div>
		<mainbottom></mainbottom>
	</div>`,
data:
	function(){
		return {
			id:"", pw:"",
			id_to_reset:"",
			pw_rst_mode:false
		}
	},
methods:
	{
		login:function(){
			if(this.id=="")$(this.$refs.id).focus();
			else if(this.pw=="")$(this.$refs.pw).focus();
			else{
				call("account/login", {id:this.id, pw:this.pw}, (x)=>{
					if(x===true){
						location.hash="list";
					}else if("error" in x){
						alert("ERROR: "+x.error);
					}else{
						alert("ERROR: unknown");
					}
				});
			}
		},
		reset:function(){
			if(this.id_to_reset!=""){
				mask(true);
				call("account/reset", {id:this.id_to_reset}, (x)=>{
					this.pw_rst_mode=false;
					mask(false);
					alert("Check your email\nWe've sent password reset instruction to '"+this.id_to_reset+"'.");
				});
			}else $(this.$refs.id_to_reset).focus();
		}
	},
mounted:
	function(){
		mask(false);
	}
});