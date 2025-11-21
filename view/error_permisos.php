<!-- Vista de Eroor de los permisos y vuelve a la lista del ultimo controlador -->

<div class="alert alert-danger text-center mt-4">
    <h3>⚠ Permiso denegado</h3>
    <p>No tienes permisos para realizar esta acción.</p>

    <a href="index.php?controller=<?= htmlspecialchars($_SESSION["lastController"] ?? '') ?>&action=list"
       class="btn btn-primary mt-3">
       Volver al listado
    </a>
</div>