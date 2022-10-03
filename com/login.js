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
			<div class="dialog">
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
				<div class="edge b1"></div><div class="edge b2"></div><div class="edge b3"></div><div class="edge b4"></div>
			</div>
		</div>
		<mainbottom></mainbottom>
	</div>`,
data:
	function(){
		return {
			id:"", pw:""
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
		}
	},
mounted:
	function(){
		mask(false);
	}
});