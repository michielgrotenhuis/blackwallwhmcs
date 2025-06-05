<div class="modal" id="botguard-dialog" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="height:calc(100vh - 20%);max-width:1140px">
		<div class="modal-content" style="height:100%">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="height:calc(100% - 50px)">
				<iframe class="botguard-frame"></iframe>
			</div>
		</div>
	</div>
</div>

{if $status == 'Active'}
<h2>Дополнительные сведения</h2>

<p>Спасибо, что доверили защиту своих сайтов BotGuard!</p>

<p>Серверы для вашего сайта <a href="http://{$botguard_domain}/" target="_main">{$botguard_domain}</a> настроены и готовы к работе.</p>

<p>Пожалуйста, ознакомьтесь с инструкцией по интеграции по адресу: <a href="https://botguard.net/ru/documentation/integration" target="_main">https://botguard.net/ru/documentation/integration</a></p>

<p>Используйте следующие адреса серверов BotGuard для интеграции вашего сайта:</p>

<div class="alert alert-info">
	Основной сервер: <strong>{$primary_server}</strong>
	<br/>
	Резервный сервер: <strong>{$secondary_server}</strong>
</div>

<p>Если вы будете испытывать затруднения c настройкой интеграции вашего сайта, <a href="/submitticket.php">свяжитесь с нашей службой поддержки</a>. Мы всегда готовы помочь.</p>

<h3>Управление ботами</h3>

<p>Для просмотра событий и управления правилами блокировки, нажмите на соответствующую кнопку ниже.</p>

<p>
	<a data-toggle="modal" data-target="#botguard-dialog" data-src="{$show_stats_url}" class="btn btn-success">Просмотр статистики</a>
	<a data-toggle="modal" data-target="#botguard-dialog" data-src="{$show_events_url}" class="btn btn-success">Просмотр событий</a>
	<a data-toggle="modal" data-target="#botguard-dialog" data-src="{$manage_rules_url}" class="btn btn-success">Управление правилами</a>
</p>
{/if}

<style>
	.botguard-frame {
		width: 100%;
		height: 100%;
		border: 0;
		background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text x="50%" y="50%" font-family="sans-serif" font-size="24" text-anchor="middle">Загрузка…</text></svg>') white 0px 0px no-repeat;
	}
</style>

<script>
$(function() {
	$('#botguard-dialog').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget);
		var src = button.data('src');
		var modal = $(this);
		modal.find('.botguard-frame').attr('src', src);
	});
});
</script>
