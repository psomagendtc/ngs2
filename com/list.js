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
								<th>File list</th>
							</tr>
						</thead>
						<tbody v-if="project.info.length+samples_extra[project.id]">
							<template v-for="row in project.info">
								<tr v-if="files!==null&&project.id in files&&row.SampleID in files[project.id]">
									<td v-for="x in fields">{{row[x.id]}}</td>
									<td class="seefiles">
										<button @click="hidelist(project.id, row.SampleID)" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">❌ Hide list</button>
										<button @click="seelist(project.id, row.SampleID)" v-else>🔽 Show list</button>
									</td>
								</tr>
								<tr class="filelist" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">
									<td :colspan="fields.length+1">-</td>
								</tr>
							</template>
							<template v-for="row in samples_extra[project.id]" v-if="project.id in samples_extra">
								<tr>
									<td v-for="x in fields">{{x.id in row?row[x.id]:""}}</td>
									<td class="seefiles">
										<button @click="hidelist(project.id, row.SampleID)" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">❌ Hide list</button>
										<button @click="seelist(project.id, row.SampleID)" v-else>🔽 Show list</button>
									</td>
								</tr>
								<tr class="filelist" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">
									<td :colspan="fields.length+1">-</td>
								</tr>
							</template>
						</tbody>
						<tbody v-else><tr><td :colspan="fields.length+1" class="nofiles">No files</td></tr></tbody>
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
			filelist:{},
			fields:[
				{title:"Sample", id:"SampleID"}, {title:"Library", id:"Librarytype"}, {title:"Description", id:"Description"},
				{title:"Platform", id:"Platform"}, {title:"Species", id:"Species"}, {title:"Application", id:"ApplicationType"},
				{title:"Type", id:"SampleType"}, {title:"Run type", id:"RunningType"}, {title:"Run scale", id:"RunScale"}
			],
			resize_t:null
		};
	},
computed:
	{
		Institute:function(){
			return this.order_list_extract(this.orders, "Institute");
		},
		Customer:function(){
			return this.order_list_extract(this.orders, "Customer");
		},
		samples_extra:function(){
			var samples_extra={};
			if(this.orders!==null&&this.files!==null){
				var orders={};
				this.orders.forEach((project)=>{
					const id=project.id;
					orders[id]={};
					project.info.forEach((sample)=>{
						orders[id][sample.SampleID]=true;
					});
				});
				for(var id in this.files){
					for(var sample in this.files[id]){
						if(!(id in orders&&sample in orders[id])){
							if(!(id in samples_extra))samples_extra[id]=[];
							samples_extra[id].push({"SampleID":sample});
						}
					}
				}
			}
			return samples_extra;
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
		seelist:function(project, sample){
			var filelist=JSON.parse(JSON.stringify(this.filelist));
			if(!(project in filelist)){
				filelist[project]={};
			}
			filelist[project][sample]=true;
			this.filelist=filelist;
		},
		hidelist:function(project, sample){
			var filelist=JSON.parse(JSON.stringify(this.filelist));
			delete filelist[project][sample];
			this.filelist=filelist;
		},
		resize:function(){
			const list=this.$refs.list;
			const h=$(window).height()-$(list).offset().top;
			$(list).height(h);
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
		this.resize_t=setInterval(this.resize, 1000);
	},
beforeDestroy:
	function(){
		$(window).off("resize");
		clearInterval(this.resize_t);
	}
});