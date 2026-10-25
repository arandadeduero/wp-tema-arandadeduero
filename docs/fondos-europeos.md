# Fondos europeos: una ficha por proyecto

Tipo de contenido `proyecto-europeo` (URL `/fondos-europeos/<proyecto>/`, menú *Proyectos europeos* de wp-admin), en
`inc/content-types.php` e `inc/eu-funds.php`. Sustituye a los apartados sueltos que tenía la página
`/fondos-nextgenerationeu-prtr/`, que pasa a ser el índice.

## La ficha

- **Datos del proyecto** (caja bajo el editor): programa, organismo y convocatoria, ayuda de la Unión Europea, inversión
  total y estado (concedido, en ejecución, terminado). Los vacíos no se muestran. Los importes, en euros con punto para los
  céntimos: `1672367.45`.
- **Contenido** (editor): descripción, barra de logos del proyecto (imagen), contratos, documentos.
- **Lo que pone siempre el tema:** el enlace «Volver a Fondos europeos» y, al final, el lema obligatorio del PRTR **como
  texto** («Plan de Recuperación, Transformación y Resiliencia – Financiado por la Unión Europea – NextGenerationEU»,
  Orden HFP/1030/2021, art. 9.3.b), no solo dentro de los logos.

Si el programa del proyecto dice «REACT-EU», el lema es el de ese fondo (FEDER, Reglamento (UE) 1303/2013, anexo XII):
«Financiado por la Unión Europea, a través del Fondo Europeo de Desarrollo Regional (FEDER), como parte de la respuesta de la
Unión a la pandemia de COVID-19 – Una manera de hacer Europa». Es el caso del equipamiento de Bomberos (2023).

## El índice

En la página `/fondos-nextgenerationeu-prtr/`, el shortcode `[proyectos_europeos]` pinta el total de ayudas de la UE de
los proyectos que tienen importe y una tarjeta por proyecto publicado (programa, estado y ayuda). El resto de la página
(introducción, política antifraude) se edita como siempre. Los proyectos también salen en el buscador.

## Contratos

Las tablas «Contratos adjudicados» de cada ficha salen de los datos abiertos de la Plataforma de Contratación
(https://willyaranda.github.io/contratacion-estado/datos/contratos.json, campo `nextgeneration`: PRTR, NextGenerationEU o REACT-EU), solo los adjudicados, a
fecha 2026-10-25. No se actualizan solas: con un contrato nuevo, añadir su fila a la tabla de la ficha.

## Pendiente de confirmar con el departamento

- La ayuda concedida de cada proyecto salvo la piscina (resolución definitiva del PIREP: 1.672.367,45 €).
- La barra de logos común («falta el logo del Ayto» en los escaneos).
