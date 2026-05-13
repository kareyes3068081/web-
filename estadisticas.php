<?php include 'includes/header.php'; ?>
<?php include 'php/conexion.php'; ?>
 
<?php
 
// Platillos por estado Sin LIMIT 
$q1 = "SELECT e.nombre AS estado, COUNT(c.id_comidas) AS total
        FROM estados e
        LEFT JOIN comidas c ON e.id_estados = c.id_estados
        GROUP BY e.id_estados, e.nombre
        ORDER BY total DESC";
        
$r1 = $conexion->query($q1);
$platillos_por_estado = [];

while ($row = $r1->fetch_assoc()) {
    $platillos_por_estado[] = $row;
}
 
$totalPlatillos = array_sum(array_column($platillos_por_estado, 'total'));
$totalEstados   = count($platillos_por_estado);
$topEstado      = $platillos_por_estado[0] ?? ['estado' => 'N/A', 'total' => 0];
$jsonDatos      = json_encode($platillos_por_estado);
?>
 
<div class="stats-wrapper">
    <h1 class="stats-titulo">Estadísticas</h1>
    <p class="stats-sub">Visualización interactiva de la gastronomía mexicana en nuestra base de datos.</p>
 
    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <span class="kpi-numero" id="kpi-platillos">0</span>
            <span class="kpi-label">Platillos registrados</span>
        </div>
        <div class="kpi-card accent">
            <span class="kpi-numero"><?= $totalEstados ?></span>
            <span class="kpi-label">Estados con datos</span>
        </div>
        <div class="kpi-card">
            <span class="kpi-numero" style="font-size:1.5rem;padding-top:8px"><?= htmlspecialchars($topEstado['estado']) ?></span>
            <span class="kpi-label">Estado con más platillos</span>
        </div>
    </div>
 
    <!-- Gráficos -->
    <div class="charts-grid">
 
        <!-- DONA -->
        <div class="chart-card">
            <div class="chart-titulo">Distribución por estado</div>
            <canvas id="canvasDona" height="220"></canvas>
            <div class="leyenda-dona" id="leyendaDona"></div>
        </div>
 
        <!-- BARRAS SVG -->
        <div class="chart-card">
            <div class="chart-titulo">Top estados</div>
            <div class="tabs-wrap">
                <button class="tab-btn active" onclick="ordenarBarras('desc',this)">Mayor → Menor</button>
                <button class="tab-btn"        onclick="ordenarBarras('asc', this)">Menor → Mayor</button>
            </div>
            <div class="barras-scroll-wrap">
                <svg id="svgBarras" class="svg-barras" aria-label="Gráfico de barras horizontales por estado"></svg>
            </div>
        </div>
 
        <!-- LÍNEA full-width -->
        <div class="chart-card full">
            <div class="chart-titulo">Comparativa acumulada de platillos</div>
            <canvas id="canvasLinea" height="180"></canvas>
        </div>
 
    </div>
</div>
 
<!-- Tooltip flotante -->
<div class="chart-tooltip" id="tooltip"></div>
 
<script>
    const jsonDatos_PHP = <?= $jsonDatos ?>;
    const totalPlatillos_PHP = <?= $totalPlatillos ?>;
</script>
 
<?php include 'includes/footer.php'; ?>