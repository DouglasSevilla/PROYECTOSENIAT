<?php
// Vista para reportes
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-seniat-rojo"><i class="bi bi-bar-chart-fill me-2"></i>Reportes</h2>
            <p class="text-muted">Genera reportes de asistencia e incidencias</p>
        </div>
    </div>

    <!-- Filtros de reporte -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="formReportes">
                        <div class="row">
                                <div class="col-md-3">
                                <label class="form-label">Tipo de Reporte</label>
                                <select class="form-select" name="tipo_reporte" id="tipo_reporte" required>
                                    <option value="asistencia">Asistencia</option>
                                    <option value="incidencias">Incidencias</option>
                                    <option value="general">General</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                                <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-seniat-rojo w-100">
                                    <i class="bi bi-search me-2"></i>Generar
                                </button>
                            </div>
                        </div>
                    </form>
                    <script>
                    document.getElementById('formReportes').addEventListener('submit', function(e){
                        e.preventDefault();
                        const form = e.target;
                        const fd = new FormData(form);
                        const tipo = document.getElementById('tipo_reporte').value;
                        if (tipo === 'asistencia') {
                            fd.append('accion', 'generar_reporte_asistencia');
                        } else if (tipo === 'incidencias') {
                            fd.append('accion', 'generar_reporte_incidencias');
                        } else {
                            alert('Tipo de reporte no soportado por ahora');
                            return;
                        }
                        fetch('controlador/reportes-controlador.php', { method: 'POST', body: fd })
                        .then(res => res.json())
                        .then(result => {
                            if (result.success) {
                                const cont = document.getElementById('resultadosReporte');
                                cont.innerHTML = '<pre>' + JSON.stringify(result.data, null, 2) + '</pre>';
                            } else {
                                alert(result.mensaje || 'Error al generar reporte');
                            }
                        })
                        .catch(err => { console.error(err); alert('Error de red'); });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados del reporte -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Resultados</h5>
                    <div id="resultadosReporte">
                        <p class="text-muted text-center py-5">Selecciona los filtros y genera un reporte</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
