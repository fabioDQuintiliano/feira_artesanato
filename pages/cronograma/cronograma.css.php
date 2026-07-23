<style>
.item-day{

	display: flex;
	
	border-right: solid 1px #ccc;
	border-bottom: solid 1px #ccc;
	flex:1;
	min-width: 40px;
	justify-content: center;
}
.item-remove{
	margin-left: 15px;
	cursor: pointer;
}
.item-mes-ano{
	line-height: 12px;
}
.item-day>div{
	height: 100%;
}
.item-cro-header{
	height: 70px;
}
.item-day-task{
	

}
.task-side{
	padding-top: 70px;
}
.h-padrao{
	height: 40px;
}

.arrastando{
	pointer-events: none;
}

.input-tarefa{
	padding: 5px 5px;
	background: transparent;
	border:none;
	border-radius: 5px;
	border-bottom: solid 1px #ccc;
	background: #fff;
	outline: none;
}
.input-tarefa-titulo{
	margin-right: 20px;

}
.input-tarefa.pai{
	width: 30px;
	margin-left: 3px;
	text-align: center;
}
.fim_semana{
	background: rgba(52, 152, 219,0.2);

}
.hoje{
	background: rgba(243, 156, 18,0.4);
}
.navbar-main{
	position: absolute;
	width: calc(100% - 3rem);
	top:4px;
}
.main-content{
	padding-top: 50px;
}
.item-salvo{
	position: fixed;
	top: 10px;
	left: 50%;
	padding: 5px 3px;
	background-color: #2ecc71;
	color: #fff;
	border-radius: 5px;
	transform: translate3d(-50%, 0px, 0px);
	z-index: 999;
}
.salvar-box{
	position: fixed;
	bottom: 10px;
}

.container-imput{
	position: relative;
	width: 100%;
    height: 100%;
    margin-right: 10px;
    margin-top: 5px;

}
input[type="text"].input-tarefa-titulo:focus {
    background:#eee;
    color: #6a6f75;
    width: 400px !important;
   
    position: absolute;
    left: 0;
    z-index: 9999;
}
input[type="text"]:focus{
	box-shadow: 1px 1px 3px -2px #000;
}
input[type="text"].input-tarefa-titulo {
    -webkit-transition: all 0.7s ease 0s;
    -moz-transition: all 0.7s ease 0s;
    -o-transition: all 0.7s ease 0s;
    transition: all 0.7s ease 0s;
}
</style>