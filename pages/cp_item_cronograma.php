<script type="text/x-template" id="cp_item_cronograma">		
	<div class="item-task-box-parent" ref="pai">
		<!-- <div class="handle"> <i class="fa fa-align-justify "></i></div> -->
		<div class="item-task-box"  ref="filho" v-bind:style="{width:largura+'px'}"  v-bind:id="'taskid'+item.id"  v-tooltip="{ content: item.nome }" >
		  	
		  	<div class="item-arraste handle">
		  		<i class="fas fa-bars"></i>
		  		
		  	</div>
		  	<div class="item-task-resize" v-on:mousedown="startDragging()"  > </div>
		  	<div class="item-task-resize-connect connect-r"  v-bind:id="'taskidr'+item.id"> </div>
		  	<div class="item-task-resize-connect connect-l"  v-bind:id="'taskidl'+item.id"> </div>
		    
		</div>
	</div>



</script>

<script>
	Vue.component('cp_item_cronograma', {
		template: '#cp_item_cronograma',
		data: function(){
			var vm = this;
			return {
				largura:0,
				init:0,
				largInit:0,
				dragging:false
			};
		},
		props: {
			item:{}
		},
		mounted(){

			let larg = this.$refs.pai.clientWidth;
			if(this.item.dias && this.item.dias >= 1){
				let d = (this.item.dias*1)+1;
				console.log('dias',d)
				larg = (larg*d) + (d*1)  
			}
			this.largura = larg;
			
		},
		methods:{
			envia(){
				//console.log('enviando',this.item)

				this.$emit('changeitem', this.item);
			},	
			
	       	
	        handleDragging (e) {
	       
	          	if(this.init == 0){
		          	this.init = e.pageX;
		          	this.largInit = this.$refs.filho.clientWidth;
	          	}
	         
	            let movimentei = e.pageX - this.init;

	            this.largura = this.largInit + movimentei;

	            let alteracao = this.largura/this.$refs.pai.clientWidth;

	            let mais_proximo = Math.round(alteracao);
	      
	            if(alteracao > mais_proximo){
	            	if((alteracao - mais_proximo) < 0.1){
	            		this.largura = (this.$refs.pai.clientWidth * mais_proximo)+mais_proximo;
	            	}
	            }else{

	            	if((mais_proximo - alteracao) < 0.1){
	            		this.largura = (this.$refs.pai.clientWidth * mais_proximo)+mais_proximo;
	            	}

	            }
	          

	        },
	        aproxima(){


	        	let largura = this.$refs.filho.clientWidth;

	            let alteracao = largura/this.$refs.pai.clientWidth;

	            let mais_proximo = Math.round(alteracao);

	            console.log('mais_proximo',mais_proximo,this.$refs.pai.clientWidth)
	            if(mais_proximo < 1){
	            	mais_proximo = 1
	            }
	            
	            if(alteracao > mais_proximo){
	            	//if((alteracao - mais_proximo) ){
	            		this.largura = (this.$refs.pai.clientWidth * mais_proximo)+mais_proximo;
	            	//}
	            }else{

	            	//if((mais_proximo - alteracao) ){
	            		this.largura = (this.$refs.pai.clientWidth * mais_proximo)+mais_proximo;
	            	//}

	            }
	            this.item.dias = mais_proximo-1;
	            this.envia();

	        },
	        startDragging () {
	        	console.log('START')
	        	this.dragging = true;
	          	document.addEventListener('mousemove', this.handleDragging)
	        },
	        endDragging () {
	        	if(this.dragging){
	        	

		        	this.dragging = false;
		        	this.init = 0;
		        	this.aproxima();
		        	this.$forceUpdate();
		          	document.removeEventListener('mousemove', this.handleDragging)
	        	}
	        },
	        keyDown: function () {
		      this.endDragging();
		    }
	      
			
    	},
	  	created: function () {
		    window.addEventListener('mouseup', this.keyDown)
		},

		destroyed: function () {
		    window.removeEventListener('mouseup', this.keyDown)
		}

	  // options
	});
</script>

<style>
.item-task-box-parent{
	height: 100%;
}
.item-task-box{
	background-color: #01a3a4;
	border-radius: 5px;
	height: 100%;
	position: relative;
	width: 100%;
	
}
.item-task-resize{
	position: absolute;
	right: 0px;
	top:0px;
	height: 100%;
	width:7px;
	background-color: rgba(255,255,255,0.5);
	cursor: col-resize;
}
.item-arraste{
	position: absolute;
	top: 0px;
	left: 0px;height: 100%;
	width: calc(100% - 15px);
	width: 25px;
	margin-top: 12px;
    margin-left: 5px;
    opacity: 0.8;
	font-size: 14px;
	color: #fff;
	cursor: move;
}
.item-task-resize-connect {
	position: absolute;
	top:50%;

}
.connect-r{
	right: 0px;
}
.connect-l{
	left: 0px;
}
</style>
