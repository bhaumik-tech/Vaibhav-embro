import os
import re

mapping = {
    'users': 'users',
    'firms': 'firms',
    'parties': 'parties',
    'machines': 'machines',
    'karigars': 'karigars',
    'input-chalan': 'input_chalan',
    'generate-chalans': 'generate_chalan',
    'output-chalans': 'output_chalan',
    'generate-bills': 'generate_bill',
    'purchase-bills': 'purchase_bill',
    'purchase-bill': 'purchase_bill',
    'bank-book': 'bank_book',
    'dhaga-cuttings': 'dh_cutting',
    'inter-exchange': 'inter_exchange',
    'thread-boxes': 'thread_boxes'
}

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    original_content = content

    # Find + Add buttons (usually have 'create' route)
    for route_prefix, perm_key in mapping.items():
        # Match <a href="{{ route('prefix.create') }}" ...>+ Add ...</a>
        # Pattern to match the whole a tag line
        add_pattern = r"(<a\s+[^>]*href=\"\{\{\s*route\('(?:" + route_prefix + r")\.create'\)\s*\}\}\"[^>]*>[\s\S]*?<\/a>)"
        
        def repl_add(m):
            # Check if already wrapped
            return f"@canpage('{perm_key}', 'edit')\n" + m.group(1) + f"\n@endcanpage"
            
        new_content = re.sub(add_pattern, repl_add, content)
        if new_content != content:
            # Clean up nested @canpage if it happened (just in case)
            if new_content.count(f"@canpage('{perm_key}', 'edit')") > content.count(f"@canpage('{perm_key}', 'edit')"):
                pass
            content = new_content

        # Match Edit button
        edit_pattern = r"(<a\s+[^>]*href=\"\{\{\s*route\('(?:" + route_prefix + r")\.edit'[^}]*\}\}\"[^>]*>[\s\S]*?<\/a>)"
        def repl_edit(m):
            return f"@canpage('{perm_key}', 'edit')\n" + m.group(1) + f"\n@endcanpage"
        content = re.sub(edit_pattern, repl_edit, content)

        # Match Destroy form
        destroy_pattern = r"(<form\s+[^>]*action=\"\{\{\s*route\('(?:" + route_prefix + r")\.destroy'[^}]*\}\}\"[^>]*>[\s\S]*?<\/form>)"
        def repl_destroy(m):
            return f"@canpage('{perm_key}', 'remove')\n" + m.group(1) + f"\n@endcanpage"
        content = re.sub(destroy_pattern, repl_destroy, content)

    # Double @canpage check
    content = re.sub(r"@canpage\('([^']+)', '([^']+)'\)\n@canpage\('\1', '\2'\)\n", r"@canpage('\1', '\2')\n", content)
    content = re.sub(r"\n@endcanpage\n@endcanpage", r"\n@endcanpage", content)

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {filepath}")

for root, _, files in os.walk('resources/views'):
    for file in files:
        if file.endswith('.blade.php'):
            process_file(os.path.join(root, file))
