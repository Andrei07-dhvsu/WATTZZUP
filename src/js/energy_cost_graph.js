am5.ready(function () {
    var root = am5.Root.new("S1");
    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true, panY: true,
        wheelX: "panX", wheelY: "zoomX",
        pinchZoomX: true,
        paddingLeft: 0, paddingRight: 1
    }));

    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30, minorGridEnabled: true });
    xRenderer.labels.template.setAll({ rotation: -90, centerY: am5.p50, centerX: am5.p100, paddingRight: 15 });
    xRenderer.grid.template.setAll({ location: 1 });

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        maxDeviation: 0.3,
        categoryField: "month",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {})
    }));

    var yRenderer = am5xy.AxisRendererY.new(root, { strokeOpacity: 0.1 });
    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        maxDeviation: 0.3,
        renderer: yRenderer,
        tooltip: am5.Tooltip.new(root, { labelText: "{valueY} ₱" })
    }));

    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Energy Cost",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "cost",
        sequencedInterpolation: true,
        categoryXField: "month",
        tooltip: am5.Tooltip.new(root, { labelText: "{valueY} ₱" })
    }));

    series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
    series.columns.template.adapters.add("fill", (fill, target) => chart.get("colors").getIndex(series.columns.indexOf(target)));
    series.columns.template.adapters.add("stroke", (stroke, target) => chart.get("colors").getIndex(series.columns.indexOf(target)));

    // 📌 Get submeterId from div data attribute



    // 📌 Function to load data dynamically
    function loadData(year) {
        fetch(`controller/whole_monthly_cost_data.php?year=${year}`)
            .then(res => res.json())
            .then(data => {
                xAxis.data.setAll(data);
                series.data.setAll(data);
            })
            .catch(err => console.error("Error loading data:", err));
    }

    // 📌 Populate year dropdown
    const yearSelect = document.getElementById("yearSelectCost");
    const currentYear = new Date().getFullYear();
    for (let y = currentYear; y >= currentYear - 5; y--) {
        let opt = document.createElement("option");
        opt.value = y;
        opt.text = y;
        if (y === currentYear) opt.selected = true;
        yearSelect.appendChild(opt);
    }

    // 📌 Load initial data (current year)
    loadData(currentYear);

    // 📌 Change data when selecting another year
    // Change year
    yearSelect.addEventListener("change", function () {
        loadData(this.value);
    });

    series.appear(1000);
    chart.appear(1000, 100);
});
