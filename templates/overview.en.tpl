{* BotGuard WHMCS Module - Client Area Overview Template (English) *}
{* Compatible with WHMCS 8.13 and Bootstrap 4/5 *}

<div class="modal fade" id="botguard-dialog" tabindex="-1" role="dialog" aria-labelledby="botguard-dialog-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="height:calc(100vh - 20%);max-width:1140px">
        <div class="modal-content" style="height:100%">
            <div class="modal-header">
                <h5 class="modal-title" id="botguard-dialog-title">BotGuard Management</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="height:calc(100% - 60px)">
                <iframe class="botguard-frame" title="BotGuard Management Interface"></iframe>
            </div>
        </div>
    </div>
</div>

{if $service_status == 'Active'}
<div class="product-details-tab-content">
    <h2 class="h4 mb-4">BotGuard Bot Management Overview</h2>

    <div class="alert alert-success" role="alert">
        <i class="fas fa-shield-alt me-2"></i>
        <strong>Protection Active!</strong> Your website is protected by BotGuard's advanced bot management system.
    </div>

    <p class="lead mb-4">Thank you for entrusting your web assets protection to BotGuard!</p>

    <div class="row mb-4">
        <div class="col-md-8">
            <p>Your website <strong><a href="http://{$botguard_domain}/" target="_blank" rel="noopener">{$botguard_domain}</a></strong> is actively protected against malicious bots, crawlers, and automated attacks.</p>
            
            <p>BotGuard provides real-time protection that:</p>
            <ul class="list-unstyled">
                <li><i class="fas fa-check text-success me-2"></i> Blocks malicious bot traffic</li>
                <li><i class="fas fa-check text-success me-2"></i> Reduces server load</li>
                <li><i class="fas fa-check text-success me-2"></i> Improves SEO performance</li>
                <li><i class="fas fa-check text-success me-2"></i> Provides accurate web analytics</li>
                <li><i class="fas fa-check text-success me-2"></i> Prevents content scraping</li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="fas fa-globe fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Protected Domain</h5>
                    <p class="card-text text-muted">{$botguard_domain}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="h5 mb-3">Integration Details</h3>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-server me-2"></i>Primary Server
                </div>
                <div class="card-body">
                    <code class="fs-6">{$primary_server}</code>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-server me-2"></i>Secondary Server
                </div>
                <div class="card-body">
                    <code class="fs-6">{$secondary_server}</code>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Need Help?</strong> Check our integration guide at 
        <a href="https://botguard.net/en/documentation/integration" target="_blank" rel="noopener" class="alert-link">
            https://botguard.net/en/documentation/integration
        </a>
    </div>

    {if $api_available}
    <h3 class="h5 mb-3">Management Tools</h3>
    <p>Use the buttons below to access real-time statistics, review protection events, and configure your bot management rules.</p>
    
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <button type="button" class="btn btn-success btn-lg w-100" 
                    data-toggle="modal" data-target="#botguard-dialog" 
                    data-src="{$show_stats_url}"
                    title="View detailed statistics about blocked bots and traffic">
                <i class="fas fa-chart-bar me-2"></i>
                Show Statistics
            </button>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-info btn-lg w-100" 
                    data-toggle="modal" data-target="#botguard-dialog" 
                    data-src="{$show_events_url}"
                    title="Review recent protection events and blocked attempts">
                <i class="fas fa-shield-alt me-2"></i>
                Protection Events
            </button>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-primary btn-lg w-100" 
                    data-toggle="modal" data-target="#botguard-dialog" 
                    data-src="{$manage_rules_url}"
                    title="Configure protection rules and settings">
                <i class="fas fa-cog me-2"></i>
                Manage Rules
            </button>
        </div>
    </div>
    {else}
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Management Access Unavailable</strong> 
        Management tools are currently not available. Please contact support if you need assistance.
    </div>
    {/if}

    <hr class="my-4">
    
    <div class="row">
        <div class="col-md-8">
            <h3 class="h6 mb-2">Need Support?</h3>
            <p class="text-muted">If you experience any issues with your BotGuard protection or need help with configuration, our support team is here to help.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/submitticket.php" class="btn btn-outline-primary">
                <i class="fas fa-life-ring me-2"></i>Contact Support
            </a>
        </div>
    </div>
</div>
{else}
<div class="alert alert-warning" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Service Not Active</strong> 
    Your BotGuard service is currently {$service_status|lower}. Protection features are not available at this time.
</div>
{/if}

<style>
.botguard-frame {
    width: 100%;
    height: 100%;
    border: 0;
    background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text x="50%" y="50%" font-family="sans-serif" font-size="18" text-anchor="middle" fill="%23666">Loading BotGuard Interface...</text></svg>') white 0px 0px no-repeat;
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
