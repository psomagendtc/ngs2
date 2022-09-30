Vue.component("mainframe", {
template:
	`<div class="mainframe">
		<comopnent :is="com"></component>
	</div>`,
data:
	function(){
		return {
			hash:location.hash.substr(1)
		};
	},
computed:
	{
		com:function(){
			return __coms.indexOf(this.hash)!=-1?this.hash:"login";
		}
	},
created:
	function(){
		$(window).on("hashchange", ()=>{
			this.hash=location.hash.substr(1);
			mask(true);
		});
	}
});