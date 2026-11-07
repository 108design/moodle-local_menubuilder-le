import fs from 'node:fs/promises';
import {createRequire} from 'node:module';
import {createHash} from 'node:crypto';
import path from 'node:path';
import {fileURLToPath} from 'node:url';
const plugin = fileURLToPath(new URL('../', import.meta.url));
const tool = fileURLToPath(new URL('./', import.meta.url));
const require = createRequire(path.join(tool, 'package.json'));
const {build} = require('esbuild');
const result = await build({entryPoints: [path.join(plugin, 'editor-src/menu-json-editor.js')],
    nodePaths: [path.join(tool, 'node_modules')], target: 'es2022', bundle: true, write: false,
    minify: true, format: 'iife', platform: 'browser', legalComments: 'inline', metafile: true,
    sourcemap: 'external', outfile: 'menu_json_editor.min.js'});
const code = result.outputFiles.find(f => f.path.endsWith('.min.js')).text.trimEnd()
    + '\n//# sourceMappingURL=menu_json_editor.min.js.map\n';
const map = JSON.parse(result.outputFiles.find(f => f.path.endsWith('.map')).text);
map.sources = map.sources.map(s => {
    const normal = s.replaceAll('\\', '/');
    return normal.includes('/node_modules/') ? 'thirdparty/' + normal.split('/node_modules/').at(-1)
        : 'editor-src/' + normal.split('/').at(-1);
});
map.x_108design_code_sha256_lf = createHash('sha256').update(code).digest('hex');
await fs.writeFile(path.join(plugin, 'amd/build/menu_json_editor.min.js'), code);
await fs.writeFile(path.join(plugin, 'amd/build/menu_json_editor.min.js.map'), JSON.stringify(map) + '\n');
const licences = path.join(plugin, 'thirdparty/codemirror-json');
await fs.mkdir(licences, {recursive: true});
const lock = JSON.parse(await fs.readFile(path.join(tool, 'package-lock.json'), 'utf8'));
for (const [name, info] of Object.entries(lock.packages)) {
    if (!name.startsWith('node_modules/') || name.includes('esbuild')) continue;
    const file = path.join(tool, name, 'LICENSE');
    const target = name.slice('node_modules/'.length).replaceAll('/', '-');
    const content = await fs.readFile(file).catch(() => null);
    if (!content) throw new Error('Missing runtime library licence: ' + name);
    await fs.writeFile(path.join(licences, target + '-LICENSE'), content);
}
console.log('Portable JSON editor AMD/map pair and runtime MIT notices generated.');
