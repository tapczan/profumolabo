{if $license}

{if $update}
<div class="alert alert-info">
Dostępna jest nowa aktualizacja oprogramowania.
<div class="row">
		<div class="col-sm-12 col-lg-12" style="padding-top:10px;border-top: 1px solid #eee;">
			<form enctype="multipart/form-data" method="post" class="form-horizontal">
				<button type="submit" name="update" class="btn btn-default">
					<i class="icon-off"></i>
					Aktualizuj teraz
				</button>
			</form>
		</div>
</div>
</div>
{/if}

{if isset($alert_fb)}
<div class="alert alert-info">
{$alert_fb}
<div class="row">
		<div class="col-sm-12 col-lg-12" style="padding-top:10px;border-top: 1px solid #eee;text-align:right;">
			<form enctype="multipart/form-data" method="post" class="form-horizontal">
				<button type="submit" name="disableBlockFb" class="btn btn-default">
					<i class="icon-off"></i>
					Wyłącz moduł
				</button>
			</form>
		</div>
</div>
</div>
{/if}
<div class="panel kpi-container">

	<div class="row">
		<div class="col-sm-6 col-lg-3">
			<div id="box-gender" data-toggle="tooltip" class="box-stats label-tooltip {$stat.db_color}" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminDashboard"></i>
					<span class="title">Kondycja bazy danych</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.db_status}</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-age" data-toggle="tooltip" class="box-stats label-tooltip {$stat.cfg_color}" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminTools"></i>
					<span class="title">Ustawienia sklepu</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.cfg_status}</span>
				</div>	
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-orders" data-toggle="tooltip" class="box-stats label-tooltip {$stat.mod_color}" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminParentModules"></i>
					<span class="title">Nadmiarowe moduły</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.mod_status}</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-newsletter" data-toggle="tooltip" class="box-stats label-tooltip {$stat.ls_color}" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminParentModules"></i>
					<span class="title">LiteSpeed Cache</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.ls_status}</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-12 col-lg-12" style="padding-top:10px;border-top: 1px solid #eee;text-align:right;">
			<form enctype="multipart/form-data" method="post" class="form-horizontal">
				<button type="submit" name="runMain" class="btn btn-default">
					<i class="icon-AdminPSoptimizer"></i>
					Optymalizuj teraz
				</button>
			</form>
		</div>
		
	</div>
</div>

{$form}
{/if}