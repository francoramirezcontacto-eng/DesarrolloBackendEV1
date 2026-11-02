# GitHub: publicación académica privada

Una vez ejecutado y verificado el proyecto Laravel completo:

1. Crear desde GitHub un repositorio **privado**, por ejemplo `hielos-del-maipo-backend-u1`, sin inicializar con README remoto si ya existe el local.
2. Desde el directorio raíz donde se encuentran `artisan` y `composer.json`:

   ```bash
   git init
   git add .
   git status
   git commit -m "Backend U1: CRUD de solicitudes y pruebas"
   git branch -M main
   git remote add origin https://github.com/TU_USUARIO/hielos-del-maipo-backend-u1.git
   git push -u origin main
   ```

3. En `git status` y `git ls-files`, revisar que NO aparezcan `.env`, `vendor`, `database.sqlite`, archivos de clientes reales ni claves.
4. Invitar al docente según el canal y cuenta indicados institucionalmente, manteniendo el repositorio privado.
5. Pegar la URL del repositorio y, si corresponde, las evidencias finales en la entrega de EVA.

No copiar información de clientes reales, registros comerciales privados ni credenciales de la ficha de la empresa en el repositorio.
