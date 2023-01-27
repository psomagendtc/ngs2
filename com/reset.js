Vue.component("reset", {
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
				<div class="title">NEW PASSWORD</div>
				<div class="input_wrap">
					<input type="password" v-model.trim="pw1" ref="pw1" @keydown.enter="reset" placeholder="Password"/>
					<img class="icon" src="src/pw_icon.png"/>
				</div><br/>
				<div class="input_wrap">
					<input type="password" v-model.trim="pw2" ref="pw2" @keydown.enter="reset" placeholder="Password confirm"/>
					<img class="icon" src="src/pw_icon.png"/>
				</div><br/>
				<ul v-if="pw1!==''" class="errors">
					<li v-for="e in errors">{{e}}</li>
				</ul>
				<button @click="reset" :disabled="errors.length>0">Reset</button>
				<div class="edge b1"></div><div class="edge b2"></div><div class="edge b3"></div><div class="edge b4"></div>
			</div>
		</div>
		<mainbottom></mainbottom>
	</div>`,
data:
	function(){
		return {
			u:"u" in __gets?__gets["u"]:null,
			token:"i" in __gets?__gets["i"]:null,
			pw1:"", pw2:""
		}
	},
methods:
	{
		reset:function(){
			if(this.pw1==""||this.errors.length)$(this.$refs.pw1).focus();
			else{
				mask(true);
				call("account/pw", {u:this.u, token:this.token, pw:this.pw1, reset:true}, (x)=>{
					if(x===true)alert("Password Updated!");
					else alert("ERROR: Invalid Access.");
					location.href=urlroot;
					mask(false);
				});
			}
		}
	},
computed:
	{
		errors:function(){
			var errors=[];
			if(!/[A-Z]/.test(this.pw1))errors.push("Must contain uppercase");
			if(!/[a-z]/.test(this.pw1))errors.push("Must contain lowercase");
			if(!/[0-9]/.test(this.pw1))errors.push("Must contain number");
			if(!/[\~\`\!\@\#\$\%\^\&\*\(\)\_\-\+\=\{\[\}\]\|\\\:\;\"\'\<\,\>\.\?\/]/.test(this.pw1))errors.push("Must contain symbol");
			if(this.pw1!=this.pw2)errors.push("Must be the same");
			return errors;
		}
	},
mounted:
	function(){
		if(this.u===null||this.token===null)location.href=urlroot;
		mask(false);
	}
});