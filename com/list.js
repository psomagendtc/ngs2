Vue.component("list", {
template:
	`<div class="list">
		<textarea style="width:1px;height:1px;padding:0;margin:0;position:fixed;right:0px;bottom:0px;border:none;color:transparent;background-color:transparent;" tabindex="-1" ref="textarea"></textarea>
		<div class="top">
			<img class="icon" src="src/logo_w.png"/>
			<button @click="logout">Logout</button>
		</div>
		<div class="content">
			<div class="common_wrap">
				<h2>Customer</h2>
				<table class="common">
					<tbody>
						<tr><th>Institute</th><td>{{Institute}}</td></tr>
						<tr><th>Customer</th><td>{{Customer}}</td></tr>
					</tbody>
				</table>
				<div class="bar c1"></div><div class="bar c2"></div><div class="bar c3"></div><div class="bar c4"></div>
			</div>
			<ul class="projectlist" ref="list">
				<li v-for="project in orders">
					<h2><span>Project</span>{{project.id}}</h2>
					<table class="samples" cellspacing="1">
						<thead>
							<tr>
								<th v-for="x in fields">{{x.title}}</th>
								<th>File list</th>
							</tr>
						</thead>
						<tbody v-if="project.info.filter(x=>files!==null&&project.id in files&&x.SampleID in files[project.id]).length+(project.id in samples_extra?samples_extra[project.id].length:0)">
							<template v-for="row in project.info">
								<tr v-if="files!==null&&project.id in files&&row.SampleID in files[project.id]">
									<td v-for="x in fields" :class="x.id">{{row[x.id]}}</td>
									<td class="seefiles">
										<button @click="hidelist(project.id, row.SampleID)" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">△ Hide list</button>
										<button @click="seelist(project.id, row.SampleID)" v-else>🔽 Show list</button>
									</td>
								</tr>
								<tr class="filelist" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">
									<td :colspan="fields.length+1">
										<ul>
											<li v-for="x in filelist[project.id][row.SampleID]">
												<span class="title">{{x.name}}<span class="size">({{number_format(x.size)+" byte"+(x.size>1?"s":"")}})</span></span>
												<a :href="urlroot+'/download?project='+encodeURIComponent(project.id)+'&sample='+encodeURIComponent(row.SampleID)+'&file='+encodeURIComponent(x.name)"><button>Download</button></a>
												<button @click="copytoclip(project.id, row.SampleID, x.name)">Copy a single-use link to clipboard</button>
											</li>
										</ul>
									</td>
								</tr>
							</template>
							<template v-for="row in samples_extra[project.id]" v-if="project.id in samples_extra">
								<tr>
									<td v-for="x in fields" :class="x.id">{{x.id in row?row[x.id]:""}}</td>
									<td class="seefiles">
										<button @click="hidelist(project.id, row.SampleID)" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">△ Hide list</button>
										<button @click="seelist(project.id, row.SampleID)" v-else>🔽 Show list</button>
									</td>
								</tr>
								<tr class="filelist" v-if="project.id in filelist&&row.SampleID in filelist[project.id]">
									<td :colspan="fields.length+1">
										<ul>
											<li v-for="x in filelist[project.id][row.SampleID]">
												<span class="title">{{x.name}}<span class="size">({{number_format(x.size)+" byte"+(x.size>1?"s":"")}})</span></span>
												<a :href="urlroot+'/download?project='+encodeURIComponent(project.id)+'&sample='+encodeURIComponent(row.SampleID)+'&file='+encodeURIComponent(x.name)"><button>Download</button></a>
												<button @click="copytoclip(project.id, row.SampleID, x.name)">Copy a single-use link to clipboard</button>
											</li>
										</ul>
									</td>
								</tr>
							</template>
						</tbody>
						<tbody v-else><tr><td :colspan="fields.length+1" class="nofiles">No file</td></tr></tbody>
					</table>
					<template v-if="project.info.filter(x=>files!==null&&project.id in files&&x.SampleID in files[project.id]).length+(project.id in samples_extra?samples_extra[project.id].length:0)">
						<button @click="wgets(project.id)">Linux wget commands</button> <button v-if="project.id in wgets_buffer" @click="copytoclip2(wgets_buffer[project.id])">Copy to clipboard</button>
						<textarea class="wgetsbuffer" v-if="project.id in wgets_buffer" :value="wgets_buffer[project.id]" readonly></textarea>
						<div class="endofproject"></div>
					</template>
				</li>
			</ul>
		</div>
	</div>`,
data:
	function(){
		return {
			urlroot,
			orders:null,
			files:null,
			filelist:{},
			fields:[
				{title:"Sample", id:"SampleID"}, {title:"Library", id:"Librarytype"}, {title:"Description", id:"Description"},
				{title:"Platform", id:"Platform"}, {title:"Species", id:"Species"}, {title:"Application", id:"ApplicationType"},
				{title:"Type", id:"SampleType"}, {title:"Run type", id:"RunningType"}, {title:"Run scale", id:"RunScale"}
			],
			resize_t:null,
			wgets_buffer:{}
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
				return values.length?values.join(", "):"-";
			}else return "";
		},
		seelist:function(project, sample){
			var filelist=JSON.parse(JSON.stringify(this.filelist));
			if(!(project in filelist)){
				filelist[project]={};
			}
			filelist[project][sample]=null;
			this.filelist=filelist;
			call("data/filelist", {project, sample}, x=>{
				var filelist=JSON.parse(JSON.stringify(this.filelist));
				filelist[project][sample]=x;
				this.filelist=filelist;
			});
		},
		hidelist:function(project, sample){
			var filelist=JSON.parse(JSON.stringify(this.filelist));
			delete filelist[project][sample];
			this.filelist=filelist;
		},
		copytoclip:function(project, sample, file){
			mask(true);
			call("data/makelink", {project, sample, file}, (x)=>{
				if(x!==undefined&&"link" in x){
					const link=urlroot+"/download?id="+x.link+"."+file;
					var textarea=this.$refs.textarea;
					textarea.value=link;
					textarea.select();
					textarea.setSelectionRange(0, 999999);
					document.execCommand("copy");
					textarea.setSelectionRange(0, 0);
				}else{
					alert("ERROR: Inappropriate Attempt");
				}
				mask(false);
			});
		},
		copytoclip2:function(text){
			mask(true);
			navigator.clipboard.writeText(urlroot+"/download?id="+x.link+"."+file);
		},
		resize:function(){
			const list=this.$refs.list;
			const h=$(window).height()-$(list).offset().top;
			$(list).height(h);
		},
		number_format:function(x){
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},
		wgets:function(project_id){
			if(!(project_id in this.wgets_buffer)){
				call("data/wgets", {project_id}, (x)=>{
					var wgets_buffer=JSON.parse(JSON.stringify(this.wgets_buffer));
					wgets_buffer[project_id]=x.join("\n");
					this.wgets_buffer=wgets_buffer;
				});
			}else{
				var wgets_buffer=JSON.parse(JSON.stringify(this.wgets_buffer));
				delete wgets_buffer[project_id];
				this.wgets_buffer=wgets_buffer;
			}
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