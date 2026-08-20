<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Cheque - {{ $cheque->payee_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #111827;
        }
        @media print {
            @page {
                size: 203mm 89mm;
                margin: 0;
            }
            body { 
                background-color: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .print-wrapper {
                padding: 0 !important;
                display: block !important;
                background: transparent !important;
                border: none !important;
            }
            .cheque-container {
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            .sbi-template, .federal-template {
                display: none !important;
            }
        }
        
        .cheque-container {
            width: 203mm;
            height: 89mm;
            position: relative;
            background-color: white;
            box-sizing: border-box;
            overflow: hidden;
            border: 1px solid #d1d5db; /* Simple border */
            margin: 0 auto;
        }

        .print-data {
            position: absolute;
            z-index: 20;
            color: #000;
            font-weight: 600;
        }
        
        .c-ac-payee { 
            border-top: 1px solid #000; 
            border-bottom: 1px solid #000; 
            padding: 2px 0; 
            display: inline-block;
        }
        
        .c-date { 
            font-family: 'Courier New', Courier, monospace; 
            font-weight: bold;
        }

        .c-signature {
            text-align: center;
        }

        /* Simple Form Inputs */
        input[type="number"] {
            -moz-appearance: textfield;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 13px;
            width: 100%;
        }
        input[type="number"]:focus {
            outline: none;
            border-color: #6b7280;
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .setting-label {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 2px;
            display: block;
        }
        .setting-group-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }

        /* ----- SBI Cheque Template Styles ----- */
        .sbi-template {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: #d1eff5; 
            background-image: repeating-linear-gradient(0deg, transparent, transparent 4px, rgba(0, 100, 160, 0.05) 4px, rgba(0, 100, 160, 0.05) 8px);
            color: #005f99; z-index: 5; font-family: Arial, sans-serif; pointer-events: none;
        }
        .sbi-diagonal {
            position: absolute; top: 10px; left: -40px; width: 150px; height: 30px;
            border-top: 1.5px solid #005f99; border-bottom: 1.5px solid #005f99;
            transform: rotate(-40deg); display: flex; align-items: center; justify-content: center;
            font-size: 8px; font-weight: bold; letter-spacing: 0.5px;
        }
        .sbi-logo-area { position: absolute; top: 10px; left: 55px; display: flex; align-items: flex-start; gap: 10px; }
        .sbi-logo-circle { width: 28px; height: 28px; background-color: #005f99; border-radius: 50%; position: relative; }
        .sbi-logo-cutout { position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 6px; height: 12px; background-color: #d1eff5; border-radius: 6px 6px 0 0; }
        .sbi-logo-text { line-height: 1.2; }
        .sbi-hindi-text { font-size: 14px; font-weight: bold; }
        .sbi-eng-text { font-size: 14px; font-weight: bold; }
        .sbi-branch-text { position: absolute; top: 12px; left: 210px; font-size: 7px; line-height: 1.3; color: #333; }
        
        /* Shared Template Styles */
        .tmpl-date-area { position: absolute; top: 10px; right: 25px; }
        .tmpl-date-validity { font-size: 6px; text-align: right; margin-bottom: 2px; color: #333;}
        .tmpl-date-boxes { display: flex; }
        .tmpl-date-box { width: 15px; height: 20px; border: 1px solid #777; border-right: none; background-color: white; }
        .tmpl-date-box:last-child { border-right: 1px solid #777; }
        .tmpl-date-labels { display: flex; margin-top: 2px; }
        .tmpl-date-label { width: 15px; text-align: center; font-size: 7px; letter-spacing: 0; color: #333; }
        
        .tmpl-pay-line { position: absolute; top: 55px; left: 20px; font-size: 10px; font-weight: bold; }
        .tmpl-line-1 { position: absolute; top: 68px; left: 50px; right: 25px; height: 1px; background-color: #777; }
        .tmpl-rupees-line { position: absolute; top: 85px; left: 20px; font-size: 10px; font-weight: bold; }
        .tmpl-line-2 { position: absolute; top: 98px; left: 80px; right: 200px; height: 1px; background-color: #777; }
        .tmpl-line-3 { position: absolute; top: 128px; left: 20px; right: 200px; height: 1px; background-color: #777; }
        .tmpl-amount-box-area { position: absolute; top: 85px; right: 25px; display: flex; align-items: center; }
        .tmpl-amount-label { font-size: 10px; font-weight: bold; margin-right: 5px; }
        .tmpl-amount-box { width: 140px; height: 28px; border: 1.5px solid #777; background-color: rgba(255,255,255,0.7); display: flex; align-items: center; padding-left: 5px; font-size: 14px; font-weight: bold; }
        .tmpl-ac-no-area { position: absolute; bottom: 45px; left: 20px; display: flex; }
        .tmpl-ac-no-label { border: 1px solid #777; border-right: none; width: 40px; height: 30px; font-size: 7px; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #333;}
        .tmpl-ac-no-box { border: 1px solid #777; width: 140px; height: 30px; background-color: white; }

        /* ----- Federal Bank Cheque Template Styles ----- */
        .federal-template {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: #f7f3e8; /* Light beige/golden */
            background-image: repeating-linear-gradient(45deg, transparent, transparent 15px, rgba(200, 180, 140, 0.15) 15px, rgba(200, 180, 140, 0.15) 30px);
            color: #333; z-index: 5; font-family: Arial, sans-serif; pointer-events: none;
        }
        .fed-diagonal {
            position: absolute; top: 15px; left: -40px; width: 150px; height: 30px;
            border-top: 1.5px solid #333; border-bottom: 1.5px solid #333;
            transform: rotate(-40deg); display: flex; align-items: center; justify-content: center;
            font-size: 8px; font-weight: bold; letter-spacing: 0.5px; color: #333;
        }
        .fed-logo-area {
            position: absolute; top: 15px; left: 60px;
        }
        .fed-logo-text {
            font-family: 'Times New Roman', Times, serif;
            font-size: 22px; font-weight: 900; color: #1e3a8a; /* Deep blue */
            font-style: italic; letter-spacing: -0.5px; position: relative;
        }
        .fed-logo-the {
            font-size: 8px; font-style: normal; position: absolute; top: -6px; left: 2px; color: #777;
        }
        .fed-branch-text {
            position: absolute; top: 15px; left: 260px; font-size: 7px; line-height: 1.3; color: #333;
            font-weight: bold;
        }
        .fed-bottom-band {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 14px;
            background-color: #1e3a8a; /* Federal Blue */
        }
        .fed-bottom-orange {
            position: absolute; bottom: 0; right: 0; width: 150px; height: 14px;
            background-color: #ea580c; /* Federal Orange */
        }
        .fed-footer-text {
            position: absolute; bottom: 20px; left: 20px; font-size: 7px; font-weight: bold; color: #333;
        }
        .fed-auth-sign {
            position: absolute; bottom: 20px; right: 25px; font-size: 7px; font-weight: bold; text-align: center; color: #333;
        }
        .fed-current-ac {
            position: absolute; top: 15px; right: 200px; font-size: 9px; font-weight: bold; color: #333;
        }
    </style>
</head>
<body x-data="chequeSettings()" x-init="initSettings()">
    @php
        if (!function_exists('amountToWords')) {
            function amountToWords($number) {
                // ... same amount to words logic ...
                $no = floor($number);
                $point = round($number - $no, 2) * 100;
                $hundred = null;
                $digits_1 = strlen($no);
                $i = 0;
                $str = array();
                $words = array(0 => '', 1 => 'One', 2 => 'Two',
                    3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
                    7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                    10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
                    13 => 'Thirteen', 14 => 'Fourteen',
                    15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
                    18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
                    30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
                    60 => 'Sixty', 70 => 'Seventy',
                    80 => 'Eighty', 90 => 'Ninety');
                $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
                while ($i < $digits_1) {
                    $divider = ($i == 2) ? 10 : 100;
                    $number = floor($no % $divider);
                    $no = floor($no / $divider);
                    $i += ($divider == 10) ? 1 : 2;
                    if ($number) {
                        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                        $str [] = ($number < 21) ? $words[$number] .
                            " " . $digits[$counter] . $plural . " " . $hundred
                            :
                            $words[floor($number / 10) * 10]
                            . " " . $words[$number % 10] . " "
                            . $digits[$counter] . $plural . " " . $hundred;
                    } else $str[] = null;
                }
                $str = array_reverse($str);
                $result = implode('', $str);
                $points = ($point) ?
                    " and " . $words[$point / 10] . " " .
                    $words[$point = $point % 10] . " Paise" : '';
                return $result . "Rupees Only";
            }
        }
    @endphp

    <!-- Simple Header -->
    <div class="no-print bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Print Cheque Preview</h1>
                    <p class="text-xs text-gray-500">CTS-2010 Standard (203mm x 89mm)</p>
                </div>
                <div class="border-l pl-6 border-gray-200 flex items-center gap-2">
                    <label class="text-sm text-gray-600 font-medium">Bank Template Preview:</label>
                    <select x-model="templateBank" class="border border-gray-300 rounded text-sm p-1.5 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        <option value="none">None (Blank Paper)</option>
                        <option value="sbi">State Bank of India (SBI)</option>
                        <option value="federal">Federal Bank</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="showSettings = !showSettings" class="p-1.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded" title="Calibration Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </button>
                <button onclick="window.history.back()" class="px-3 py-1.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded text-sm font-medium">Back</button>
                <button onclick="window.print()" class="px-4 py-1.5 bg-blue-600 text-white hover:bg-blue-700 rounded text-sm font-medium">Print Cheque</button>
            </div>
        </div>
    </div>
    
    <div class="max-w-6xl mx-auto px-4 py-6">
        
        <!-- Simple Settings Panel -->
        <div class="no-print bg-white border border-gray-200 rounded mb-8 shadow-md" x-show="showSettings" x-transition style="display: none;">
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-sm font-semibold text-gray-700">Printer Calibration Settings</h2>
                <button @click="resetSettings()" class="text-xs text-blue-600 hover:underline">Reset Defaults</button>
            </div>
            
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- A/C Payee -->
                <div>
                    <div class="setting-group-title">A/C Payee</div>
                    <div class="flex gap-2">
                        <div class="flex-1"><label class="setting-label">Top (mm)</label><input type="number" step="0.5" x-model.number="settings.acPayee.top"></div>
                        <div class="flex-1"><label class="setting-label">Left (mm)</label><input type="number" step="0.5" x-model.number="settings.acPayee.left"></div>
                        <div class="flex-1"><label class="setting-label">Font (px)</label><input type="number" step="1" x-model.number="settings.acPayee.fontSize"></div>
                    </div>
                </div>
                
                <!-- Date -->
                <div>
                    <div class="setting-group-title">Date</div>
                    <div class="flex gap-2">
                        <div class="flex-1"><label class="setting-label">Top (mm)</label><input type="number" step="0.5" x-model.number="settings.date.top"></div>
                        <div class="flex-1"><label class="setting-label">Left (mm)</label><input type="number" step="0.5" x-model.number="settings.date.left"></div>
                        <div class="flex-1"><label class="setting-label">Font (px)</label><input type="number" step="1" x-model.number="settings.date.fontSize"></div>
                        <div class="flex-1"><label class="setting-label">Space (mm)</label><input type="number" step="0.5" x-model.number="settings.date.letterSpacing"></div>
                    </div>
                </div>
                
                <!-- Payee Name -->
                <div>
                    <div class="setting-group-title">Payee Name</div>
                    <div class="flex gap-2">
                        <div class="flex-1"><label class="setting-label">Top (mm)</label><input type="number" step="0.5" x-model.number="settings.payee.top"></div>
                        <div class="flex-1"><label class="setting-label">Left (mm)</label><input type="number" step="0.5" x-model.number="settings.payee.left"></div>
                        <div class="flex-1"><label class="setting-label">Font (px)</label><input type="number" step="1" x-model.number="settings.payee.fontSize"></div>
                    </div>
                </div>
                
                <!-- Amount in Words -->
                <div>
                    <div class="setting-group-title">Amount in Words</div>
                    <div class="flex gap-2">
                        <div class="flex-1"><label class="setting-label">Top (mm)</label><input type="number" step="0.5" x-model.number="settings.amountWords.top"></div>
                        <div class="flex-1"><label class="setting-label">Left (mm)</label><input type="number" step="0.5" x-model.number="settings.amountWords.left"></div>
                        <div class="flex-1"><label class="setting-label">Width (mm)</label><input type="number" step="1" x-model.number="settings.amountWords.width"></div>
                        <div class="flex-1"><label class="setting-label">Font (px)</label><input type="number" step="1" x-model.number="settings.amountWords.fontSize"></div>
                    </div>
                </div>
                
                <!-- Amount Number -->
                <div>
                    <div class="setting-group-title">Amount Number</div>
                    <div class="flex gap-2">
                        <div class="flex-1"><label class="setting-label">Top (mm)</label><input type="number" step="0.5" x-model.number="settings.amountNum.top"></div>
                        <div class="flex-1"><label class="setting-label">Left (mm)</label><input type="number" step="0.5" x-model.number="settings.amountNum.left"></div>
                        <div class="flex-1"><label class="setting-label">Font (px)</label><input type="number" step="1" x-model.number="settings.amountNum.fontSize"></div>
                    </div>
                </div>
                
                <!-- Signature -->
                <div>
                    <div class="setting-group-title">Signature</div>
                    <div class="flex gap-2">
                        <div class="flex-1"><label class="setting-label">Top (mm)</label><input type="number" step="0.5" x-model.number="settings.signature.top"></div>
                        <div class="flex-1"><label class="setting-label">Left (mm)</label><input type="number" step="0.5" x-model.number="settings.signature.left"></div>
                        <div class="flex-1"><label class="setting-label">Font (px)</label><input type="number" step="1" x-model.number="settings.signature.fontSize"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Print Area -->
        <div class="print-wrapper pb-12">
            <!-- The actual cheque area -->
            <div class="cheque-container">
                
                <!-- SBI Background Template -->
                <div class="sbi-template" x-show="templateBank === 'sbi'">
                    <div class="sbi-diagonal">A/C PAYEE ONLY</div>
                    <div class="sbi-logo-area">
                        <div class="sbi-logo-circle"><div class="sbi-logo-cutout"></div></div>
                        <div class="sbi-logo-text"><div class="sbi-hindi-text">भारतीय स्टेट बैंक</div><div class="sbi-eng-text">State Bank Of India</div></div>
                    </div>
                    <div class="sbi-branch-text">
                        (08439) - POTTERY ROAD AREA BRANCH<br>35C, CHRISTOPHER ROAD, CALCUTTA<br>WEST BENGAL 700046<br>Tel: 91-9830174829 IFS Code : SBIN0008439
                    </div>
                    <div class="tmpl-date-area">
                        <div class="tmpl-date-validity">केवल 3 महीने के लिए वैध / VALID FOR 3 MONTHS ONLY</div>
                        <div class="tmpl-date-boxes">
                            <div class="tmpl-date-box" style="border-color:#005f99"></div><div class="tmpl-date-box" style="border-color:#005f99"></div>
                            <div class="tmpl-date-box" style="border-color:#005f99"></div><div class="tmpl-date-box" style="border-color:#005f99"></div>
                            <div class="tmpl-date-box" style="border-color:#005f99"></div><div class="tmpl-date-box" style="border-color:#005f99"></div>
                            <div class="tmpl-date-box" style="border-color:#005f99"></div><div class="tmpl-date-box" style="border-color:#005f99"></div>
                        </div>
                        <div class="tmpl-date-labels">
                            <div class="tmpl-date-label">D</div><div class="tmpl-date-label">D</div><div class="tmpl-date-label">M</div><div class="tmpl-date-label">M</div><div class="tmpl-date-label">Y</div><div class="tmpl-date-label">Y</div><div class="tmpl-date-label">Y</div><div class="tmpl-date-label">Y</div>
                        </div>
                    </div>
                    <div class="tmpl-pay-line">PAY</div><div class="tmpl-line-1" style="background-color:#005f99"></div>
                    <div class="tmpl-rupees-line">रुपये RUPEES</div><div class="tmpl-line-2" style="background-color:#005f99"></div><div class="tmpl-line-3" style="background-color:#005f99"></div>
                    <div class="tmpl-amount-box-area">
                        <div class="tmpl-amount-label">अदा करें ₹</div><div class="tmpl-amount-box" style="border-color:#005f99; background:#e6f7fa;"></div>
                    </div>
                    <div class="tmpl-ac-no-area">
                        <div class="tmpl-ac-no-label" style="border-color:#005f99"><span>खा. सं.</span><span>A/c No.</span></div>
                        <div class="tmpl-ac-no-box" style="border-color:#005f99"></div>
                    </div>
                    <div style="position: absolute; bottom: 60px; left: 220px; font-size: 9px; font-weight: bold; color: #333;">CURRENT A/C</div>
                    <div style="position: absolute; bottom: 45px; left: 220px; font-size: 9px; font-weight: bold; color: #333;">PREFIX:</div>
                    <div style="position: absolute; bottom: 75px; left: 220px; font-size: 6px; color: #333;">VALID UPTO ₹ 1 CRORE AT NON-HOME BRANCH FOR NON-CASH TRANSACTION ONLY</div>
                    <div style="position: absolute; bottom: 15px; left: 20px; font-size: 8px; font-weight: bold;">MULTI-CITY CHEQUE <span style="font-weight:normal">Payable at Par at All Branches of SBI</span></div>
                    <div style="position: absolute; bottom: 15px; right: 50px; font-size: 9px;">Please sign above</div>
                </div>

                <!-- Federal Bank Background Template -->
                <div class="federal-template" x-show="templateBank === 'federal'">
                    <div class="fed-diagonal">A/C PAYEE ONLY</div>
                    <div class="fed-logo-area">
                        <div class="fed-logo-text"><span class="fed-logo-the">THE</span>FEDERAL BANK</div>
                    </div>
                    <div class="fed-branch-text">
                        Vadodara / Waghodia Road Br. [gi]<br>Vadodara - 390 019<br>IFSC: FDRL0001810
                    </div>
                    <div class="fed-current-ac">CURRENT ACCOUNT<br><span style="font-size:6px; font-weight:normal;">Valid for 3 months only</span></div>
                    
                    <div class="tmpl-date-area">
                        <div class="tmpl-date-boxes">
                            <div class="tmpl-date-box"></div><div class="tmpl-date-box"></div>
                            <div class="tmpl-date-box"></div><div class="tmpl-date-box"></div>
                            <div class="tmpl-date-box"></div><div class="tmpl-date-box"></div>
                            <div class="tmpl-date-box"></div><div class="tmpl-date-box"></div>
                        </div>
                        <div class="tmpl-date-labels">
                            <div class="tmpl-date-label">D</div><div class="tmpl-date-label">D</div><div class="tmpl-date-label">M</div><div class="tmpl-date-label">M</div><div class="tmpl-date-label">Y</div><div class="tmpl-date-label">Y</div><div class="tmpl-date-label">Y</div><div class="tmpl-date-label">Y</div>
                        </div>
                    </div>
                    
                    <div class="tmpl-pay-line">Pay</div><div class="tmpl-line-1"></div><div style="position:absolute; top:55px; right: 25px; font-size:9px;">या धारक को XXXXX</div>
                    <div class="tmpl-rupees-line">रुपये Rupees</div><div class="tmpl-line-2"></div><div class="tmpl-line-3"></div>
                    
                    <div class="tmpl-amount-box-area">
                        <div class="tmpl-amount-label">अदा करें |</div><div class="tmpl-amount-label" style="font-size:16px;">₹</div><div class="tmpl-amount-box"></div>
                    </div>
                    
                    <div class="tmpl-ac-no-area">
                        <div class="tmpl-ac-no-label"><span>खा. सं.</span><span>A/c No.</span></div>
                        <div class="tmpl-ac-no-box"></div>
                    </div>
                    
                    <div class="fed-footer-text">PAYABLE AT ALL BRANCHES OF FEDERAL BANK</div>
                    <div class="fed-auth-sign">AUTHORISED SIGNATORY<br>Please sign above</div>
                    
                    <div class="fed-bottom-band">
                        <div class="fed-bottom-orange"></div>
                    </div>
                </div>

                <!-- Print Data Overlay -->
                <!-- Hide A/C Payee if template is showing since it has diagonal lines printed -->
                @if($cheque->is_ac_payee)
                    <div class="print-data c-ac-payee" :style="getStyle('acPayee')" x-show="templateBank === 'none'">A/C PAYEE ONLY</div>
                @endif
                
                <!-- Format Date as DDMMYYYY -->
                <div class="print-data c-date" :style="getStyle('date')">{{ \Carbon\Carbon::parse($cheque->date)->format('dmY') }}</div>
                
                <div class="print-data c-payee" :style="getStyle('payee')">** {{ strtoupper($cheque->payee_name) }} **</div>
                
                <div class="print-data c-amount-words" :style="getStyle('amountWords')">** {{ strtoupper(amountToWords($cheque->amount)) }} **</div>
                
                <div class="print-data c-amount-num" :style="getStyle('amountNum')">*** {{ number_format($cheque->amount, 2) }} /- ***</div>
                
                <div class="print-data c-signature" :style="getStyle('signature')">
                    For {{ optional($cheque->firm)->name }}
                </div>
            </div>
        </div>

    </div>

    <script>
        function chequeSettings() {
            return {
                showSettings: false,
                templateBank: 'none',
                settings: {
                    acPayee: { top: 17, left: 10, fontSize: 13 },
                    date: { top: 7, left: 163, letterSpacing: 4.5, fontSize: 14 },
                    payee: { top: 27, left: 35, fontSize: 15 },
                    amountWords: { top: 40, left: 25, width: 140, lineHeight: 8, fontSize: 13 },
                    amountNum: { top: 39, left: 165, fontSize: 16 },
                    signature: { top: 61, left: 155, width: 40, fontSize: 14 }
                },
                initSettings() {
                    const savedSettings = localStorage.getItem('chequePrinterSettingsGlobalV2');
                    const savedBank = localStorage.getItem('chequePrinterBankSelection');
                    
                    if (savedSettings) {
                        try {
                            this.settings = JSON.parse(savedSettings);
                        } catch (e) {
                            // Defaults
                        }
                    }
                    if (savedBank) {
                        this.templateBank = savedBank;
                    }
                    
                    this.$watch('settings', value => {
                        localStorage.setItem('chequePrinterSettingsGlobalV2', JSON.stringify(value));
                    }, { deep: true });
                    
                    this.$watch('templateBank', value => {
                        localStorage.setItem('chequePrinterBankSelection', value);
                    });
                },
                resetSettings() {
                    if (confirm('Reset settings to standard defaults?')) {
                        this.settings = {
                            acPayee: { top: 17, left: 10, fontSize: 13 },
                            date: { top: 7, left: 163, letterSpacing: 4.5, fontSize: 14 },
                            payee: { top: 27, left: 35, fontSize: 14 },
                            amountWords: { top: 40, left: 25, width: 140, lineHeight: 8, fontSize: 13 },
                            amountNum: { top: 39, left: 165, fontSize: 15 },
                            signature: { top: 61, left: 155, width: 40, fontSize: 14 }
                        };
                    }
                },
                getStyle(key) {
                    const s = this.settings[key];
                    if (!s) return {};
                    let style = {};
                    if (s.top !== undefined) style.top = s.top + 'mm';
                    if (s.bottom !== undefined) style.bottom = s.bottom + 'mm';
                    if (s.left !== undefined) style.left = s.left + 'mm';
                    if (s.right !== undefined) style.right = s.right + 'mm';
                    if (s.width !== undefined) style.width = s.width + 'mm';
                    if (s.fontSize !== undefined) style.fontSize = s.fontSize + 'px';
                    if (s.letterSpacing !== undefined) style.letterSpacing = s.letterSpacing + 'mm';
                    if (s.lineHeight !== undefined) style.lineHeight = s.lineHeight + 'mm';
                    return style;
                }
            }
        }
    </script>
</body>
</html>
