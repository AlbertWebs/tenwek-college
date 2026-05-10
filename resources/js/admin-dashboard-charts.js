import Chart from 'chart.js/auto';

const palette = ['#1a1a68', '#8265ae', '#62a3a1', '#4a4a8f', '#9d7fc4', '#7ebab8', '#c45a8c', '#3d6b69'];

function chartFontFamily() {
    return "'Instrument Sans', ui-sans-serif, system-ui, sans-serif";
}

function commonOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: 'rgb(68 68 68 / 0.92)',
                    font: { family: chartFontFamily(), size: 12 },
                    boxWidth: 10,
                    boxHeight: 10,
                    padding: 14,
                },
            },
            tooltip: {
                backgroundColor: 'rgb(26 26 104 / 0.94)',
                titleFont: { family: chartFontFamily(), size: 13 },
                bodyFont: { family: chartFontFamily(), size: 12 },
                padding: 10,
                cornerRadius: 8,
            },
        },
    };
}

/**
 * @param {HTMLElement} root
 */
export function initAdminDashboard(root) {
    const raw = root.getAttribute('data-charts');
    if (!raw) {
        return;
    }

    let data;
    try {
        data = JSON.parse(raw);
    } catch {
        return;
    }

    const trendCanvas = root.querySelector('canvas[data-chart="trend"]');
    const mixCanvas = root.querySelector('canvas[data-chart="mix"]');
    const formsCanvas = root.querySelector('canvas[data-chart="forms"]');

    if (trendCanvas && Array.isArray(data.trend) && data.trend.length > 0) {
        const labels = data.trend.map((d) => d.label);
        const counts = data.trend.map((d) => d.count);

        new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Form submissions',
                        data: counts,
                        borderColor: '#8265ae',
                        backgroundColor: 'rgb(130 101 174 / 0.14)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#1a1a68',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5,
                    },
                ],
            },
            options: {
                ...commonOptions(),
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: 'rgb(68 68 68 / 0.75)',
                            font: { family: chartFontFamily(), size: 11 },
                            maxRotation: 45,
                            minRotation: 0,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: 'rgb(68 68 68 / 0.75)',
                            font: { family: chartFontFamily(), size: 11 },
                        },
                        grid: { color: 'rgb(26 26 104 / 0.06)' },
                    },
                },
            },
        });
    }

    if (mixCanvas && data.contentMix && typeof data.contentMix === 'object') {
        const entries = Object.entries(data.contentMix).filter(([, v]) => typeof v === 'number');
        const sum = entries.reduce((acc, [, v]) => acc + v, 0);

        if (sum > 0) {
            const labelMap = {
                pages: 'Pages',
                downloads: 'Downloads',
                news: 'News (published)',
                media: 'Media library',
            };

            new Chart(mixCanvas, {
                type: 'doughnut',
                data: {
                    labels: entries.map(([k]) => labelMap[k] ?? k),
                    datasets: [
                        {
                            data: entries.map(([, v]) => v),
                            backgroundColor: entries.map((_, i) => palette[i % palette.length]),
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6,
                        },
                    ],
                },
                options: {
                    ...commonOptions(),
                    cutout: '58%',
                },
            });
        }
    }

    if (formsCanvas && data.forms && typeof data.forms === 'object') {
        const entries = Object.entries(data.forms);
        if (entries.length > 0) {
            const labels = entries.map(([k]) =>
                k.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase()),
            );
            const values = entries.map(([, v]) => v);

            new Chart(formsCanvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Submissions',
                            data: values,
                            backgroundColor: entries.map((_, i) => palette[(i + 2) % palette.length]),
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                    ],
                },
                options: {
                    ...commonOptions(),
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: 'rgb(68 68 68 / 0.75)',
                                font: { family: chartFontFamily(), size: 11 },
                            },
                            grid: { color: 'rgb(26 26 104 / 0.06)' },
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                color: 'rgb(68 68 68 / 0.85)',
                                font: { family: chartFontFamily(), size: 11 },
                            },
                        },
                    },
                },
            });
        }
    }
}
