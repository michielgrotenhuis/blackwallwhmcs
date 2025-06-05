<div class="modal" id="botguard-dialog" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="height:calc(100vh - 20%);max-width:1140px">
		<div class="modal-content" style="height:100%">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
<h2>Overview</h2>

<p>Thank you for entrusting your web assets protection to BotGuard!</p>

<p>The servers for your website <a href="http://{$botguard_domain}/" target="_main">{$botguard_domain}</a> are set and running.</p>

<p>Please find the integration manual at: <a href="https://botguard.net/en/documentation/integration" target="_main">https://botguard.net/en/documentation/integration</a></p>

<p>Please use the following BotGuard servers to integrate your website:</p>

<div class="alert alert-info">
	Primary server: <strong>{$primary_server}</strong>
	<br/>
	Secondary server: <strong>{$secondary_server}</strong>
</div>

<p>If you have any trouble configuring your website, please <a href="/submitticket.php">contact our support team</a>. We are always ready to help you.</p>

<h3>Bot Management</h3>

<p>To view events and manage protection rules, please click an appropriate button below.</p>

<p>
	<a data-toggle="modal" data-target="#botguard-dialog" data-src="{$show_stats_url}" class="btn btn-success">Show Statistics</a>
	<a data-toggle="modal" data-target="#botguard-dialog" data-src="{$show_events_url}" class="btn btn-success">Show Protection Events</a>
	<a data-toggle="modal" data-target="#botguard-dialog" data-src="{$manage_rules_url}" class="btn btn-success">Manage Protection Rules</a>
</p>
{/if}

<style>
	.botguard-frame {
		width: 100%;
		height: 100%;
		border: 0;
		background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text x="50%" y="50%" font-family="sans-serif" font-size="24" text-anchor="middle">Loading…</text></svg>') white 0px 0px no-repeat;
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
