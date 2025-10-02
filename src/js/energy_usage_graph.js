/* ---------- helper: fetch/load ---------- */
async function loadData(type, year, month) {
    // Build URL safely
    let url = `controller/whole_get_usage.php?type=${encodeURIComponent(type)}`;
    if (year !== undefined && year !== null && year !== '') {
        url += `&year=${encodeURIComponent(year)}`;
    }
    // Only append month for daily/weekly (not for monthly)
    if (month !== undefined && month !== null && month !== '' && (type === 'daily' || type === 'weekly')) {
        url += `&month=${encodeURIComponent(month)}`;
    }

    const resp = await fetch(url);
    const text = await resp.text();

    try {
        const data = JSON.parse(text);
        if (!Array.isArray(data)) return [];
        return data.map(item => ({
            date: new Date(item.date).getTime(),
            value: parseFloat(item.usage) || 0
        }));
    } catch (e) {
        console.error("Invalid JSON from get_usage.php:", text);
        return [];
    }
}

/* ---------- UI init ---------- */
const chartContainer = document.getElementById("chartContainer");
let currentType = "daily"; // no submeterId needed anymore

am5.ready(async function() {
    // Populate month/year selects
    const monthSelect = document.getElementById("monthSelect");
    const yearSelect = document.getElementById("yearSelect");
    (function populateMonthYear() {
        const months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        monthSelect.innerHTML = "";
        const now = new Date();
        months.forEach((m, idx) => {
            const opt = document.createElement("option");
            opt.value = idx + 1; // 1..12
            opt.text = m;
            if (idx === now.getMonth()) opt.selected = true;
            monthSelect.appendChild(opt);
        });

        yearSelect.innerHTML = "";
        const startYear = now.getFullYear();
        for (let y = startYear; y >= startYear - 5; y--) {
            const opt = document.createElement("option");
            opt.value = y;
            opt.text = y;
            if (y === startYear) opt.selected = true;
            yearSelect.appendChild(opt);
        }
    })();

    // amCharts setup
    var root = am5.Root.new("usage_estimate_tenant");

    const myTheme = am5.Theme.new(root);
    myTheme.rule("AxisLabel", ["minor"]).setAll({ dy: 1 });
    myTheme.rule("Grid", ["minor"]).setAll({ strokeOpacity: 0.08 });

    root.setThemes([am5themes_Animated.new(root), myTheme]);

    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: false,
        wheelX: "panX",
        wheelY: "zoomX",
        paddingLeft: 0
    }));

    // Cursor
    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none" }));
    cursor.lineY.set("visible", false);
    cursor.lineX.set("visible", true);

    // X Axis
    var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
        baseInterval: { timeUnit: "day", count: 1 },
        renderer: am5xy.AxisRendererX.new(root, {
            minorGridEnabled: true,
            minGridDistance: 80,
            minorLabelsEnabled: true
        }),
        tooltip: am5.Tooltip.new(root, {})
    }));
    if (xAxis.get("tooltip")) xAxis.get("tooltip").set("visible", false);
    xAxis.set("minorDateFormats", { day: "dd", month: "MM" });

    // Y Axis
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
            labelText: "{valueY.formatNumber('#,###.###')} kWh"
        })
    }));
    series.strokes.template.setAll({ strokeWidth: 2 });

    // Bullets (points)
    series.bullets.push(function(root, series, dataItem) {
        var circle = am5.Circle.new(root, {
            radius: 5,
            fill: series.get("fill"),
            stroke: root.interfaceColors.get("background"),
            strokeWidth: 1,
            tooltipText: "{valueY.formatNumber('#,###.###')} kWh"
        });
        circle.states.create("hover", { scale: 1.6 });
        return am5.Bullet.new(root, { sprite: circle });
    });

    // Scrollbar
    const scrollbarX = am5.Scrollbar.new(root, { orientation: "horizontal" });
    scrollbarX.set("marginBottom", 50);
    chart.set("scrollbarX", scrollbarX);

    // No-data label
    let noDataLabel = chart.children.push(am5.Label.new(root, {
        text: "No data available for selected period",
        fontSize: 18,
        fill: am5.color(0x888888),
        centerX: am5.p50,
        centerY: am5.p50,
        visible: true
    }));

    // Helper to load and set series
    async function setSeriesData(type) {
        const yearVal = yearSelect.value;
        const monthVal = (type === 'monthly') ? null : monthSelect.value;

        const items = await loadData(type, yearVal, monthVal);

        if (!items || items.length === 0) {
            chart.set("visible", false);
            noDataLabel.set("visible", true);
            series.data.setAll([]);
            return;
        } else {
            chart.set("visible", true);
            noDataLabel.set("visible", false);
        }

        let final = items.slice();
        if (type === 'daily') {
            final = final.slice(-31);
        } else if (type === 'weekly') {
            final = final.slice(-5);
        } else if (type === 'monthly') {
            final = final.slice(-12);
        }

        series.data.setAll(final);
        series.appear(600);
        chart.appear(600, 100);
    }

    // Buttons
    const buttons = ["dailyBtn", "weeklyBtn", "monthlyBtn"];
    buttons.forEach(id => {
        const btn = document.getElementById(id);
        btn.addEventListener("click", async function() {
            buttons.forEach(bid => document.getElementById(bid).classList.remove("active"));
            btn.classList.add("active");

            if (id === "dailyBtn") {
                currentType = "daily";
                xAxis.set("baseInterval", { timeUnit: "day", count: 1 });
                monthSelect.disabled = false;
                await setSeriesData("daily");
            } else if (id === "weeklyBtn") {
                currentType = "weekly";
                xAxis.set("baseInterval", { timeUnit: "week", count: 1 });
                monthSelect.disabled = false;
                await setSeriesData("weekly");
            } else if (id === "monthlyBtn") {
                currentType = "monthly";
                xAxis.set("baseInterval", { timeUnit: "month", count: 1 });
                monthSelect.disabled = true;
                await setSeriesData("monthly");
            }
        });
    });

    // Default = daily
    document.getElementById("dailyBtn").classList.add("active");
    monthSelect.disabled = false;
    await setSeriesData("daily");

    // React on change
    monthSelect.addEventListener("change", () => setSeriesData(currentType));
    yearSelect.addEventListener("change", () => setSeriesData(currentType));
});