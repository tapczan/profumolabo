<div id="x13links-modal" class="x13linksProductModal bootstrap">
	<div class="x13linksProductModal__content">
		<div class="x13linksProductModal__inner panel">
			<h4 class="x13linksProductModal__title">
				{l s='There are repetitions of product links' mod='x13links'}
			</h4>
			<p class="x13linksProductModal__text">
				{l s='We recommend entering the individual name of the friendly link in the SEO tab -> Friendly URL.' mod='x13links'}<br>
				{l s='Do you want to save the product with the duplicate link?' mod='x13links'}
			</p>
			<div class="panel-footer">
				<a href="#" class="btn btn-default" id="x13links-cancel"><i class="process-icon-cancel"></i>{l s='Edit' mod='x13links'}</a>
				<a href="#" class="btn btn-default pull-right" id="x13links-save"><i class="process-icon-save"></i>{l s='Save' mod='x13links'}</a>
			</div>
		</div>
	</div>
</div> 


<style>

.x13linksProductModal {
	position: fixed;
	left: 0;
	right: 0;
	top: 0;
	bottom: 0;
	background: rgba(0,0,0,.4);
	z-index: 10000;
}

.x13linksProductModal__text {
	font-size: 14px;
}

.x13linksProductModal__content {
	height: 100vh;
	overflow: scroll;
	padding: 20px;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
}

.x13linksProductModal__inner {
	max-width: 500px;
	width: 100%;
	background: #fff;
	border: 1px solid #eee;
	padding: 20px 20px 10px;
	text-align: center;
}

.bootstrap .x13linksProductModal__title {
	margin: 0 0 20px;
	font-size: 24px;
	color: #222;
	font-weight: 600;
}

.x13linksProductModal .panel-footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
}


</style>