<?php
	$menu = new \Sistema\Admin\Menu();
    $dados = $menu->getMenu();
    $itemAtual = isset($_GET[':item']) ? (string) $_GET[':item'] : '';
?>

	<div id="admMenu" class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
	    <ul class="navbar-nav">
	        <li class="nav-item nav-item-principal" v-for="item in dados" :key="item.item.id">
	        	<a class="nav-link text-decoration-none item-categoria-menu"
	        	   href="javascript:;"
	        	   @click.prevent="toggle(item.item.id)"
	        	   :aria-expanded="isOpen(item.item.id) ? 'true' : 'false'">
		         	<span class="adm-menu-icon" aria-hidden="true">
		         		<i :class="[item.item.icon, 'fa-fw']"></i>
		         	</span>
		            <span class="nav-link-text nav-item-principal-text">{{item.item.nome}}</span>
		            <i class="fas fa-chevron-down ms-auto menu-chevron" :class="{ 'is-open': isOpen(item.item.id) }"></i>
		        </a>

		        <ul class="navbar-nav menu-subitens" v-show="isOpen(item.item.id)">
	        		<li class="nav-item" v-for="sub in item.subitens" :key="sub.id">
	        			<a class="nav-link text-decoration-none"
	        			   :class="{ 'is-active': String(sub.form) === String(itemAtual) }"
	        			   :href="'ROOT/adm-home?item='+sub.form">
				            <span class="adm-menu-icon" aria-hidden="true">
				            	<i :class="[(sub && sub.icon) ? sub.icon : 'fas fa-circle', 'fa-fw']"></i>
				            </span>
				            <span class="nav-link-text">{{sub.item}}</span>
				        </a>
	        		</li>
	        	</ul>
	        </li>
	    </ul>
	</div>

<script>
var app = new Vue({
    el: '#admMenu',
    data: {
        dados: <?=json_encode($dados)?>,
        itemAtual: <?=json_encode($itemAtual)?>,
        openSections: {}
    },
    mounted: function () {
    	var map = {};
    	var atual = String(this.itemAtual || '');
    	(this.dados || []).forEach(function (item) {
    		if (!item || !item.item || !item.item.id) return;
    		var open = !atual;
    		if (atual && item.subitens) {
    			open = item.subitens.some(function (sub) {
    				return String(sub.form) === atual;
    			});
    		}
    		map[item.item.id] = open || !atual;
    	});
    	// se nada marcado, mantém seções abertas
    	if (!atual) {
    		(this.dados || []).forEach(function (item) {
    			if (item && item.item && item.item.id) map[item.item.id] = true;
    		});
    	}
    	this.openSections = map;
    },
    methods:{
    	isOpen: function (id) {
    		return !!this.openSections[id];
    	},
    	toggle: function (id) {
    		var next = Object.assign({}, this.openSections);
    		next[id] = !next[id];
    		this.openSections = next;
    	}
    }
})
</script>

<style scoped>
.navbar-vertical.navbar-expand-xs .navbar-collapse{ height: auto; }
.nav-item-principal{ margin-bottom: 8px; }
.nav-item-principal-text{
	font-weight: 700;
	font-size: 0.7rem;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	opacity: 0.8;
}
.menu-chevron{
	font-size: 10px;
	opacity: 0.55;
	transition: transform 0.2s ease;
	flex-shrink: 0;
}
.menu-chevron.is-open{ transform: rotate(180deg); }
</style>
