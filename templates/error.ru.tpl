{* BotGuard WHMCS Module - Error Template (Russian) *}
{* Compatible with WHMCS 8.13 *}

<div class="product-details-tab-content">
    <div class="text-center mb-4">
        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
        <h2 class="h4">Упс! Что-то пошло не так.</h2>
    </div>

    <div class="alert alert-danger" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-times-circle me-3 fa-lg"></i>
            <div>
                <h5 class="alert-heading mb-2">Подробности ошибки</h5>
                <p class="mb-0">{$error_details}</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Что можно сделать?</h5>
                    <p class="card-text text-muted mb-4">
                        Эта ошибка обычно временная. Попробуйте выполнить следующие действия:
                    </p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button onclick="location.reload()" class="btn btn-primary w-100">
                                <i class="fas fa-redo me-2"></i>
                                Обновить страницу
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button onclick="history.back()" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>
                                Вернуться назад
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="text-center">
        <p class="text-muted mb-3">
            Если проблема сохраняется, наша служба поддержки готова помочь вам решить этот вопрос.
        </p>
        <a href="/submitticket.php" class="btn btn-success btn-lg">
            <i class="fas fa-life-ring me-2"></i>
            Связаться со службой поддержки
        </a>
    </div>

    <div class="mt-4">
        <details class="collapse-details">
            <summary class="btn btn-link text-muted p-0 border-0 bg-transparent">
                <i class="fas fa-info-circle me-2"></i>
                Техническая информация
            </summary>
            <div class="mt-3 p-3 bg-light rounded">
                <p class="small text-muted mb-2"><strong>Время возникновения ошибки:</strong> {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}</p>
                <p class="small text-muted mb-2"><strong>Модуль:</strong> BotGuard Bot Management</p>
                <p class="small text-muted mb-0"><strong>Ошибка:</strong> {$error_details}</p>
            </div>
        </details>
    </div>
</div>

<style>
.collapse-details summary {
    cursor: pointer;
    outline: none;
    font-size: 0.875rem;
}

.collapse-details summary:hover {
    text-decoration: underline;
}

.product-details-tab-content .card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.alert-danger {
    border-left: 4px solid #dc3545;
}

@media (max-width: 768px) {
    .fa-4x {
        font-size: 2.5rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on refresh button for accessibility
    const refreshBtn = document.querySelector('button[onclick="location.reload()"]');
    if (refreshBtn) {
        refreshBtn.focus();
    }
    
    // Add keyboard navigation for details
    const details = document.querySelector('details');
    if (details) {
        details.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.open = !this.open;
            }
        });
    }
});
</script>
