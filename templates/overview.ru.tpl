{* BotGuard WHMCS Module - Client Area Overview Template (Russian) *}
{* Compatible with WHMCS 8.13 and Bootstrap 4/5 *}

<div class="modal fade" id="botguard-dialog" tabindex="-1" role="dialog" aria-labelledby="botguard-dialog-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="height:calc(100vh - 20%);max-width:1140px">
        <div class="modal-content" style="height:100%">
            <div class="modal-header">
                <h5 class="modal-title" id="botguard-dialog-title">Управление BotGuard</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="height:calc(100% - 60px)">
                <iframe class="botguard-frame" title="Интерфейс управления BotGuard"></iframe>
            </div>
        </div>
    </div>
</div>

{if $service_status == 'Active'}
<div class="product-details-tab-content">
    <h2 class="h4 mb-4">Обзор системы управления ботами BotGuard</h2>

    <div class="alert alert-success" role="alert">
        <i class="fas fa-shield-alt me-2"></i>
        <strong>Защита активна!</strong> Ваш сайт защищен передовой системой управления ботами BotGuard.
    </div>

    <p class="lead mb-4">Спасибо, что доверили защиту своих веб-ресурсов BotGuard!</p>

    <div class="row mb-4">
        <div class="col-md-8">
            <p>Ваш сайт <strong><a href="http://{$botguard_domain}/" target="_blank" rel="noopener">{$botguard_domain}</a></strong> активно защищен от вредоносных ботов, краулеров и автоматических атак.</p>
            
            <p>BotGuard обеспечивает защиту в реальном времени:</p>
            <ul class="list-unstyled">
                <li><i class="fas fa-check text-success me-2"></i> Блокирует вредоносный трафик ботов</li>
                <li><i class="fas fa-check text-success me-2"></i> Снижает нагрузку на сервер</li>
                <li><i class="fas fa-check text-success me-2"></i> Улучшает SEO-показатели</li>
                <li><i class="fas fa-check text-success me-2"></i> Обеспечивает точную веб-аналитику</li>
                <li><i class="fas fa-check text-success me-2"></i> Предотвращает парсинг контента</li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="fas fa-globe fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Защищенный домен</h5>
                    <p class="card-text text-muted">{$botguard_domain}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="h5 mb-3">Детали интеграции</h3>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-server me-2"></i>Основной сервер
                </div>
                <div class="card-body">
                    <code class="fs-6">{$primary_server}</code>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-server me-2"></i>Резервный сервер
                </div>
                <div class="card-body">
                    <code class="fs-6">{$secondary_server}</code>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Нужна помощь?</strong> Ознакомьтесь с руководством по интеграции: 
        <a href="https://botguard.net/ru/documentation/integration" target="_blank" rel="noopener" class="alert-link">
            https://botguard.net/ru/documentation/integration
        </a>
    </div>

    {if $api_available}
    <h3 class="h5 mb-3">Инструменты управления</h3>
    <p>Используйте кнопки ниже для доступа к статистике в реальном времени, просмотра событий защиты и настройки правил управления ботами.</p>
    
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <button type="button" class="btn btn-success btn-lg w-100" 
                    data-toggle="modal" data-target="#botguard-dialog" 
                    data-src="{$show_stats_url}"
                    title="Просмотр подробной статистики заблокированных ботов и трафика">
                <i class="fas fa-chart-bar me-2"></i>
                Просмотр статистики
            </button>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-info btn-lg w-100" 
                    data-toggle="modal" data-target="#botguard-dialog" 
                    data-src="{$show_events_url}"
                    title="Просмотр последних событий защиты и заблокированных попыток">
                <i class="fas fa-shield-alt me-2"></i>
                События защиты
            </button>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-primary btn-lg w-100" 
                    data-toggle="modal" data-target="#botguard-dialog" 
                    data-src="{$manage_rules_url}"
                    title="Настройка правил защиты и параметров">
                <i class="fas fa-cog me-2"></i>
                Управление правилами
            </button>
        </div>
    </div>
    {else}
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Доступ к управлению недоступен</strong> 
        Инструменты управления в настоящее время недоступны. Обратитесь в службу поддержки, если вам нужна помощь.
    </div>
    {/if}

    <hr class="my-4">
    
    <div class="row">
        <div class="col-md-8">
            <h3 class="h6 mb-2">Нужна поддержка?</h3>
            <p class="text-muted">Если у вас возникли проблемы с защитой BotGuard или нужна помощь с настройкой, наша служба поддержки готова помочь.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/submitticket.php" class="btn btn-outline-primary">
                <i class="fas fa-life-ring me-2"></i>Связаться с поддержкой
            </a>
        </div>
    </div>
</div>
{else}
<div class="alert alert-warning" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Служба неактивна</strong> 
    Ваша служба BotGuard в настоящее время {$service_status|lower}. Функции защиты в данный момент недоступны.
</div>
{/if}

<style>
.botguard-frame {
    width: 100%;
    height: 100%;
    border: 0;
    background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text x="50%" y="50%" font-family="sans-serif" font-size="18" text-anchor="middle" fill="%23666">Загрузка интерфейса BotGuard...</text></svg>') white 0px 0px no-repeat;
    background-position: center;
}

.product-details-tab-content .card {
    transition: all 0.2s ease-in-out;
}

.product-details-tab-content .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .modal-dialog {
        height: calc(100vh - 10%);
        margin: 5vh auto;
    }
    
    .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('botguard-dialog');
    if (modal) {
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const src = button.getAttribute('data-src');
            const frame = modal.querySelector('.botguard-frame');
            
            if (src && frame) {
                frame.setAttribute('src', src);
            }
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            const frame = modal.querySelector('.botguard-frame');
            if (frame) {
                frame.setAttribute('src', 'about:blank');
            }
        });
    }
    
    // Fallback for older Bootstrap versions
    if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
        jQuery('#botguard-dialog').on('show.bs.modal', function(event) {
            const button = jQuery(event.relatedTarget);
            const src = button.data('src');
            const modal = jQuery(this);
            modal.find('.botguard-frame').attr('src', src);
        });
        
        jQuery('#botguard-dialog').on('hidden.bs.modal', function() {
            jQuery(this).find('.botguard-frame').attr('src', 'about:blank');
        });
    }
});
</script>
