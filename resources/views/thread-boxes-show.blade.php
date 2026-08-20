<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thread Boxes Entry Details - {{ $threadBox->company_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #000; margin: 0; padding: 20px; }
        .print-container { width: 100%; max-width: 800px; margin: 0 auto; padding: 40px; background: #fff; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); border-radius: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .uppercase { text-transform: uppercase; }
        .bold { font-weight: bold; }
        .mb-4 { margin-bottom: 15px; }
        .mb-6 { margin-bottom: 25px; }
        .mt-4 { margin-top: 15px; }
        .flex-between { display: flex; justify-content: space-between; }
        .text-sm { font-size: 14px; }
        
        .table-borders { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .table-borders th, .table-borders td { border: 1px solid #000; padding: 10px; font-size: 14px; }
        .table-borders th { font-weight: bold; background-color: #f1f5f9; text-align: center; }
        
        .title-header { font-size: 24px; font-weight: 800; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 8px; display: inline-block; letter-spacing: 1px; }
        
        .action-bar { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; align-items: center; }
        .back-btn { background: #4f46e5; color: white; border: none; padding: 10px 24px; font-weight: bold; font-size: 14px; cursor: pointer; border-radius: 6px; font-family: inherit; transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .back-btn:hover { background: #4338ca; }
        
        .image-container { margin-top: 40px; border: 2px dashed #cbd5e1; padding: 10px; border-radius: 8px; background: #f8fafc; text-align: center; }
        .image-container img { max-width: 100%; height: auto; border-radius: 4px; max-height: 600px; object-fit: contain; }
        .image-title { font-weight: bold; margin-bottom: 15px; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="action-bar">
            <div style="font-weight: bold; color: #475569; font-size: 14px;">ENTRY DETAILS</div>
            <a href="{{ route('thread-boxes.index') }}" class="back-btn">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                BACK TO LIST
            </a>
        </div>
        
        <div class="text-center mb-6">
            <div class="title-header uppercase">Thread Boxes Ch. Entry</div>
        </div>

        <div class="flex-between mb-6 text-sm bold" style="padding: 0 5px;">
            <div>
                <div style="font-size: 16px;">COMPANY NAME: <span class="uppercase" style="color: #4f46e5;">{{ $threadBox->company_name }}</span></div>
                @if($threadBox->ch_no)
                <div style="margin-top: 8px;">CH. NO: <span class="uppercase">{{ $threadBox->ch_no }}</span></div>
                @endif
            </div>
            <div style="text-align: right;">
                <div style="font-size: 16px;">DATE: {{ \Carbon\Carbon::parse($threadBox->date)->format('d/m/Y') }}</div>
            </div>
        </div>

        <table class="table-borders">
            <thead>
                <tr>
                    <th class="uppercase">Type of Box</th>
                    <th class="uppercase" style="width: 150px;">Box / Cone</th>
                    <th class="uppercase" style="width: 150px;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($threadBox->items as $item)
                <tr style="background-color: {{ $item->is_highlighted ? ($item->highlight_color ?? '#fef08a') : 'transparent' }};">
                    <td class="uppercase bold" style="padding-left: 15px;">{{ $item->type_of_box }}</td>
                    <td class="uppercase bold text-center">{{ $item->box_cone }}</td>
                    <td class="uppercase bold text-center">{{ rtrim(rtrim(number_format((float)$item->quantity, 2, '.', ''), '0'), '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="uppercase bold text-right" style="padding-right: 15px; background-color: #f8fafc;">TOTAL</td>
                    <td class="bold text-center" style="font-size: 18px; background-color: #f8fafc; color: #4f46e5;">{{ rtrim(rtrim(number_format((float)$threadBox->items->sum('quantity'), 2, '.', ''), '0'), '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($threadBox->remark)
        <div class="text-sm bold mt-4" style="border: 2px dashed #cbd5e1; padding: 15px; background-color: #f8fafc; border-radius: 4px;">
            REMARK/NOTE: <span class="uppercase" style="color: #475569; margin-left: 5px;">{{ $threadBox->remark }}</span>
        </div>
        @endif
        
        <div style="margin-top: 50px; display: flex; justify-content: space-between; font-size: 13px; font-weight: bold; color: #475569; padding: 0 20px;">
            <div class="text-center">
                <div style="border-top: 1px solid #94a3b8; width: 150px; margin-bottom: 5px;"></div>
                Receiver Sign
            </div>
            <div class="text-center">
                <div style="border-top: 1px solid #94a3b8; width: 150px; margin-bottom: 5px;"></div>
                Prepared By
            </div>
            <div class="text-center">
                <div style="border-top: 1px solid #94a3b8; width: 150px; margin-bottom: 5px;"></div>
                Authorized Sign
            </div>
        </div>

        @if($threadBox->image_path)
        <div class="image-container">
            <div class="image-title">Attached Image</div>
            <img src="{{ Storage::disk('public')->url($threadBox->image_path) }}" alt="Attachment for {{ $threadBox->ch_no }}">
        </div>
        @endif
    </div>
</body>
</html>
