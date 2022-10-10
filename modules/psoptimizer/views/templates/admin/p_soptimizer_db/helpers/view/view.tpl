{if $license}
<div class="panel kpi-container">

	<div class="row">
		<div class="col-sm-6 col-lg-3">
			<div id="box-gender" data-toggle="tooltip" class="box-stats label-tooltip {$db_stat.color}" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminDashboard"></i>
					<span class="title">Kondycja bazy danych</span>
					<span class="subtitle"></span>
					<span class="value">{$db_stat.status}</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-age" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
				<div class="kpi-content">
					<i class="icon-calendar"></i>
					<span class="title">Ostatnia optymalizacja</span>
					<span class="subtitle"></span>
					<span class="value">{if $db_stat.last_optimization != ''}{$db_stat.last_optimization}{else}nigdy{/if}</span>
				</div>	
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-orders" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminParentStats"></i>
					<span class="title">Liczba rekordów do optymalizacji</span>
					<span class="subtitle"></span>
					<span class="value">{$db_stat.records}</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-newsletter" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminParentStats"></i>
					<span class="title">Wielkość danych do optymalizacji</span>
					<span class="subtitle"></span>
					<span class="value">{$db_stat.size} MB</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-12 col-lg-12" style="padding-top:10px;border-top: 1px solid #eee;text-align:right;">
			<form enctype="multipart/form-data" method="post" class="form-horizontal">
				<button type="submit" name="runDbClean" class="btn btn-default">
					<i class="icon-AdminPSoptimizer"></i>
					Optymalizuj teraz
				</button>
			</form>
		</div>
		
	</div>
</div>

{$form}
{/if}