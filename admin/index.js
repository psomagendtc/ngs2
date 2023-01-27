var app=null;
Vue.component("maintop", {
template:
	`<div class="maintop">
		<div class="inner">
			<img class="logo" src="../src/logo_w.png"/>
		</div>
	</div>`
});
Vue.component("mainbottom", {
template:
	`<div class="mainbottom">
	</div>`
});
$(document).ready(()=>{
	app=new Vue({
		el:"#view"
	});
});
function call(method, data, callback){
	if(method===undefined)return;
	if(data===undefined)data={};
	const async=callback!==undefined;
	return $.ajax({url:urlroot+"/srv/", type:"post", data:{method, data:JSON.stringify(data)}, success:callback, error:(x)=>{
		if(callback!==undefined)callback({error:x.statusText, code:x.status});
	}, async, dataType:"json"}).responseJSON;
}
function mask(on){
	if(on!==false&&on!==0){//on
		$("#mask").fadeIn("fast");
	}else{
		$("#mask").fadeOut("fast");
	}
}