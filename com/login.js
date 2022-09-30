Vue.component("login", {
template:
	`<div class="login">
		<maintop></maintop>
		<h2>Customer Report Drive</h2>
		<div class="dialog">
			<div class="title">CUSTOMER LOGIN</div>
			<div><label>Username: <input type="text" v-model.trim="id" autofocus ref="id" @keydown.enter="login"/></label></div>
			<div><label>Password: <input type="password" v-model.trim="pw" ref="pw" @keydown.enter="login"/></label></div>
			<button @click="login">Login</button>
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