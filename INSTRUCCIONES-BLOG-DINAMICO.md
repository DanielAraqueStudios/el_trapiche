// INSTRUCCIONES PARA CONFIGURAR BLOG DINÁMICO CON GOOGLE SHEETS

## PASO 1: Crear Google Form
1. Ir a forms.google.com
2. Crear formulario con estos campos:
   - Título (Texto corto)
   - Subtítulo (Texto corto)
   - Descripción (Párrafo)
   - Contenido (Párrafo)
   - URL de Imagen (Texto corto)

## PASO 2: Configurar Google Sheets
1. En el formulario, ir a "Respuestas" → "Ver en Sheets"
2. Se crea automáticamente una hoja con las respuestas

## PASO 3: Crear Apps Script
1. En Google Sheets, ir a Extensiones → Apps Script
2. Borrar todo el código y pegar:

```javascript
function doGet() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName('Respuestas de formulario 1');
  var data = sheet.getDataRange().getValues();
  var headers = data[0];
  var jsonData = [];
  
  for (var i = 1; i < data.length; i++) {
    var row = {};
    row.titulo = data[i][1];        // Columna B
    row.subtitulo = data[i][2];     // Columna C
    row.descripcion = data[i][3];   // Columna D
    row.contenido = data[i][4];     // Columna E
    row.imagen = data[i][5];        // Columna F
    jsonData.push(row);
  }
  
  // Devolver solo el último artículo
  var lastArticle = jsonData[jsonData.length - 1];
  return ContentService.createTextOutput(JSON.stringify([lastArticle]))
    .setMimeType(ContentService.MimeType.JSON);
}
```

3. Guardar el proyecto
4. Hacer clic en "Implementar" → "Nueva implementación"
5. Tipo: "Aplicación web"
6. Ejecutar como: "Yo"
7. Quién tiene acceso: "Cualquier persona"
8. Copiar la URL que genera

## PASO 4: Configurar blog-dinamico.html
1. Abrir blog-dinamico.html
2. Buscar: `const SHEET_URL = 'TU_URL_AQUI';`
3. Reemplazar con la URL del Apps Script

## PASO 5: Subir Imágenes
Opción A - Google Drive:
1. Subir imagen a Google Drive
2. Hacer clic derecho → "Compartir" → "Cualquiera con el enlace"
3. Copiar ID del archivo (está en la URL)
4. URL final: `https://drive.google.com/uc?id=ID_DEL_ARCHIVO`

Opción B - Imgur:
1. Ir a imgur.com
2. Subir imagen
3. Copiar enlace directo

## FORMATO DE COLUMNAS EN GOOGLE SHEETS:
A: Marca temporal (automático)
B: Título
C: Subtítulo
D: Descripción
E: Contenido (puede usar HTML básico)
F: URL de Imagen

## EJEMPLO DE RESPUESTA:
Título: "Beneficios de la Panela"
Subtítulo: "Un Endulzante Natural"
Descripción: "La panela es rica en nutrientes..."
Contenido: "<h3>Nutrientes</h3><p>Calcio, hierro...</p>"
Imagen: "https://drive.google.com/uc?id=ABC123"

## NOTAS:
- El blog siempre muestra el último artículo agregado
- Las imágenes deben ser públicas
- El contenido puede incluir HTML básico
- Actualización automática al recargar la página
