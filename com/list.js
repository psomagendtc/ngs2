Vue.component("list", {
template:
	`<div class="list">
		<div><button @click="logout">Logout</button></div>
		<div v-if="list!==null">
Institute
Customer

SampleID
Librarytype
Description
Platform
Species
SampleType
ApplicationType
RunningType
RunScale
			<table>
				<theadh>
					<tr><th>Project</th><th></th></tr>
				</theadh>
				<tbody>
					<tr></tr>
				</tbody>
			</table>
		</div>
		{{list}}
	</div>`,
data:
	function(){
		return {
			list:null
		};
	},
methods:
	{
		logout:function(){
			if(call("account/logout")){
				location.href=urlroot;
			}
		},
		list_load:function(){
			call("data/orders", undefined, (list)=>{
				this.list=list;
				mask(false);
			})
		}
	},
created:
	function(){
		this.list_load();
	}
});