Vue.component("list", {
template:
	`<div class="list">
		<div><button @click="logout">Logout</button></div>
		<div class="content">
			<h2>Customer Information</h2>
			<table class="common">
				<tbody>
					<tr><th>Institute</th><td>{{Institute}}</td></tr>
					<tr><th>Customer</th><td>{{Customer}}</td></tr>
				</tbody>
			</table>
			<h2>Project List</h2>
			<ul class="list" ref="list">
				<li v-for="project in orders">
					<h3>Project: {{project.id}}</h3>
					<table class="samples" cellspacing="1">
						<thead>
							<tr>
								<th v-for="x in fields">{{x.title}}</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="row in project.info">
								<td v-for="x in fields">{{row[x.id]}}</td>
							</tr>
						</tbody>
					</table>
				</li>
			</ul>
		</div>
	</div>`,
data:
	function(){
		return {
			orders:null,
			files:null,
			fields:[
				{title:"Sample", id:"SampleID"},
				{title:"Library", id:"Librarytype"},
				{title:"Description", id:"Description"},
				{title:"Platform", id:"Platform"},
				{title:"Species", id:"Species"},
				{title:"Application", id:"ApplicationType"},
				{title:"Type", id:"SampleType"},
				{title:"Run type", id:"RunningType"},
				{title:"Run scale", id:"RunScale"}
			]
		};
	},
computed:
	{
		Institute:function(){
			return this.order_list_extract(this.orders, "Institute");
		},
		Customer:function(){
			return this.order_list_extract(this.orders, "Customer");
		}
	},
methods:
	{
		logout:function(){
			if(call("account/logout")){
				location.href=urlroot;
			}
		},
		list_load:function(){
			call("data/orders", undefined, (orders)=>{
				this.orders=orders;
				mask(false);
			});
			call("data/files", undefined, (files)=>{
				this.files=files;
			});
		},
		order_list_extract:function(orders, key){
			if(orders!==null){
				var values=[];
				orders.forEach((X)=>{
					X.info.forEach((x)=>{
						if(key in x){
							const value=x[key];
							if(value!="null"&&values.indexOf(value)==-1){
								values.push(value);
							}
						}
					});
				});
				return values.length?values.join(", "):"";
			}else return "";
		},
		resize:function(){
			const list=this.$refs.list;
			const h=$(window).height()-$(list).offset().top;
			$(list).height(h-2);
		}
	},
created:
	function(){
		this.list_load();
	},
mounted:
	function(){
		this.resize();
		$(window).on("resize", ()=>{
			this.resize();
		});
	},
beforeDestroy:
	function(){
		$(window).off("resize");
	}
});