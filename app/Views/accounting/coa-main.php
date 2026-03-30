<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// Get account_id from URL for editing
$account_id = $this->request->getGet('account_id');

$account_code = "";
$account_name = "";
$account_type = "";
$parent_code = "";
$is_active = "1";

if(!empty($account_id)) { 
    $query = $this->db->query("SELECT * FROM tbl_coa WHERE account_id = '$account_id'");
    $data = $query->getRowArray();
    if($data) {
        $account_code = $data['account_code'] ?? '';
        $account_name = $data['account_name'] ?? '';
        $account_type = $data['account_type'] ?? '';
        $parent_code = $data['parent_code'] ?? '';
        $is_active = $data['is_active'] ?? 1;
    }
}

echo view('templates/myheader.php');
?>

<style>
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 50px;
        letter-spacing: 0.3px;
    }

    /* Dot indicator */
    .status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    /* ACTIVE (Green - subtle) */
    .status-active {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    .status-active::before {
        background: #198754;
    }

    /* INACTIVE (Red - subtle) */
    .status-inactive {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    .status-inactive::before {
        background: #dc3545;
    }
    
    /* Account Type Badges */
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        font-size: 10px;
        font-weight: 600;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }
    .type-asset { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .type-liability { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .type-equity { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .type-revenue { background: rgba(32, 201, 151, 0.1); color: #20c997; }
    .type-expense { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    
    /* Edit mode badge */
    .edit-mode-badge {
        background: #e9ecef;
        color: #0d6efd;
        font-size: 0.7rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    /* Account Tree Styles */
    .account-tree {
        font-size: 0.875rem;
    }
    
    .account-item {
        margin-bottom: 0.5rem;
    }
    
    .account-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .account-card:hover {
        border-color: #dee2e6;
        background: #f8f9fa;
    }
    
    .account-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .account-code {
        font-family: monospace;
        font-weight: 600;
        font-size: 0.75rem;
        background: #f8f9fa;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        color: #0d6efd;
    }
    
    .account-name {
        font-weight: 500;
        color: #212529;
    }
    
    .account-actions {
        display: flex;
        gap: 0.5rem;
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }
    
    .account-card:hover .account-actions {
        opacity: 1;
    }
    
    .action-icon {
        background: none;
        border: none;
        padding: 0.25rem;
        cursor: pointer;
        color: #6c757d;
        transition: all 0.2s ease;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-icon:hover {
        color: #0d6efd;
        background: #e9ecef;
    }
    
    /* Filter Buttons */
    .filter-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        border-radius: 20px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .filter-btn.active {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }
    
    .filter-btn:hover:not(.active) {
        background: #f8f9fa;
        border-color: #adb5bd;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
</style>

<div class="container-fluid">
    <div class="row me-mycoa-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <!-- Page Header -->
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Chart of Accounts</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Accounting</li>
                <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Chart of Accounts</span></li>
            </ol>
        </nav>
    </div>
    
    <!-- Stats Overview -->
    <?php
    // Get stats from database
    $totalAccountsQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa")->getRowArray();
    $activeAccountsQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE is_active = 1")->getRowArray();
    $assetCountQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE account_type = 'Asset'")->getRowArray();
    $expenseCountQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE account_type = 'Expense'")->getRowArray();
    ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($totalAccountsQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Total Accounts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($activeAccountsQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($assetCountQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Assets</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($expenseCountQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Expenses</div>
        </div>
    </div>
    
    <!-- Add/Edit Account Card (Matching Journal Entry Style) -->
    <div class="card">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1"><?= !empty($account_id) ? 'Edit Account' : 'Add New Account'; ?></span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end pe-3">
                    <?php if(!empty($account_id)): ?>
                        <a href="<?= site_url('mycoa?meaction=MAIN'); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-plus"></i> Add New
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0 px-4 py-2 my-2">
            <?php if(!empty($account_id)): ?>
                <div class="edit-mode-badge">
                    <i class="ti ti-edit"></i>
                    Editing: <?= $account_code; ?> - <?= $account_name; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?=site_url();?>mycoa?meaction=COA-SAVE" method="post" class="mycoa-validation">
                <input type="hidden" name="account_id" id="account_id" value="<?= $account_id; ?>">
                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-sm-6">
                        <div class="row mb-2 mt-2">
                            <div class="col-sm-4"><span>Account Code:</span></div>
                            <div class="col-sm-8">
                                <input type="text" name="account_code" id="account_code" class="form-control form-control-sm" value="<?= $account_code; ?>" placeholder="e.g., 1010" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Account Name:</span></div>
                            <div class="col-sm-8">
                                <input type="text" name="account_name" id="account_name" class="form-control form-control-sm" value="<?= $account_name; ?>" placeholder="e.g., Cash on Hand" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Account Type:</span></div>
                            <div class="col-sm-8">
                                <select name="account_type" id="account_type" class="form-select form-select-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="Asset" <?= $account_type == 'Asset' ? 'selected' : ''; ?>>Asset</option>
                                    <option value="Liability" <?= $account_type == 'Liability' ? 'selected' : ''; ?>>Liability</option>
                                    <option value="Equity" <?= $account_type == 'Equity' ? 'selected' : ''; ?>>Equity</option>
                                    <option value="Revenue" <?= $account_type == 'Revenue' ? 'selected' : ''; ?>>Revenue</option>
                                    <option value="Expense" <?= $account_type == 'Expense' ? 'selected' : ''; ?>>Expense</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-sm-6 my-2">
                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Parent Account:</span></div>
                            <div class="col-sm-8">
                                <select name="parent_code" id="parent_code" class="form-select form-select-sm">
                                    <option value="">— None (Main Account) —</option>
                                    <?php
                                    $parents = $this->db->query("SELECT account_code, account_name FROM tbl_coa WHERE account_code != '$account_code' OR account_code IS NULL ORDER BY account_code")->getResultArray();
                                    foreach($parents as $p) {
                                        $selected = ($parent_code == $p['account_code']) ? 'selected' : '';
                                        echo '<option value="' . $p['account_code'] . '" ' . $selected . '>' . $p['account_code'] . ' - ' . $p['account_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Status:</span></div>
                            <div class="col-sm-8">
                                <select name="is_active" id="is_active" class="form-select form-select-sm">
                                    <option value="1" <?= $is_active == '1' ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?= $is_active == '0' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="row mt-3">
                    <div class="col-sm-12 text-end">
                        <?php if(!empty($account_id)): ?>
                            <a href="<?= site_url('mycoa?meaction=MAIN'); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-x"></i> Cancel
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="btn bg-<?= empty($account_id) ? 'success' : 'info' ?>-subtle text-<?= empty($account_id) ? 'success' : 'info' ?> btn-sm">
                            <i class="ti ti-device-floppy mt-1 fs-4 me-1"></i>
                            <?= empty($account_id) ? 'Save Account' : 'Update Account'; ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Chart of Accounts Structure Card -->
    <div class="card mt-3">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-list-tree fs-5 me-1"></i>
                        <span class="pt-1">Chart of Accounts Structure</span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end pe-3">
                    <div class="filter-group">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <button class="filter-btn" data-filter="Asset">Assets</button>
                        <button class="filter-btn" data-filter="Liability">Liabilities</button>
                        <button class="filter-btn" data-filter="Equity">Equity</button>
                        <button class="filter-btn" data-filter="Revenue">Revenue</button>
                        <button class="filter-btn" data-filter="Expense">Expenses</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-2 px-4 py-3">
            <div class="account-tree" id="accountTree">
                <?php
                // Get all accounts with proper null checks
                $query = $this->db->query("SELECT * FROM tbl_coa ORDER BY account_code ASC");
                $accounts = $query->getResultArray();
                
                // Build tree structure
                $tree = [];
                foreach ($accounts as $row) {
                    $parentKey = isset($row['parent_code']) && !empty($row['parent_code']) ? $row['parent_code'] : null;
                    $tree[$parentKey][] = $row;
                }
                
                function renderTree($parent, $tree, $level = 0, $filter = 'all') {
                    if (!isset($tree[$parent])) return;
                    
                    foreach ($tree[$parent] as $row) {
                        $display = ($filter === 'all' || $row['account_type'] === $filter);
                        if(!$display && $filter !== 'all') continue;
                        
                        $account_code = $row['account_code'] ?? '';
                        $account_name = $row['account_name'] ?? '';
                        $account_type = $row['account_type'] ?? '';
                        $is_active = $row['is_active'] ?? 1;
                        $account_id = $row['account_id'] ?? '';
                        ?>
                        <div class="account-item" data-type="<?= $account_type; ?>" data-active="<?= $is_active; ?>" style="padding-left: <?= $level * 25 ?>px;">
                            <div class="account-card">
                                <div class="account-info">
                                    <span class="account-code"><?= $account_code; ?></span>
                                    <span class="account-name"><?= htmlspecialchars($account_name); ?></span>
                                    <span class="type-badge type-<?= strtolower($account_type); ?>">
                                        <?= $account_type; ?>
                                    </span>
                                    <span class="status-pill <?= $is_active ? 'status-active' : 'status-inactive'; ?>">
                                        <?= $is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="account-actions">
                                    <a href="<?= site_url('mycoa?meaction=MAIN&account_id=' . $account_id); ?>" class="action-icon" title="Edit Account">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                        renderTree($account_code, $tree, $level + 1, $filter);
                    }
                }
                ?>
                <div id="treeContent">
                    <?php renderTree(null, $tree, 0, 'all'); ?>
                </div>
                <?php if(empty($accounts)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="ti ti-folder-off fs-1 d-block mb-2"></i>
                        <p>No accounts found. Create your first account above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/accounting/mycoa.js?v=2');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<script>
$(document).ready(function() {
    // Filter functionality
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const filter = $(this).data('filter');
        
        if(filter === 'all') {
            $('.account-item').show();
        } else {
            $('.account-item').hide();
            $(`.account-item[data-type="${filter}"]`).show();
        }
    });
    
    <?php if(!empty($account_id)): ?>
        // Scroll to form on page load when editing
        setTimeout(function() {
            $('html, body').animate({
                scrollTop: $('.card').offset().top - 100
            }, 700);
        }, 300);
    <?php endif; ?>
});

__mysys_coa_ent.__coa_saving();
</script>

<?php
echo view('templates/myfooter.php');
?>