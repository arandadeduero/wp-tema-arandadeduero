#!/usr/bin/env node
/**
 * Descarga el catálogo de trámites de la sede electrónica y lo guarda en data/sede/tramites.json, que usa el buscador
 * «¿Qué necesitas?» (inc/search.php). Una sola petición: la propia página del catálogo lleva todos los trámites dentro, en un JSON.
 *
 * Uso: node scripts/update-sede-catalog.js
 */
const fs = require('fs');
const path = require('path');

const URL_CATALOGO = 'https://sede.arandadeduero.es/sta/CarpetaPublic/doEvent?APP_CODE=STA&PAGE_CODE=CATALOGO';
const OUTPUT = path.join(__dirname, '..', 'data', 'sede', 'tramites.json');

/** El primer array de objetos con "dboid" que se pueda leer entero y sea el más largo (la lista de trámites). */
function extractProcedures(html) {
    let best = [];
    for (const match of html.matchAll(/\[\{"dboid"/g)) {
        let depth = 0;
        let inString = false;
        let escaped = false;
        for (let i = match.index; i < html.length; i++) {
            const c = html[i];
            if (inString) {
                if (escaped) escaped = false;
                else if (c === '\\') escaped = true;
                else if (c === '"') inString = false;
            } else if (c === '"') inString = true;
            else if (c === '[' || c === '{') depth++;
            else if (c === ']' || c === '}') {
                depth--;
                if (depth === 0) {
                    try {
                        const list = JSON.parse(html.slice(match.index, i + 1));
                        if (list.length > best.length) best = list;
                    } catch (e) {
                        // No era el array de trámites.
                    }
                    break;
                }
            }
        }
    }
    return best;
}

(async () => {
    const response = await fetch(URL_CATALOGO, { headers: { 'User-Agent': 'Mozilla/5.0' } });
    if (!response.ok) throw new Error(`La sede respondió ${response.status}`);

    const procedures = extractProcedures(await response.text());
    if (procedures.length < 100) throw new Error(`Solo se han leído ${procedures.length} trámites: la página ha cambiado.`);

    const items = procedures
        .map((p) => ({
            id: p.dboid,
            name: String(p.name).trim(),
            area: (p.keywordList || []).map((k) => k.description).join(' · '),
            presential: Boolean(p.presential),
            online: Boolean(p.telematic),
            phone: Boolean(p.telefonic),
        }))
        .sort((a, b) => a.name.localeCompare(b.name, 'es'));

    fs.mkdirSync(path.dirname(OUTPUT), { recursive: true });
    fs.writeFileSync(OUTPUT, JSON.stringify({ meta: { source: URL_CATALOGO, retrievedAt: new Date().toISOString().slice(0, 10), count: items.length }, procedures: items }, null, 1) + '\n');
    console.log(`${items.length} trámites guardados en ${path.relative(process.cwd(), OUTPUT)}`);
})().catch((error) => {
    console.error(error.message);
    process.exit(1);
});
