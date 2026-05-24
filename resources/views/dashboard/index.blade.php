<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Investor Dashboard | Aurum Gold</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('website/style.css') }}">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="/" class="sidebar-brand">
            Aurum <span>Gold</span>
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="#" onclick="showTab('dashboard')" class="sidebar-link active" id="sidebar-dashboard">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" onclick="showTab('vault')" class="sidebar-link" id="sidebar-vault">
                    <i class="fa-solid fa-vault"></i> Vault Storage
                </a>
            </li>
            <li>
                <a href="#" onclick="showTab('ira')" class="sidebar-link" id="sidebar-ira">
                    <i class="fa-solid fa-piggy-bank"></i> Gold IRA
                </a>
            </li>
            <li>
                <a href="#" onclick="showTab('settings')" class="sidebar-link" id="sidebar-settings">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
            </li>
            @if(auth()->user()->role === 'admin' || auth()->user()->hasRole('admin'))
            <li>
                <a href="#" onclick="showTab('admin')" class="sidebar-link" id="sidebar-admin">
                    <i class="fa-solid fa-user-shield"></i> Admin Control
                </a>
            </li>
            @endif
            <li style="margin-top: auto;">
                <a href="/logout" class="sidebar-link sidebar-logout">
                    <i class="fa-solid fa-right-from-bracket" style="color: #ff4d4d;"></i> <span style="color: #ff4d4d;">Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">

        <!-- Tab: Main Dashboard -->
        <div id="tab-dashboard" class="tab-pane active-tab">
            <header class="dashboard-header">
                <div class="welcome-msg">
                    <h1>Welcome Back, {{ auth()->user()->name }}</h1>
                    <p>Track your portfolio and manage your assets in real-time.</p>
                </div>
                <div style="display: flex; gap: 15px; align-items: center;">
                    <!-- Fulfillment Mode Toggle Segmented Control -->
                    <div class="fulfillment-selector-group" style="display: inline-flex; background: rgba(30, 41, 59, 0.6); border: 1px solid var(--border-color); padding: 4px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                        <button id="fulfillment-voucher-btn" onclick="setFulfillment('voucher')" style="font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 600; padding: 7px 15px; border-radius: 6px; border: none; background: var(--gold-light); color: #000; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); outline: none;">
                            <i class="fa-solid fa-ticket"></i> Voucher
                        </button>
                        <button id="fulfillment-deliver-btn" onclick="setFulfillment('deliver')" style="font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 500; padding: 7px 15px; border-radius: 6px; border: none; background: transparent; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); outline: none;">
                            <i class="fa-solid fa-truck-fast"></i> Deliver
                        </button>
                    </div>

                    <button class="btn btn-primary" onclick="depositFunds()">
                        <i class="fa-solid fa-plus"></i> Add Fund
                    </button>
                </div>
            </header>

            <!-- Chart & Wallet Summary Grid -->
            <div class="dashboard-grid">
                <!-- Live Precious Metals Chart -->
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-chart-area"></i> Live Metals Price Chart (USD / g)
                    </div>
                    <div style="position: relative; height: 350px;">
                        <canvas id="metalsChart"></canvas>
                    </div>
                </div>

                <!-- Fiat Wallet Summary -->
                <div class="card" style="display: flex; flex-direction: column; justify-content: center;">
                    <div class="card-title" style="margin-bottom: 15px;">
                        <i class="fa-solid fa-dollar-sign"></i> Available Wallet Balance
                    </div>
                    <div style="font-size: 48px; font-weight: 300; margin-bottom: 20px; color: var(--gold-light); display: flex; align-items: center; gap: 15px;">
                        <span id="fiatBalance" data-balance="{{ $wallet->balance }}">••••••</span>
                        <button id="toggleBalanceBtn" onclick="toggleBalanceVisibility()" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 24px; display: inline-flex; align-items: center; justify-content: center; outline: none; transition: color 0.3s ease;">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 30px;">
                        Deposit fiat funds instantly using your credit card or bank transfer. Use these funds to buy gold, silver, platinum, or palladium in seconds.
                    </p>
                    <div class="trade-buttons">
                        <button class="btn btn-primary" onclick="depositFunds()" style="width: 100%;">
                            <i class="fa-solid fa-building-columns"></i> Deposit Funds
                        </button>
                    </div>
                </div>
            </div>

            <!-- Portfolios Grid for All Materials -->
            <h2 style="font-size: 22px; font-weight: 600; margin-bottom: 20px; color: var(--text-main);">
                <i class="fa-solid fa-cubes-stacked" style="color: var(--gold-light); margin-right: 10px;"></i> Precious Metals Portfolio
            </h2>

            <div class="portfolio-grid">
                <!-- Gold Card -->
                <div class="metal-card">
                    <div class="metal-info">
                        <div class="metal-name" style="color: var(--gold-light);">
                            <i class="fa-solid fa-coins"></i> Gold
                        </div>
                        <div class="metal-price" id="goldLivePrice">
                            ${{ number_format($metalPrices['gold'], 2) }} / g
                        </div>
                    </div>
                    <div class="metal-balance" id="goldBalance">
                        {{ number_format($wallet->gold_balance, 4) }} g
                    </div>
                    <div class="trade-buttons">
                        <button class="btn btn-primary" onclick="tradeMetal('gold', 'buy')">Buy</button>
                        <button class="btn btn-outline" onclick="tradeMetal('gold', 'sell')">Sell</button>
                    </div>
                </div>

                <!-- Silver Card -->
                <div class="metal-card">
                    <div class="metal-info">
                        <div class="metal-name" style="color: var(--silver-color);">
                            <i class="fa-solid fa-circle"></i> Silver
                        </div>
                        <div class="metal-price" id="silverLivePrice">
                            ${{ number_format($metalPrices['silver'], 2) }} / g
                        </div>
                    </div>
                    <div class="metal-balance" id="silverBalance">
                        {{ number_format($wallet->silver_balance, 4) }} g
                    </div>
                    <div class="trade-buttons">
                        <button class="btn btn-primary" onclick="tradeMetal('silver', 'buy')">Buy</button>
                        <button class="btn btn-outline" onclick="tradeMetal('silver', 'sell')">Sell</button>
                    </div>
                </div>

                <!-- Platinum Card -->
                <div class="metal-card">
                    <div class="metal-info">
                        <div class="metal-name" style="color: var(--platinum-color);">
                            <i class="fa-solid fa-gem"></i> Platinum
                        </div>
                        <div class="metal-price" id="platinumLivePrice">
                            ${{ number_format($metalPrices['platinum'], 2) }} / g
                        </div>
                    </div>
                    <div class="metal-balance" id="platinumBalance">
                        {{ number_format($wallet->platinum_balance, 4) }} g
                    </div>
                    <div class="trade-buttons">
                        <button class="btn btn-primary" onclick="tradeMetal('platinum', 'buy')">Buy</button>
                        <button class="btn btn-outline" onclick="tradeMetal('platinum', 'sell')">Sell</button>
                    </div>
                </div>

                <!-- Palladium Card -->
                <div class="metal-card">
                    <div class="metal-info">
                        <div class="metal-name" style="color: var(--palladium-color);">
                            <i class="fa-solid fa-shield-halved"></i> Palladium
                        </div>
                        <div class="metal-price" id="palladiumLivePrice">
                            ${{ number_format($metalPrices['palladium'], 2) }} / g
                        </div>
                    </div>
                    <div class="metal-balance" id="palladiumBalance">
                        {{ number_format($wallet->palladium_balance, 4) }} g
                    </div>
                    <div class="trade-buttons">
                        <button class="btn btn-primary" onclick="tradeMetal('palladium', 'buy')">Buy</button>
                        <button class="btn btn-outline" onclick="tradeMetal('palladium', 'sell')">Sell</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Vault Storage -->
        <div id="tab-vault" class="tab-pane" style="display: none;">
            <header class="dashboard-header">
                <div class="welcome-msg">
                    <h1><i class="fa-solid fa-vault" style="color: var(--gold-light);"></i> Vault Storage Center</h1>
                    <p>Track, audit, and withdraw physical bars stored in ultra-secure, IRS-approved class 3 depositories.</p>
                </div>
            </header>

            <!-- Vault Summary Stats -->
            <div class="portfolio-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 30px; gap: 20px;">
                <div class="metal-card" style="padding: 20px; border-left: 4px solid var(--gold-light); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Vault Location</div>
                        <div style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin-top: 8px;">Brink's NY & DE</div>
                    </div>
                    <div style="font-size: 11px; color: #4A7C59; margin-top: 15px;"><i class="fa-solid fa-circle-check"></i> Segregated Custody</div>
                </div>

                <div class="metal-card" style="padding: 20px; border-left: 4px solid var(--gold-light); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Stored Weight</div>
                        <div style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin-top: 8px;">{{ number_format($totalWeightKg, 4) }} kg</div>
                    </div>
                    <div style="font-size: 11px; color: var(--gold-light); margin-top: 15px;"><i class="fa-solid fa-scale-balanced"></i> Of total portfolio holdings</div>
                </div>

                <div class="metal-card" style="padding: 20px; border-left: 4px solid var(--gold-light); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Vault Storage Space</div>
                        <div style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin-top: 8px;">
                            {{ number_format(min($totalWeightKg, $storageLimitKg), 2) }} / {{ number_format($storageLimitKg, 1) }} kg
                        </div>
                        <!-- Space Capacity Progress Bar -->
                        <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.08); border-radius: 4px; margin-top: 10px; overflow: hidden;">
                            <div style="width: {{ min(100, ($totalWeightKg / $storageLimitKg) * 100) }}%; height: 100%; background: {{ $totalWeightKg > $storageLimitKg ? '#ff4d4d' : 'var(--gold-light)' }}; border-radius: 4px; transition: width 0.5s ease;"></div>
                        </div>
                    </div>
                    <div style="font-size: 11px; color: {{ $totalWeightKg > $storageLimitKg ? '#ff4d4d' : 'var(--text-muted)' }}; margin-top: 12px;">
                        @if($totalWeightKg > $storageLimitKg)
                            <i class="fa-solid fa-triangle-exclamation"></i> Storage limit exceeded by {{ number_format($totalWeightKg - $storageLimitKg, 2) }} kg
                        @else
                            <i class="fa-solid fa-circle-check"></i> Within free storage space allocation
                        @endif
                    </div>
                </div>

                <div class="metal-card" style="padding: 20px; border-left: 4px solid #ff4d4d; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Extra Space Surcharge</div>
                        <div style="font-size: 20px; font-weight: 600; color: {{ $monthlyExtraFee > 0 ? '#ff4d4d' : 'var(--text-primary)' }}; margin-top: 8px;">
                            ${{ number_format($monthlyExtraFee, 2) }} / mo
                        </div>
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 15px;">
                        Rate: ${{ number_format($extraStoragePrice, 2) }} / kg extra
                    </div>
                </div>
            </div>

            <!-- Vault Inventory Grid -->
            <div class="dashboard-grid">
                <!-- Stored Inventory List -->
                <div class="card" style="padding: 25px;">
                    <div class="card-title" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa-solid fa-list-check" style="color: var(--gold-light);"></i> Active Serialized Inventory</span>
                        <span style="font-size: 12px; background: rgba(74,124,89,0.15); color: #4A7C59; padding: 4px 10px; border-radius: 4px;">Audited</span>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-secondary);">
                                    <th style="padding: 12px 8px;">Asset / Weight</th>
                                    <th style="padding: 12px 8px;">Serial Number</th>
                                    <th style="padding: 12px 8px;">Mint / Origin</th>
                                    <th style="padding: 12px 8px;">Location</th>
                                    <th style="padding: 12px 8px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);" id="vault-bar-1">
                                    <td style="padding: 16px 8px; font-weight: 500;"><i class="fa-solid fa-coins" style="color: var(--gold-light); margin-right: 8px;"></i> 100g Gold Bar</td>
                                    <td style="padding: 16px 8px; font-family: monospace; color: var(--gold-light);">#VC-882941</td>
                                    <td style="padding: 16px 8px; color: var(--text-secondary);">Valcambi Suisse</td>
                                    <td style="padding: 16px 8px; color: var(--text-secondary);">Brink's New York</td>
                                    <td style="padding: 16px 8px;" class="status-cell"><span style="background: rgba(74,124,89,0.15); color: #4A7C59; padding: 2px 8px; border-radius: 4px; font-size: 12px;">In Vault</span></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);" id="vault-bar-2">
                                    <td style="padding: 16px 8px; font-weight: 500;"><i class="fa-solid fa-coins" style="color: var(--gold-light); margin-right: 8px;"></i> 50g Gold Bar</td>
                                    <td style="padding: 16px 8px; font-family: monospace; color: var(--gold-light);">#PS-493021</td>
                                    <td style="padding: 16px 8px; color: var(--text-secondary);">PAMP Suisse</td>
                                    <td style="padding: 16px 8px; color: var(--text-secondary);">Brink's New York</td>
                                    <td style="padding: 16px 8px;" class="status-cell"><span style="background: rgba(74,124,89,0.15); color: #4A7C59; padding: 2px 8px; border-radius: 4px; font-size: 12px;">In Vault</span></td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);" id="vault-bar-3">
                                    <td style="padding: 16px 8px; font-weight: 500;"><i class="fa-solid fa-circle" style="color: var(--silver-color); margin-right: 8px;"></i> 1 Kilo Silver Bar</td>
                                    <td style="padding: 16px 8px; font-family: monospace; color: var(--silver-color);">#PM-990238</td>
                                    <td style="padding: 16px 8px; color: var(--text-secondary);">Perth Mint</td>
                                    <td style="padding: 16px 8px; color: var(--text-secondary);">Delaware Depository</td>
                                    <td style="padding: 16px 8px;" class="status-cell"><span style="background: rgba(74,124,89,0.15); color: #4A7C59; padding: 2px 8px; border-radius: 4px; font-size: 12px;">In Vault</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Monthly Invoices & Delivery Requests -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Invoice Panel -->
                    <div class="card" style="padding: 25px;">
                        <div class="card-title" style="margin-bottom: 15px;"><i class="fa-solid fa-receipt" style="color: var(--gold-light);"></i> Vault Storage Invoice</div>
                        <div id="invoiceContainer" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 6px; padding: 15px; text-align: center;">
                            <div style="font-size: 12px; color: var(--text-secondary); text-transform: uppercase;">May 2026 Storage Fee</div>
                            @if($monthlyExtraFee > 0)
                                <div style="font-size: 32px; font-weight: 500; color: #ff4d4d; margin: 8px 0;" id="invoiceAmountVal" data-amount="{{ $monthlyExtraFee }}">${{ number_format($monthlyExtraFee, 2) }}</div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 15px;">Calculated fee: ${{ number_format($extraStoragePrice, 2) }} per kg over your {{ number_format($storageLimitKg, 1) }} kg free space.</div>
                                <button class="btn btn-primary" onclick="payStorageInvoice()" style="width: 100%;" id="payInvoiceBtn"><i class="fa-solid fa-credit-card"></i> Pay from Wallet</button>
                            @else
                                <div style="font-size: 32px; font-weight: 500; color: #4A7C59; margin: 8px 0;" id="invoiceAmountVal" data-amount="0">$0.00</div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 15px;">You are currently within your {{ number_format($storageLimitKg, 1) }} kg free space limit. No fee due.</div>
                                <button class="btn btn-outline" style="width: 100%; opacity: 0.6; cursor: not-allowed;" disabled><i class="fa-solid fa-circle-check"></i> Account Up to Date</button>
                            @endif
                        </div>
                    </div>

                    <!-- Delivery Request -->
                    <div class="card" style="padding: 25px;">
                        <div class="card-title" style="margin-bottom: 15px;"><i class="fa-solid fa-truck-ramp-box" style="color: var(--gold-light);"></i> Physical Delivery Request</div>
                        <form id="vaultDeliveryForm" onsubmit="requestPhysicalDelivery(event)">
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label class="form-label" style="font-size: 12px;">Select Stored Asset to Withdraw</label>
                                <select class="form-control" id="deliveryAsset" style="padding-left: 10px;" required>
                                    <option value="1">#VC-882941 — 100g Gold Bar (Valcambi)</option>
                                    <option value="2">#PS-493021 — 50g Gold Bar (PAMP)</option>
                                    <option value="3">#PM-990238 — 1 Kilo Silver Bar (Perth Mint)</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label class="form-label" style="font-size: 12px;">Delivery Address</label>
                                <input type="text" class="form-control" placeholder="123 Financial Blvd, New York, NY" style="padding-left: 10px;" required>
                            </div>
                            <button type="submit" class="btn btn-outline" style="width: 100%; border-color: var(--gold-light); color: var(--gold-light);"><i class="fa-solid fa-truck-fast"></i> Schedule Secured Delivery</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Gold IRA -->
        <div id="tab-ira" class="tab-pane" style="display: none;">
            <header class="dashboard-header">
                <div class="welcome-msg">
                    <h1><i class="fa-solid fa-piggy-bank" style="color: var(--gold-light);"></i> Self-Directed Gold & Silver IRA</h1>
                    <p>Roll over standard retirement accounts (401k/IRA) into tax-advantaged physical precious metals portfolios.</p>
                </div>
            </header>

            <!-- IRA Flow Wizard -->
            <div class="card" style="padding: 30px; margin-bottom: 30px;" id="iraWizardCard">
                <div class="wizard-steps" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
                    <div class="step-indicator active-step" id="stepIndicator1" style="display: flex; align-items: center; gap: 10px;">
                        <span style="width: 32px; height: 32px; border-radius: 50%; background: var(--gold-light); color: #000; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">1</span>
                        <div>
                            <div style="font-size: 13px; font-weight: 600;">Account Setup</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Choose type & custodian</div>
                        </div>
                    </div>
                    <div class="step-indicator" id="stepIndicator2" style="display: flex; align-items: center; gap: 10px; opacity: 0.4;">
                        <span style="width: 32px; height: 32px; border-radius: 50%; background: #334155; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">2</span>
                        <div>
                            <div style="font-size: 13px; font-weight: 600;">Fund Account</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Custodian transfer request</div>
                        </div>
                    </div>
                    <div class="step-indicator" id="stepIndicator3" style="display: flex; align-items: center; gap: 10px; opacity: 0.4;">
                        <span style="width: 32px; height: 32px; border-radius: 50%; background: #334155; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">3</span>
                        <div>
                            <div style="font-size: 13px; font-weight: 600;">Invest & Store</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Buy IRS-Approved metals</div>
                        </div>
                    </div>
                </div>

                <!-- Wizard Step Content 1 -->
                <div class="wizard-step-content" id="wizardStep1">
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 20px; color: var(--gold-light);"><i class="fa-solid fa-folder-open"></i> Step 1: Select IRA Type & Partner Custodian</h3>
                    <form id="iraSetupForm" onsubmit="submitIraStep1(event)">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label" style="font-size: 12px; margin-bottom: 8px; display: block;">Retirement Account Type</label>
                                <select class="form-control" id="iraType" style="padding-left: 10px;" required>
                                    <option value="traditional_ira">Traditional Self-Directed IRA</option>
                                    <option value="roth_ira">Roth Self-Directed IRA</option>
                                    <option value="sep_ira">SEP Self-Directed IRA</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="font-size: 12px; margin-bottom: 8px; display: block;">IRS-Approved Trustee Custodian</label>
                                <select class="form-control" id="iraCustodian" style="padding-left: 10px;" required>
                                    <option value="strata">STRATA Trust Company</option>
                                    <option value="equity">Equity Trust Company</option>
                                    <option value="goldstar">GoldStar Trust Company</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label class="form-label" style="font-size: 12px; margin-bottom: 8px; display: block;">Estimated Rollover/Transfer Amount (USD)</label>
                            <div class="form-input-wrapper">
                                <span>$</span>
                                <input type="number" id="iraRolloverAmount" min="5000" step="100" class="form-control" placeholder="25,000.00" required>
                            </div>
                            <small style="color: var(--text-muted); font-size: 11px; margin-top: 5px; display: block;">Standard minimum Gold IRA rollover is typically $5,000.</small>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-arrow-right"></i> Next: Initiate Custodian Transfer Paperwork</button>
                    </form>
                </div>

                <!-- Wizard Step Content 2 -->
                <div class="wizard-step-content" id="wizardStep2" style="display: none; text-align: center;">
                    <div style="font-size: 48px; color: var(--gold-light); margin-bottom: 20px;"><i class="fa-solid fa-file-signature"></i></div>
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 10px; color: var(--gold-light);">Step 2: Sign Direct Transfer Documents</h3>
                    <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto 20px; font-size: 14px;">
                        We have generated your custom retirement transfer application for <strong id="selectedCustodianText">STRATA Trust</strong>. A direct request is prepared for your current 401(k) / Fidelity portfolio worth <strong id="requestedRolloverText">$25,000.00</strong>.
                    </p>
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 6px; padding: 15px; display: inline-flex; align-items: center; gap: 15px; margin-bottom: 25px; text-align: left;">
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><i class="fa-regular fa-file-pdf" style="color: #ff4d4d;"></i> Pre_Filled_Custodian_Rollover.pdf</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Pre-filled with client ID and estimated rollover fund balance.</div>
                        </div>
                        <button class="btn btn-outline" style="font-size: 12px; padding: 6px 12px;" onclick="swalInfo('Downloading PDF form...')">Download Form</button>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="submitIraStep2()" style="min-width: 300px;"><i class="fa-solid fa-circle-check"></i> Sign & Submit Rollover Request</button>
                    </div>
                </div>

                <!-- Wizard Step Content 3 (Success Account View) -->
                <div class="wizard-step-content" id="wizardStep3" style="display: none; text-align: center;">
                    <div style="font-size: 48px; color: #4A7C59; margin-bottom: 20px;"><i class="fa-solid fa-circle-check"></i></div>
                    <h3 style="font-size: 20px; font-weight: 500; color: #4A7C59; margin-bottom: 10px;">Gold IRA Account Successfully Active!</h3>
                    <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto 30px; font-size: 14px;">
                        Your retirement transfer has cleared and your Gold IRA custodian account is active and funded with <strong class="ira-funded-balance">$25,000.00</strong>. You are cleared to invest in tax-sheltered, physical precious metals!
                    </p>

                    <!-- IRS Approved Store Grid -->
                    <h3 style="font-size: 16px; font-weight: 600; text-align: left; margin-bottom: 15px; border-top: 1px solid var(--border-color); padding-top: 25px;"><i class="fa-solid fa-gem" style="color: var(--gold-light);"></i> IRS-Approved Qualified Precious Metals</h3>
                    <div class="portfolio-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); text-align: left;">
                        <div class="metal-card" style="padding: 15px;">
                            <div style="font-size: 13px; font-weight: 600; color: var(--gold-light);">1 oz American Eagle Gold</div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">Purity: 91.6% (IRS Approved Exception)</div>
                            <button class="btn btn-outline" style="font-size: 12px; width: 100%; padding: 6px;" onclick="buyIraBullion('1 oz American Gold Eagle', 2350)">Invest $2,350.00</button>
                        </div>
                        <div class="metal-card" style="padding: 15px;">
                            <div style="font-size: 13px; font-weight: 600; color: var(--gold-light);">1 oz Canadian Maple Leaf Gold</div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">Purity: 99.99% (IRS Standard Approved)</div>
                            <button class="btn btn-outline" style="font-size: 12px; width: 100%; padding: 6px;" onclick="buyIraBullion('1 oz Gold Maple Leaf', 2380)">Invest $2,380.00</button>
                        </div>
                        <div class="metal-card" style="padding: 15px;">
                            <div style="font-size: 13px; font-weight: 600; color: var(--silver-color);">10 oz Silver Buffalo Bar</div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">Purity: 99.9% (IRS Standard Approved)</div>
                            <button class="btn btn-outline" style="font-size: 12px; width: 100%; padding: 6px;" onclick="buyIraBullion('10 oz Silver Buffalo Bar', 320)">Invest $320.00</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Settings -->
        <div id="tab-settings" class="tab-pane" style="display: none;">
            <header class="dashboard-header">
                <div class="welcome-msg">
                    <h1><i class="fa-solid fa-gear" style="color: var(--gold-light);"></i> Account Settings</h1>
                    <p>Manage your account credentials, security settings, and profile activation status.</p>
                </div>
            </header>

            <div class="dashboard-grid">
                <!-- Settings Form Card -->
                <div class="card" style="padding: 30px;">
                    <div class="card-title" style="margin-bottom: 20px;"><i class="fa-solid fa-user-lock" style="color: var(--gold-light);"></i> Security Credentials</div>
                    <form id="inlineSettingsForm" onsubmit="submitInlineSettings(event)">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" style="font-size: 13px;">Email Address</label>
                            <input type="email" id="inlineSettingsEmail" class="form-control" style="padding-left: 12px;" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" style="font-size: 13px;">
                                <i class="fa-solid fa-location-dot" style="color: var(--gold-light); margin-right: 6px;"></i>
                                Address
                                <span style="font-size: 11px; color: var(--text-muted); margin-left: 6px;">(Pre-filled as pick point on Deliver mode)</span>
                            </label>
{{--                            <input type="text" id="inlineSettingsAddress" class="form-control" style="padding-left: 12px;" placeholder="e.g. 742 Evergreen Terrace, Springfield, IL" value="{{ auth()->user()->address ?? '' }}">--}}
                            <input
                                type="text"
                                id="inlineSettingsAddress"
                                name="address"
                                class="form-control"
                                placeholder="e.g. 742 Evergreen Terrace, Springfield, IL"
                                value="{{ auth()->user()->address ?? '' }}"
                                style="padding-left: 12px;"
                            >
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">

                            <input type="hidden" id="city" name="city">
                            <input type="hidden" id="state" name="state">
                            <input type="hidden" id="zip_code" name="zip_code">
                            <input type="hidden" id="country" name="country">

                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" style="font-size: 13px;">Current Password <span style="font-size: 11px; color: var(--text-muted);">(Required to change password)</span></label>
                            <input type="password" id="inlineSettingsCurrentPassword" class="form-control" style="padding-left: 12px;" placeholder="••••••••">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" style="font-size: 13px;">New Password</label>
                            <input type="password" id="inlineSettingsNewPassword" class="form-control" style="padding-left: 12px;" placeholder="Minimum 6 characters">
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label class="form-label" style="font-size: 13px;">Confirm New Password</label>
                            <input type="password" id="inlineSettingsNewPasswordConfirmation" class="form-control" style="padding-left: 12px;" placeholder="Confirm new password">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-circle-check"></i> Save Profile Settings</button>
                    </form>
                </div>

                <!-- Account Management & Deactivation -->
                <div class="card" style="padding: 30px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="card-title" style="margin-bottom: 20px; color: #ff4d4d;"><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</div>
                        <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 10px; color: var(--text-primary);">Deactivate Account</h4>
                        <p style="color: var(--text-secondary); font-size: 13px; line-height: 1.6; margin-bottom: 20px;">
                            By deactivating your investor account, you will immediately terminate your active portal access. All funds and holdings remain securely stored offline in standard vault protocols. You will be logged out instantly.
                        </p>
                    </div>
                    <button class="btn btn-outline" onclick="deactivateAccount()" style="color: #ff4d4d; border-color: rgba(255, 77, 77, 0.4); width: 100%;"><i class="fa-solid fa-user-slash"></i> Deactivate Account</button>
                </div>
            </div>
        </div>

        <!-- Tab: Admin Control (Only visible to Admin) -->
        @if(auth()->user()->role === 'admin' || auth()->user()->hasRole('admin'))
        <div id="tab-admin" class="tab-pane" style="display: none;">
            <header class="dashboard-header">
                <div class="welcome-msg">
                    <h1><i class="fa-solid fa-user-shield" style="color: var(--gold-light);"></i> Admin Console</h1>
                    <p>Adjust system-wide free storage space ceilings, extra-capacity billing rates, and company base shop address.</p>
                </div>
            </header>

            <div class="dashboard-grid">
                <!-- Storage Control Panel Card -->
                <div class="card" style="padding: 30px;">
                    <div class="card-title" style="margin-bottom: 20px;"><i class="fa-solid fa-gears" style="color: var(--gold-light);"></i> Global System Settings</div>
                    <form id="adminSettingsForm" onsubmit="submitAdminSettings(event)">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" style="font-size: 13px;">Corporate Shop Location (Directions Origin)</label>
                            <input type="text" id="adminShopLocation" class="form-control" style="padding-left: 12px;" value="{{ $shopLocation }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" style="font-size: 13px;">Free Storage Space Limit (kg)</label>
                            <input type="number" step="0.1" id="adminStorageLimit" class="form-control" style="padding-left: 12px;" value="{{ $storageLimitKg }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label class="form-label" style="font-size: 13px;">Extra Capacity Surcharge Rate ($ / kg per month)</label>
                            <input type="number" step="0.01" id="adminExtraStoragePrice" class="form-control" style="padding-left: 12px;" value="{{ $extraStoragePrice }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-circle-check"></i> Apply Global Configuration</button>
                    </form>
                </div>

                <!-- Admin Information Card -->
                <div class="card" style="padding: 30px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="card-title" style="margin-bottom: 20px;"><i class="fa-solid fa-circle-info" style="color: var(--gold-light);"></i> Operations Summary</div>
                        <p style="color: var(--text-secondary); font-size: 13px; line-height: 1.6; margin-bottom: 20px;">
                            Updating these variables will immediately alter the free-tier parameters and direction plot origins for all active users across the platform. Surcharges are calculated automatically when portfolios exceed the free capacity ceiling.
                        </p>
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 6px; padding: 15px; font-size: 13px; color: var(--text-muted);">
                            <div style="margin-bottom: 8px;"><strong>Active Limit:</strong> {{ number_format($storageLimitKg, 1) }} kg</div>
                            <div style="margin-bottom: 8px;"><strong>Extra Surcharge:</strong> ${{ number_format($extraStoragePrice, 2) }} / kg</div>
                            <div><strong>Base Shop:</strong> {{ $shopLocation }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </main>

    <!-- Custom ChartJS Configuration -->
    <script>
        $(document).ready(function() {
            const ctx = document.getElementById('metalsChart').getContext('2d');

            // Raw chart data injected securely from controller
            const chartData = {!! json_encode($chartData) !!};

            const labels = ['6d ago', '5d ago', '4d ago', '3d ago', '2d ago', 'Yesterday', 'Today'];

            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Gold',
                        data: chartData.gold,
                        borderColor: '#f5c469',
                        backgroundColor: 'rgba(245, 196, 105, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Silver',
                        data: chartData.silver,
                        borderColor: '#a6a6a6',
                        backgroundColor: 'rgba(166, 166, 166, 0.05)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Platinum',
                        data: chartData.platinum,
                        borderColor: '#e5e5e5',
                        backgroundColor: 'rgba(229, 229, 229, 0.05)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Palladium',
                        data: chartData.palladium,
                        borderColor: '#8c8c8c',
                        backgroundColor: 'rgba(140, 140, 140, 0.05)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            };

            window.metalsChartInstance = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#9ca3af',
                                font: {
                                    family: "'Outfit', sans-serif",
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#1e293b'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    family: "'Outfit', sans-serif"
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: '#1e293b'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    family: "'Outfit', sans-serif"
                                }
                            }
                        }
                    }
                }
            });
        });

        // ─── SweetAlert2 Helpers (themed for Aurum Gold dark design) ───
        const swalTheme = {
            background: '#0f172a',
            color: '#e2e8f0',
            confirmButtonColor: '#f5c469',
            cancelButtonColor: '#334155',
        };
        function swalSuccess(title, text) {
            Swal.fire({ ...swalTheme, icon: 'success', title: title, text: text || '', timer: 3000, timerProgressBar: true, showConfirmButton: false });
        }
        function swalError(title, text) {
            Swal.fire({ ...swalTheme, icon: 'error', title: title || 'Error', text: text || '' });
        }
        function swalInfo(text) {
            Swal.fire({ ...swalTheme, icon: 'info', title: 'Info', text: text });
        }
        function swalConfirm(title, text, callback) {
            Swal.fire({
                ...swalTheme, icon: 'warning', title: title, text: text,
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel',
            }).then((result) => { if (result.isConfirmed) callback(); });
        }

        // Tab toggling logic
        function showTab(tabName) {
            $('.tab-pane').hide();
            $(`#tab-${tabName}`).show();

            $('.sidebar-link').removeClass('active');
            $(`#sidebar-${tabName}`).addClass('active');
        }

        // Modal toggling handlers
        function openModal(modalId) {
            $(`#${modalId}ModalOverlay`).addClass('active');
        }

        function closeModal(modalId) {
            $(`#${modalId}ModalOverlay`).removeClass('active');
        }

        // Toggling show/hide balance
        let balanceHidden = true;
        function toggleBalanceVisibility() {
            balanceHidden = !balanceHidden;
            const textEl = $('#fiatBalance');
            const iconEl = $('#toggleBalanceBtn i');
            const balanceVal = textEl.attr('data-balance');

            if (balanceHidden) {
                textEl.text('••••••');
                iconEl.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                textEl.text(`$${parseFloat(balanceVal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
                iconEl.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        }

        // Trigger Deposit Modal
        function depositFunds() {
            $('#depositAmount').val('');
            openModal('deposit');
        }

        function submitDeposit(e) {
            e.preventDefault();
            const amount = $('#depositAmount').val();
            if (!amount || isNaN(amount) || amount <= 0) return;

            $.ajax({
                url: '/wallet/deposit',
                method: 'POST',
                data: { amount: parseFloat(amount), _token: '{{ csrf_token() }}' },
                success: function(data) {
                    closeModal('deposit');
                    swalSuccess('Deposit Successful!', `$${parseFloat(amount).toFixed(2)} has been added to your wallet.`);
                    updateBalances(data);
                },
                error: function(xhr) {
                    const d = xhr.responseJSON;
                    swalError('Deposit Failed', d && d.message ? d.message : 'Unknown error occurred.');
                }
            });
        }

        // Trigger Trade Modal
        function tradeMetal(metal, action) {
            $('#tradeMetalType').val(metal);
            $('#tradeActionType').val(action);
            $('#tradeQuantity').val('');

            const capitalizedMetal = metal.charAt(0).toUpperCase() + metal.slice(1);
            const capitalizedAction = action.charAt(0).toUpperCase() + action.slice(1);

            $('#tradeModalTitle').html(`<i class="fa-solid fa-scale-balanced"></i> ${capitalizedAction} ${capitalizedMetal}`);
            $('#tradeModalDesc').text(`Enter the quantity of ${metal} you wish to ${action}.`);
            $('#tradeSubmitBtn').text(`Confirm ${capitalizedAction}`);

            openModal('trade');
        }

        let currentFulfillmentMode = 'voucher';
        function setFulfillment(mode) {
            currentFulfillmentMode = mode;
            if (mode === 'voucher') {
                $('#fulfillment-voucher-btn').css('background', 'var(--gold-light)').css('color', '#000').css('font-weight', '600');
                $('#fulfillment-deliver-btn').css('background', 'transparent').css('color', 'var(--text-secondary)').css('font-weight', '500');
            } else {
                $('#fulfillment-deliver-btn').css('background', 'var(--gold-light)').css('color', '#000').css('font-weight', '600');
                $('#fulfillment-voucher-btn').css('background', 'transparent').css('color', 'var(--text-secondary)').css('font-weight', '500');
            }
        }

        function submitTrade(e) {
            e.preventDefault();
            const metal = $('#tradeMetalType').val();
            const action = $('#tradeActionType').val();
            const qty = $('#tradeQuantity').val();

            if (!qty || isNaN(qty) || qty <= 0) return;

            const endpoint = action === 'buy' ? '/trade/buy' : '/trade/sell';

            $.ajax({
                url: endpoint,
                method: 'POST',
                data: {
                    quantity: parseFloat(qty),
                    metal: metal,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    closeModal('trade');
                    updateBalances(data);

                    if (action === 'buy') {
                        if (currentFulfillmentMode === 'voucher') {
                            const randId = 'AG-' + (new Date().getFullYear()) + '-' + Math.floor(10000 + Math.random() * 90000);
                            $('#voucherReceiptId').text(randId);
                            $('#voucherMetalName').text(metal.charAt(0).toUpperCase() + metal.slice(1) + ' Bullion Asset');
                            $('#voucherWeight').text(parseFloat(qty).toFixed(4) + ' grams');
                            openModal('voucher');
                        } else {
                            openModal('deliveryMap');
                            setTimeout(function() { calculateTransitRoute(); }, 400);
                        }
                    } else {
                        swalSuccess('Trade Executed!', data.message);
                    }
                },
                error: function(xhr) {
                    const d = xhr.responseJSON;
                    swalError('Trade Failed', d && d.message ? d.message : 'Insufficient balance or an error occurred.');
                }
            });
        }

        // Helper to update DOM elements
        function updateBalances(data) {
            $('#fiatBalance').attr('data-balance', data.fiat_balance);
            if (!balanceHidden) {
                $('#fiatBalance').text(`$${parseFloat(data.fiat_balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
            }
            $('#goldBalance').text(`${parseFloat(data.gold_balance).toFixed(4)} g`);
            $('#silverBalance').text(`${parseFloat(data.silver_balance).toFixed(4)} g`);
            $('#platinumBalance').text(`${parseFloat(data.platinum_balance).toFixed(4)} g`);
            $('#palladiumBalance').text(`${parseFloat(data.palladium_balance).toFixed(4)} g`);
        }

        // Vault Storage Handlers
        function payStorageInvoice() {
            const amount = parseFloat($('#invoiceAmountVal').attr('data-amount'));
            swalConfirm(
                'Confirm Payment',
                `Pay the $${amount.toFixed(2)} storage invoice from your wallet balance?`,
                function() {
                    $.ajax({
                        url: '/vault/pay-invoice',
                        method: 'POST',
                        data: { amount: amount, _token: '{{ csrf_token() }}' },
                        success: function(data) {
                            if (data.success) {
                                $('#invoiceContainer').html(`
                                    <div style="font-size: 48px; color: #4A7C59; margin-bottom: 10px;"><i class="fa-solid fa-circle-check"></i></div>
                                    <div style="font-size: 14px; font-weight: 600; color: #4A7C59;">Invoice Paid Successfully</div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 5px;">Your payment of $${amount.toFixed(2)} was logged.</div>
                                `);
                                updateBalances(data);
                                swalSuccess('Payment Confirmed', `$${amount.toFixed(2)} deducted from your wallet.`);
                            }
                        },
                        error: function() {
                            swalError('Payment Failed', 'Insufficient wallet balance to cover this invoice.');
                        }
                    });
                }
            );
        }

        function requestPhysicalDelivery(e) {
            e.preventDefault();
            const barId = $('#deliveryAsset').val();
            const address = $('#vaultDeliveryForm input').val();
            if (!confirm(`Are you sure you want to schedule secure class 3 armored delivery to: ${address}?`)) {
                return;
            }

            // Simulate delivery dispatch
            $(`#vault-bar-${barId} .status-cell`).html(`
                <span style="background: rgba(245,196,105,0.15); color: var(--gold-light); padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fa-solid fa-truck-fast"></i> In Transit</span>
            `);
            swalSuccess('Delivery Dispatched!', 'Armored logistics will contact you shortly to confirm insurance & delivery schedule.');
            $('#vaultDeliveryForm')[0].reset();
        }

        // Gold IRA Multi-step Wizard Handlers
        let iraSelectedCustodian = "";
        let iraRolloverSum = 0;
        let iraRemainingFunds = 0;

        function submitIraStep1(e) {
            e.preventDefault();
            iraSelectedCustodian = $('#iraCustodian option:selected').text();
            iraRolloverSum = parseFloat($('#iraRolloverAmount').val());

            $('#selectedCustodianText').text(iraSelectedCustodian);
            $('#requestedRolloverText').text(`$${iraRolloverSum.toLocaleString('en-US', {minimumFractionDigits: 2})}`);

            // Transition UI
            $('#wizardStep1').hide();
            $('#wizardStep2').fadeIn();

            $('#stepIndicator1').css('opacity', '0.5');
            $('#stepIndicator2').css('opacity', '1').addClass('active-step');
            $('#stepIndicator2 span').css('background', 'var(--gold-light)').css('color', '#000');
        }

        function submitIraStep2() {
            Swal.fire({
                ...swalTheme,
                icon: 'info',
                title: 'Submitting Documents',
                text: 'Sending rollover agreement via DocuSign gateway...',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            }).then(() => {
                iraRemainingFunds = iraRolloverSum;
                $('.ira-funded-balance').text(`$${iraRemainingFunds.toLocaleString('en-US', {minimumFractionDigits: 2})}`);
                $('#wizardStep2').hide();
                $('#wizardStep3').fadeIn();
                $('#stepIndicator2').css('opacity', '0.5');
                $('#stepIndicator3').css('opacity', '1').addClass('active-step');
                $('#stepIndicator3 span').css('background', 'var(--gold-light)').css('color', '#000');
            });
        }

        function buyIraBullion(itemName, cost) {
            if (iraRemainingFunds < cost) {
                swalError('Insufficient IRA Funds', `Required: $${cost.toFixed(2)} — Available: $${iraRemainingFunds.toFixed(2)}`);
                return;
            }
            swalConfirm(
                'Confirm IRA Purchase',
                `Invest $${cost.toFixed(2)} in ${itemName} inside your tax-sheltered IRA account?`,
                function() {
                    iraRemainingFunds -= cost;
                    $('.ira-funded-balance').text(`$${iraRemainingFunds.toLocaleString('en-US', {minimumFractionDigits: 2})}`);
                    swalSuccess('Purchase Allocated!', `1 unit of ${itemName} has been added to your physical Gold IRA vault.`);
                }
            );
        }

        // Inline Settings Form Submission
        function submitInlineSettings(e) {
            e.preventDefault();
            const email   = $('#inlineSettingsEmail').val();
            const address = $('#inlineSettingsAddress').val();
            const currentPassword = $('#inlineSettingsCurrentPassword').val();
            const newPassword = $('#inlineSettingsNewPassword').val();
            const newPasswordConfirmation = $('#inlineSettingsNewPasswordConfirmation').val();

            $.ajax({
                url: '/settings/update',
                method: 'POST',
                data: {
                    email: email,
                    address: address,
                    current_password: currentPassword,
                    new_password: newPassword,
                    new_password_confirmation: newPasswordConfirmation,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    swalSuccess('Settings Saved!', data.message);
                    $('#inlineSettingsCurrentPassword').val('');
                    $('#inlineSettingsNewPassword').val('');
                    $('#inlineSettingsNewPasswordConfirmation').val('');
                    // Update the delivery map default destination live
                    if (data.address) {
                        $('#mapDestinationInput').val(data.address);
                    }
                },
                error: function(xhr) {
                    const d = xhr.responseJSON;
                    swalError('Update Failed', d && d.message ? d.message : 'Invalid current password or fields.');
                }
            });
        }

        // Legacy settings functions
        function triggerSettings() {
            showTab('settings');
        }

        function submitSettings(e) {
            e.preventDefault();
        }

        function deactivateAccount() {
            swalConfirm(
                'Deactivate Account?',
                'You will be immediately logged out. All holdings remain safe in vault storage.',
                function() {
                    $.ajax({
                        url: '/settings/toggle-status',
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(data) {
                            swalSuccess('Account Deactivated', data.message);
                            setTimeout(() => { window.location.href = '/'; }, 2000);
                        },
                        error: function() {
                            swalError('Failed', 'Could not deactivate your account. Please try again.');
                        }
                    });
                }
            );
        }

        // High frequency polling to update Chart and prices dynamically in real-time
        setInterval(function() {
            $.ajax({
                url: '/cron/fetch-gold-price',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.price && window.metalsChartInstance) {
                        const prices = response.price;

                        // Update units prices on metal portfolio cards
                        $('#goldLivePrice').text(`$${parseFloat(prices.gold).toFixed(2)} / g`);
                        $('#silverLivePrice').text(`$${parseFloat(prices.silver).toFixed(2)} / g`);
                        $('#platinumLivePrice').text(`$${parseFloat(prices.platinum).toFixed(2)} / g`);
                        $('#palladiumLivePrice').text(`$${parseFloat(prices.palladium).toFixed(2)} / g`);

                        // Append time label and metal prices to Chart
                        const now = new Date();
                        const timeLabel = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });

                        window.metalsChartInstance.data.labels.push(timeLabel);
                        if (window.metalsChartInstance.data.labels.length > 10) {
                            window.metalsChartInstance.data.labels.shift();
                        }

                        window.metalsChartInstance.data.datasets.forEach((dataset) => {
                            const metalKey = dataset.label.toLowerCase();
                            dataset.data.push(prices[metalKey]);
                            if (dataset.data.length > 10) {
                                dataset.data.shift();
                            }
                        });

                        window.metalsChartInstance.update();
                    }
                }
            });
        }, 10000); // Ticks prices in real-time every 10 seconds!

        // Google Maps Interactive directions and autocomplete script integration
        let directionsService;
        let directionsRenderer;
        let secureMap;
        let destinationAutocomplete;

        function initAutocomplete() {
            const input = document.getElementById('mapDestinationInput');
            if (input) {
                destinationAutocomplete = new google.maps.places.Autocomplete(input);
            }
        }

        // Initialize Google Autocomplete on ready
        $(document).ready(function() {
            setTimeout(initAutocomplete, 1000);
        });

        function calculateTransitRoute() {
            const originAddress = $('#mapOriginDisplay').text().trim();
            const destAddress = $('#mapDestinationInput').val().trim() || 'Central Park, New York, NY';

            if (!secureMap) {
                secureMap = new google.maps.Map(document.getElementById('secureDeliveryMap'), {
                    zoom: 12,
                    center: { lat: 40.7128, lng: -74.0060 }, // NYC Center
                    styles: [
                        { elementType: 'geometry', stylers: [{ color: '#0f172a' }] },
                        { elementType: 'labels.text.stroke', stylers: [{ color: '#0f172a' }] },
                        { elementType: 'labels.text.fill', stylers: [{ color: '#94a3b8' }] },
                        {
                            featureType: 'administrative.locality',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#f5c469' }]
                        },
                        {
                            featureType: 'poi',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#94a3b8' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry',
                            stylers: [{ color: '#1e293b' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry.stroke',
                            stylers: [{ color: '#334155' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#64748b' }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'geometry',
                            stylers: [{ color: '#1e293b' }]
                        }
                    ]
                });

                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer({
                    map: secureMap,
                    polylineOptions: {
                        strokeColor: '#f5c469',
                        strokeOpacity: 0.8,
                        strokeWeight: 5
                    }
                });
            }

            directionsService.route({
                origin: originAddress,
                destination: destAddress,
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                    const route = response.routes[0].legs[0];
                    $('#routeDistanceVal').text(route.distance.text);
                    $('#routeDurationVal').text(route.duration.text);
                } else {
                    swalError('Route Error', 'Could not calculate the secure armored route: ' + status);
                }
            });
        }

        function confirmArmoredDispatch() {
            closeModal('deliveryMap');
            Swal.fire({
                ...swalTheme,
                icon: 'success',
                title: 'Dispatch Order Received!',
                html: `<p style="color:#94a3b8;font-size:14px;">A <strong style="color:#f5c469;">Brink's heavy-armored</strong> security unit has been booked.<br>Transit tracking code &amp; escort details have been sent to your verified email.</p>`,
                confirmButtonText: 'Got it!',
            });
        }

        // Submit Admin System Settings update form
        function submitAdminSettings(e) {
            e.preventDefault();
            const shop  = $('#adminShopLocation').val();
            const limit = $('#adminStorageLimit').val();
            const price = $('#adminExtraStoragePrice').val();

            $.ajax({
                url: '/admin/update-settings',
                method: 'POST',
                data: {
                    shop_location: shop,
                    storage_limit_kg: parseFloat(limit),
                    extra_storage_price_per_kg: parseFloat(price),
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    swalSuccess('Settings Applied!', data.message);
                    setTimeout(() => window.location.reload(), 1800);
                },
                error: function(xhr) {
                    const d = xhr.responseJSON;
                    swalError('Update Failed', d && d.message ? d.message : 'Unauthorized or invalid input.');
                }
            });
        }
    </script>

    <!-- Add Funds Modal Overlay -->
{{--    <div class="custom-modal-overlay" id="depositModalOverlay">--}}
{{--        <div class="custom-modal">--}}
{{--            <button class="modal-close-btn" onclick="closeModal('deposit')">&times;</button>--}}
{{--            <div class="modal-header">--}}
{{--                <h3><i class="fa-solid fa-wallet"></i> Deposit Funds</h3>--}}
{{--                <p>Enter the USD amount to add to your fiat wallet balance.</p>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form id="depositForm" onsubmit="submitDeposit(event)">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="depositAmount" style="margin-bottom: 8px; display: block; font-size: 14px; font-weight: 500;">Amount (USD)</label>--}}
{{--                        <div class="form-input-wrapper">--}}
{{--                            <span>$</span>--}}
{{--                            <input type="number" id="depositAmount" step="0.01" min="0.01" class="form-control" placeholder="100.00" required>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="submit" class="btn btn-primary" style="width: 100%;">--}}
{{--                            Confirm Deposit--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="custom-modal-overlay" id="depositModalOverlay">
        <div class="custom-modal">

            <button class="modal-close-btn"
                    onclick="closeModal('deposit')">
                &times;
            </button>

            <div class="modal-header">
                <h3>
                    <i class="fa-solid fa-wallet"></i>
                    Deposit Funds
                </h3>

                <p>Select payment method to add balance.</p>
            </div>

            <div class="modal-body">

                <div class="form-group">

                    <label style="margin-bottom: 8px; display:block;">
                        Amount (USD)
                    </label>

                    <div class="form-input-wrapper">
                        <span>$</span>

                        <input
                            type="number"
                            id="depositAmount"
                            step="0.01"
                            min="1"
                            class="form-control"
                            placeholder="100.00"
                        >
                    </div>
                </div>

                <div class="modal-footer"
                     style="display:flex; gap:10px; flex-direction:column;">

                    <!-- STRIPE -->
                    <button
                        type="button"
                        onclick="payWithStripe()"
                        class="btn btn-primary"
                        style="width:100%;">

                        <i class="fa-brands fa-stripe"></i>
                        Pay with Stripe
                    </button>

                    <!-- JAZZCASH -->
                    <button
                        type="button"
                        onclick="payWithJazzCash()"
                        class="btn btn-success"
                        style="width:100%;">

                        JazzCash
                    </button>

                </div>
            </div>
        </div>
    </div>

{{--    Precious Metal Trade Modal Overlay --}}
    <div class="custom-modal-overlay" id="tradeModalOverlay">
        <div class="custom-modal">
            <button class="modal-close-btn" onclick="closeModal('trade')">&times;</button>
            <div class="modal-header">
                <h3 id="tradeModalTitle"><i class="fa-solid fa-scale-balanced"></i> Trade Metal</h3>
                <p id="tradeModalDesc">Buy or sell precious metals securely.</p>
            </div>
            <div class="modal-body">
                <form id="tradeForm" onsubmit="submitTrade(event)">
                    <input type="hidden" id="tradeMetalType">
                    <input type="hidden" id="tradeActionType">
                    <div class="form-group">
                        <label for="tradeQuantity" style="margin-bottom: 8px; display: block; font-size: 14px; font-weight: 500;">Quantity (grams)</label>
                        <div class="form-input-wrapper">
                            <input type="number" id="tradeQuantity" step="0.0001" min="0.0001" class="form-control" style="padding-left: 16px;" placeholder="0.5000" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="tradeSubmitBtn" style="width: 100%;">
                            Confirm Trade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Fulfillment Option: Voucher Modal Overlay -->
    <div class="custom-modal-overlay" id="voucherModalOverlay">
        <div class="custom-modal" style="max-width: 480px; padding: 0; background: #0b0f19; border: 2px solid var(--gold-light); overflow: hidden; border-radius: 16px;">
            <!-- Golden header trim -->
            <div style="background: linear-gradient(135deg, #bf953f 0%, #fcf6ba 25%, #b38728 50%, #fcf6ba 75%, #aa771c 100%); height: 8px; width: 100%;"></div>

            <div style="padding: 30px; position: relative;">
                <button class="modal-close-btn" onclick="closeModal('voucher')" style="top: 15px; right: 15px; color: var(--text-secondary);">&times;</button>

                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 12px; font-weight: 600; color: var(--gold-light); text-transform: uppercase; letter-spacing: 2px;">Receipt & Verification</div>
                    <h3 style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin-top: 5px; font-family: 'Outfit', sans-serif;">SECURE PHYSICAL VOUCHER</h3>
                </div>

                <!-- Ticket Body -->
                <div style="border: 1px dashed rgba(245,196,105,0.25); background: rgba(255,255,255,0.01); border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: inset 0 0 15px rgba(0,0,0,0.5);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px;">
                        <span style="color: var(--text-muted);">Receipt ID:</span>
                        <strong style="color: var(--text-primary); font-family: monospace;" id="voucherReceiptId">AG-2026-90412</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px;">
                        <span style="color: var(--text-muted);">Precious Metal:</span>
                        <strong style="color: var(--gold-light);" id="voucherMetalName">Gold Bullion</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px;">
                        <span style="color: var(--text-muted);">Allocated Weight:</span>
                        <strong style="color: var(--text-primary);" id="voucherWeight">1.0000 grams</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 12px;">
                        <span style="color: var(--text-muted);">Owner:</span>
                        <strong style="color: var(--text-primary);">{{ auth()->user()->name }}</strong>
                    </div>

                    <hr style="border: 0; border-top: 1px dashed rgba(255,255,255,0.08); margin: 15px 0;">

                    <!-- Simulated Barcode Graphic -->
                    <div style="text-align: center; margin: 15px 0 5px;">
                        <div style="display: inline-flex; height: 40px; gap: 2px; align-items: stretch; background: white; padding: 8px 15px; border-radius: 4px;">
                            <div style="width: 2px; background: black;"></div>
                            <div style="width: 1px; background: black;"></div>
                            <div style="width: 3px; background: black;"></div>
                            <div style="width: 1px; background: black;"></div>
                            <div style="width: 2px; background: black;"></div>
                            <div style="width: 4px; background: black;"></div>
                            <div style="width: 1px; background: black;"></div>
                            <div style="width: 2px; background: black;"></div>
                            <div style="width: 1px; background: black;"></div>
                            <div style="width: 3px; background: black;"></div>
                            <div style="width: 2px; background: black;"></div>
                            <div style="width: 1px; background: black;"></div>
                            <div style="width: 4px; background: black;"></div>
                            <div style="width: 2px; background: black;"></div>
                            <div style="width: 1px; background: black;"></div>
                            <div style="width: 3px; background: black;"></div>
                        </div>
                        <div style="font-family: monospace; font-size: 11px; color: var(--text-secondary); margin-top: 6px; letter-spacing: 3px;">*AG-90412-SECURE*</div>
                    </div>
                </div>

                <p style="color: var(--text-secondary); font-size: 12px; line-height: 1.6; text-align: center; margin-bottom: 25px;">
                    This voucher has been allocated to your profile. Please present this voucher along with valid government photo identification at our main retail store location to take physical custody of your goods.
                </p>

                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-outline" style="flex: 1; font-size: 13px;" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Voucher</button>
                    <button class="btn btn-primary" style="flex: 1; font-size: 13px;" onclick="closeModal('voucher')"><i class="fa-solid fa-circle-check"></i> Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fulfillment Option: Google Map Secure Delivery Modal Overlay -->
    <div class="custom-modal-overlay" id="deliveryMapModalOverlay">
        <div class="custom-modal" style="max-width: 600px; padding: 30px; background: #0b0f19; border: 1px solid var(--border-color); border-radius: 16px;">
            <button class="modal-close-btn" onclick="closeModal('deliveryMap')">&times;</button>

            <div class="modal-header" style="margin-bottom: 20px;">
                <h3><i class="fa-solid fa-truck-shield" style="color: var(--gold-light); margin-right: 8px;"></i> Secured Armored Escort Delivery</h3>
                <p>Input your destination. Physical transit route calculations are plotted live from our main store depository vault.</p>
            </div>

            <div class="modal-body" style="padding: 0;">
                <!-- Origin (Pre-filled from Admin Console setting) -->
                <div style="margin-bottom: 15px; font-size: 13px;">
                    <span style="color: var(--text-muted);"><i class="fa-solid fa-shop" style="color: var(--gold-light); margin-right: 6px;"></i> Depository Origin Address:</span>
                    <strong style="color: var(--text-primary); display: block; margin-top: 4px;" id="mapOriginDisplay">{{ $shopLocation }}</strong>
                </div>

                <!-- Input Drop Destination with autosearch -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-size: 12px;"><i class="fa-solid fa-location-dot" style="color: #ff4d4d; margin-right: 6px;"></i> Enter Destination Address</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="mapDestinationInput" class="form-control" style="padding-left: 12px; flex: 1;" placeholder="Enter street address, city or zip" value="{{ $userAddress }}">
                        <button class="btn btn-primary" onclick="calculateTransitRoute()" style="font-size: 13px; padding: 0 15px;"><i class="fa-solid fa-route"></i> Calculate Route</button>
                    </div>
                </div>

                <!-- Route Stats Indicators -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; text-align: center;">
                        <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase;">Estimated Transit Distance</div>
                        <div style="font-size: 18px; font-weight: 600; color: var(--gold-light); margin-top: 5px;" id="routeDistanceVal">--- miles</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; text-align: center;">
                        <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase;">Armored Transit ETA</div>
                        <div style="font-size: 18px; font-weight: 600; color: var(--gold-light); margin-top: 5px;" id="routeDurationVal">--- mins</div>
                    </div>
                </div>

                <!-- Google Map container -->
                <div id="secureDeliveryMap" style="width: 100%; height: 300px; border-radius: 12px; border: 1px solid var(--border-color); background: #0c1220;"></div>

                <div class="modal-footer" style="margin-top: 25px; padding: 0; display: flex; gap: 12px;">
                    <button class="btn btn-outline" style="flex: 1;" onclick="closeModal('deliveryMap')">Cancel</button>
                    <button class="btn btn-primary" style="flex: 1;" onclick="confirmArmoredDispatch()"><i class="fa-solid fa-truck-fast"></i> Dispatch Armored Car</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Google Maps API dynamically with place libraries -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places"></script>
<script>
    function initAutocomplete() {

        const input = document.getElementById('inlineSettingsAddress');

        if (input) {

            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {

                const place = autocomplete.getPlace();

                console.log(place);

                // Check geometry exists
                if (!place.geometry) {
                    console.log("No details available");
                    return;
                }

                // Latitude & Longitude
                const latitude = place.geometry.location.lat();
                const longitude = place.geometry.location.lng();

                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;

                // Address Components
                let city = '';
                let state = '';
                let postcode = '';
                let country = '';

                place.address_components.forEach((component) => {

                    const types = component.types;

                    if (types.includes('locality')) {
                        city = component.long_name;
                    }

                    if (types.includes('administrative_area_level_1')) {
                        state = component.long_name;
                    }

                    if (types.includes('postal_code')) {
                        postcode = component.long_name;
                    }

                    if (types.includes('country')) {
                        country = component.long_name;
                    }
                });

                // Assign values
                document.getElementById('city').value = city;
                document.getElementById('state').value = state;
                document.getElementById('zip_code').value = postcode;
                document.getElementById('country').value = country;

                console.log({
                    latitude,
                    longitude,
                    city,
                    state,
                    postcode,
                    country
                });
            });
        }
    }

    function payWithJazzCash() {
        const amount = $('#depositAmount').val();

        if (!amount || amount <= 0) {
            alert('Enter valid amount');
            return;
        }

        // Open form in new window instead of replacing page
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '/wallet/jazzcash/payment';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'amount';
        input.value = amount;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
</script>
</body>
</html>
