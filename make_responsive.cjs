const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    fs.readdirSync(dir).forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(dirPath);
    });
}

let modifiedFiles = 0;

walkDir('d:/xampp/htdocs/vaibhav-embro/resources/views', (filePath) => {
    if (!filePath.endsWith('.blade.php')) return;
    
    let original = fs.readFileSync(filePath, 'utf8');
    let content = original;
    
    // 1. Header Flex Fix: "flex items-center justify-between" on header/top containers
    content = content.replace(/class=\"([^\"]*)flex items-center justify-between([^\"]*)\"/g, (match, p1, p2) => {
        if (p1.includes('flex-col') || p2.includes('flex-col')) return match;
        if (p1.includes('p-4') || p2.includes('p-4') || p1.includes('mb-6') || p2.includes('mb-6') || p1.includes('gap-4') || p2.includes('gap-4') || p1.includes('bg-white') || p2.includes('bg-white')) {
            return `class="${p1}flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4${p2}"`.replace(/gap-4 gap-4/g, 'gap-4');
        }
        return match;
    });

    // 2. Grid Fix: grid-cols-2 -> grid-cols-1 md:grid-cols-2
    content = content.replace(/class=\"([^\"]*)grid-cols-([23456])([^\"]*)\"/g, (match, p1, p2, p3) => {
        if (p1.includes('md:grid-cols-') || p1.includes('sm:grid-cols-') || p1.includes('lg:grid-cols-')) return match;
        return `class="${p1}grid-cols-1 md:grid-cols-${p2}${p3}"`;
    });

    // 3. Flex headers in index pages that don't have justify-between but have gap-4 mb-6 shrink-0
    content = content.replace(/class=\"([^\"]*)flex items-center gap-4 mb-6 shrink-0([^\"]*)\"/g, (match, p1, p2) => {
        if (p1.includes('flex-col') || p2.includes('flex-col')) return match;
        return `class="${p1}flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0${p2}"`;
    });

    if (content !== original) {
        fs.writeFileSync(filePath, content, 'utf8');
        console.log('Modified:', filePath);
        modifiedFiles++;
    }
});

console.log('Total modified files:', modifiedFiles);
