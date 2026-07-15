const fs = require('fs');

function fixFiles(files) {
    files.forEach(file => {
        let content = fs.readFileSync(file, 'utf8');
        let original = content;

        // Wrap tables that are not wrapped in overflow-auto
        content = content.replace(/<div class="[^"]*border[^"]*">\s*<table/g, (match) => {
            return match.replace('<table', '<div class="overflow-x-auto"><table');
        });
        content = content.replace(/<\/table>\s*<\/div>/g, (match) => {
            if (original.includes('overflow-x-auto') || content.includes('overflow-x-auto')) {
               return match.replace('</table>', '</table></div>');
            }
            return match;
        });

        // specific flex gap-4 to flex-col md:flex-row
        content = content.replace(/<div class="flex gap-4/g, '<div class="flex flex-col md:flex-row gap-4');
        content = content.replace(/<div class="flex items-center gap-4 pt-6 mt-4">/g, '<div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 pt-6 mt-4">');
        content = content.replace(/<div class="flex items-center gap-4">/g, '<div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">');
        content = content.replace(/<div class="flex items-center gap-4 flex-1">/g, '<div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 flex-1">');

        if (content !== original) {
            fs.writeFileSync(file, content);
            console.log('Fixed', file);
        }
    });
}

fixFiles([
    'd:/xampp/htdocs/vaibhav-embro/resources/views/dhaga-cuttings/index.blade.php',
    'd:/xampp/htdocs/vaibhav-embro/resources/views/users/create.blade.php',
    'd:/xampp/htdocs/vaibhav-embro/resources/views/users/edit.blade.php',
    'd:/xampp/htdocs/vaibhav-embro/resources/views/users/index.blade.php'
]);
