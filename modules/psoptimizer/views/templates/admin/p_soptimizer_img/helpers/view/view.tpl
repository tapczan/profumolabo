{if !$stat.install || $stat.delete}
	{if !$stat.install && $stat.license}
	<div class="alert alert-info">
		Trwa synchronizacja danych.<br />
		Proces ten w zależności od liczby produktów może potrwać nawet kilkanaście minut.<br /><br />
		<strong>Spróbuj ponownie za 2-3 minuty.</strong>
		<div class="row">
			<div class="col-sm-12 col-lg-12" style="padding-top:10px;border-top: 1px solid #eee;text-align:left;">
				<a class="btn btn-default" href="{$stat.link}"><i class="icon-refresh"></i> Odśwież</a>
			</div>
		</div>
	</div>
	{/if}
	
	{if $stat.delete}
	<div class="alert alert-info">
		Trwa usuwanie skompresowanych elementów graficznych.<br />
		Proces ten w zależności od liczby obazków może potrwać nawet kilkanaście minut.<br /><br />
		<strong>Spróbuj ponownie za 2-3 minuty.</strong>
		<div class="row">
			<div class="col-sm-12 col-lg-12" style="padding-top:10px;border-top: 1px solid #eee;text-align:left;">
				<a class="btn btn-default" href="{$stat.link}"><i class="icon-refresh"></i> Odśwież</a>
			</div>
		</div>
	</div>
	{/if}

{else}

<div class="panel kpi-container">
	<div class="kpi-refresh">
		<form method="post" class="form-horizontal">
		<button class="close refresh" type="submit" name="updateAll"><i class="process-icon-refresh" style="font-size:1em"></i></button>
		</form>
	</div>
	<div class="row">
		<div class="col-sm-6 col-lg-3">
			<div id="box-gender" data-toggle="tooltip" class="box-stats label-tooltip {$stat.img_color}" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminDashboard"></i>
					<span class="title">Kompresja obrazków</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.img_status}</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-age" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminParentStats"></i>
					<span class="title">Łączna liczba obrazków</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.img_total}</span>
				</div>	
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-orders" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminParentStats"></i>
					<span class="title">Zoptymalizowane obrazki</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.img_optimized}</span>
				</div>
			</div>
		</div>
		
		<div class="col-sm-6 col-lg-3">
			<div id="box-newsletter" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
				<div class="kpi-content">
					<i class="icon-AdminParentModules"></i>
					<span class="title">Status optymalizacji</span>
					<span class="subtitle"></span>
					<span class="value">{$stat.img_progress}%</span>
				</div>
			</div>
		</div>
		
	</div>
</div>

{if !$stat.img_inprogress}
<div class="row">
	<div class="col-sm-12 col-lg-12">
			<form method="post" class="form-horizontal">
				<div class="panel">
					<h3>
						<i class="icon-fullscreen"></i> Kompresuj obrazki
					</h3>
					<div class="alert alert-info">
					{if $stat.img_todo > 0}
					Posiadasz <strong>{$stat.img_todo}</strong> niezoptymalizowanych obrazków.<br /> Szacowany czas potrzebny na kompresję zdjęć do formatu WebP to <strong>{$stat.img_estimated_time}</strong><br />Kompresja danych realizowana jest w tle i nie wpływa na źródłowe elementy graficzne.<br /><br /><strong>Uwaga!</strong> Proces optymalizacji powoduje duże obciążenie procesora, co w efekcie może czasowo wydłużyć czas ładowania Twojej witryny.<br />Zalecamy uruchomienie kompresji w godzinach wieczornych.
					{else}
					<strong>Gratulacje!</strong> Posiadasz skompresowaną wersję wszystkich obrazków w Twoim sklepie.
					{/if}
					</div>
					{if $stat.img_todo > 0}
					<button type="submit" class="btn btn-default" name="compressImg">
						<i class="icon-AdminPSoptimizer"></i> Optymalizuj teraz
					</button>
					{/if}			
				</div>
			</form>
		</div>
	</div>
{else}
<div class="row">
	<div class="col-sm-12 col-lg-12">
			<form method="post" class="form-horizontal">
				<div class="panel">
					<h3>
						<i class="icon-fullscreen"></i> Kompresuj obrazki
					</h3>
					<div class="alert alert-info">
					{if $stat.img_todo > 0}
					<strong>Trwa optymalizacja elementów graficznych Twojego sklepu do formatu WebP.</strong><br />
					Kompresja danych odbywa się w tle- możesz swobodnie wylogować się z sklepu lub zamknąć przeglądarkę.<br /><br />
					Pozostało <strong>{$stat.img_todo}</strong> niezoptymalizowanych obrazków.<br />
					Szacowany czas pozostały do zakończenia operacji to <strong>{$stat.img_estimated_time}</strong><br />
					Identyfikator procesu (PID): <strong>{$stat.pid}</strong><br /><br />
					Informacje dotyczące statusu optymalizacji oraz szacowanego czasu do zakończenia operacji aktualizowane są co <strong>15 min</strong>.
					
					</div>
					<button type="submit" class="btn btn-default" name="stopOptimize">
						<i class="icon-off"></i> Przerwij operację
					</button>

					<button type="submit" class="btn btn-default" name="updateProgressStatus">
						<i class="icon-refresh"></i> Odśwież status
					</button>
					{else}
					<strong>Gratulacje!</strong> Posiadasz skompresowaną wersję wszystkich obrazków w Twoim sklepie.
					</div>
					{/if}

					
				</div>
			</form>
		</div>
	</div>
{/if}

{$form}

<div class="row">
	<div class="col-sm-12 col-lg-12">

		<form method="post" class="form-horizontal" onSubmit="{literal}if(!confirm('Czy na pewno chcesz usunąć wszystkie skompresowane wersje obrazków?')){return false;}{/literal}">
			<div class="panel">
				<center>
					<button type="submit" class="btn btn-danger" name="deleteOptimizedImages">
						<i class="icon-trash"></i> Usuń wszystkie skompresowane elementy graficzne
					</button>
				</center>
			</div>
		</form>
	</div>
</div>
{/if}