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
    const chartContainer = document.getElementById("chartContainer");
    const submeterId = chartContainer.dataset.roomsubmeterId;

    // 📌 Function to load data dynamically
    function loadData(submeterId, year) {
        fetch(`controller/monthly_cost_data.php?year=${year}&submeter_id=${encodeURIComponent(submeterId)}`)
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
    loadData(submeterId, currentYear);

    // 📌 Change data when selecting another year
    yearSelect.addEventListener("change", function () {
        loadData(submeterId, this.value);
    });

    series.appear(1000);
    chart.appear(1000, 100);
});
//--------------------------------------------------------------------------------

// Add buttons for Daily, Weekly, Monthly
const container = document.getElementById("S2");
const buttonWrapper = document.createElement("div");
buttonWrapper.style.marginBottom = "10px";
buttonWrapper.innerHTML = `
    <button id="dailyBtn">Daily</button>
    <button id="weeklyBtn">Weekly</button>
    <button id="monthlyBtn">Monthly</button>
`;
container.parentNode.insertBefore(buttonWrapper, container);

// Example data sets
const dailyData = [
    { date: "2024-06-01", usage: 35 },
    { date: "2024-06-02", usage: 40 },
    { date: "2024-06-03", usage: 38 },
    { date: "2024-06-04", usage: 42 },
    { date: "2024-06-05", usage: 37 },
    { date: "2024-06-06", usage: 45 },
    { date: "2024-06-07", usage: 39 },
    { date: "2024-06-08", usage: 41 },
    { date: "2024-06-09", usage: 36 },
    { date: "2024-06-10", usage: 43 }
];

const weeklyData = [
    { date: "2024-05-27", usage: 270 },
    { date: "2024-06-03", usage: 320 },
    { date: "2024-06-10", usage: 305 }
];

const monthlyData = [
    { date: "2024-04-01", usage: 1200 },
    { date: "2024-05-01", usage: 1350 },
    { date: "2024-06-01", usage: 1280 }
];

// Helper to convert data
function convertData(data, timeUnit) {
    return data.map(function(item) {
        return {
            date: new Date(item.date).getTime(),
            value: item.usage
        };
    });
}

am5.ready(function() {
    var root = am5.Root.new("S2");

    // Custom theme for minor labels/grid
    const myTheme = am5.Theme.new(root);
    myTheme.rule("AxisLabel", ["minor"]).setAll({ dy: 1 });
    myTheme.rule("Grid", ["minor"]).setAll({ strokeOpacity: 0.08 });

    root.setThemes([
        am5themes_Animated.new(root),
        myTheme
    ]);

    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: false,
        panY: false,
        wheelX: "panX",
        wheelY: "zoomX",
        paddingLeft: 0
    }));

    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
        behavior: "zoomX"
    }));
    cursor.lineY.set("visible", false);

    // Axes
    var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
        maxDeviation: 0,
        baseInterval: { timeUnit: "day", count: 1 },
        renderer: am5xy.AxisRendererX.new(root, {
            minorGridEnabled: true,
            minGridDistance: 80,
            minorLabelsEnabled: true
        }),
        tooltip: am5.Tooltip.new(root, {})
    }));
    xAxis.set("minorDateFormats", { day: "dd", month: "MM" });

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {})
    }));

    // Series
    var series = chart.series.push(am5xy.LineSeries.new(root, {
        name: "Energy Usage",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "value",
        valueXField: "date",
        tooltip: am5.Tooltip.new(root, {
            labelText: "{valueY} kWh"
        })
    }));

    series.bullets.push(function () {
        var bulletCircle = am5.Circle.new(root, {
            radius: 5,
            fill: series.get("fill")
        });
        return am5.Bullet.new(root, {
            sprite: bulletCircle
        })
    });

    // Move scrollbar below the chart with margin
    const scrollbarX = am5.Scrollbar.new(root, {
        orientation: "horizontal"
    });
    scrollbarX.set("marginBottom", 50); // Add space between chart and scrollbar
    chart.set("scrollbarX", scrollbarX);

    // Initial data
    series.data.setAll(convertData(dailyData, "day"));

    // Animate on load
    series.appear(1000);
    chart.appear(1000, 100);

    // Button handlers
    document.getElementById("dailyBtn").onclick = function() {
        xAxis.set("baseInterval", { timeUnit: "day", count: 1 });
        xAxis.set("groupData", false);
        series.data.setAll(convertData(dailyData, "day"));
    };
    document.getElementById("weeklyBtn").onclick = function() {
        xAxis.set("baseInterval", { timeUnit: "week", count: 1 });
        xAxis.set("groupData", false);
        series.data.setAll(convertData(weeklyData, "week"));
    };
    document.getElementById("monthlyBtn").onclick = function() {
        xAxis.set("baseInterval", { timeUnit: "month", count: 1 });
        xAxis.set("groupData", false);
        series.data.setAll(convertData(monthlyData, "month"));
    };
}); // end am5.ready()

//--------------------------------------------------------------------------------

