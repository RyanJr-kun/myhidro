/**
 * Dashboard Analytics
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  let cardColor, headingColor, legendColor, labelColor, shadeColor, borderColor, fontFamily;
  cardColor = config.colors.cardColor;
  headingColor = config.colors.headingColor;
  legendColor = config.colors.bodyColor;
  labelColor = config.colors.textMuted;
  borderColor = config.colors.borderColor;
  fontFamily = config.fontFamily;

  // Order Area Chart
  // --------------------------------------------------------------------
  const orderAreaChartEl = document.querySelector('#orderChart'),
    orderAreaChartConfig = {
      chart: {
        height: 80,
        type: 'area',
        toolbar: {
          show: false
        },
        sparkline: {
          enabled: true
        }
      },
      markers: {
        size: 6,
        colors: 'transparent',
        strokeColors: 'transparent',
        strokeWidth: 4,
        discrete: [
          {
            fillColor: cardColor,
            seriesIndex: 0,
            dataPointIndex: 6,
            strokeColor: config.colors.success,
            strokeWidth: 2,
            size: 6,
            radius: 8
          }
        ],
        offsetX: -1,
        hover: {
          size: 7
        }
      },
      grid: {
        show: false,
        padding: {
          top: 15,
          right: 7,
          left: 0
        }
      },
      colors: [config.colors.success],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.4,
          gradientToColors: [config.colors.cardColor],
          opacityTo: 0.4,
          stops: [0, 100]
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 2,
        curve: 'smooth'
      },
      series: [
        {
          data: [180, 175, 275, 140, 205, 190, 295]
        }
      ],
      xaxis: {
        show: false,
        lines: {
          show: false
        },
        labels: {
          show: false
        },
        stroke: {
          width: 0
        },
        axisBorder: {
          show: false
        }
      },
      yaxis: {
        stroke: {
          width: 0
        },
        show: false
      }
    };
  if (typeof orderAreaChartEl !== undefined && orderAreaChartEl !== null) {
    const orderAreaChart = new ApexCharts(orderAreaChartEl, orderAreaChartConfig);
    orderAreaChart.render();
  }

  // ... (kode JS Anda yang lain) ...

// --- Total Biaya Listrik (MODIFIED) ---
// --------------------------------------------------------------------
const totalBiayaListrikEl = document.querySelector('#totalBiayaListrik');

// Pastikan elemennya ada sebelum lanjut
if (typeof totalBiayaListrikEl !== 'undefined' && totalBiayaListrikEl !== null) {

  // 1. Ambil data dari data-attributes HTML
  const labels = JSON.parse(totalBiayaListrikEl.dataset.labels);
  const standbySeries = JSON.parse(totalBiayaListrikEl.dataset.standbySeries);
  const pompaSeries = JSON.parse(totalBiayaListrikEl.dataset.pompaSeries);

  // 2. Opsi Chart (menggunakan data baru)
  const totalBiayaListrikOptions = {
    series: [
      {
        name: 'Biaya Pompa (Variabel)', // Ganti nama series
        data: pompaSeries // Gunakan data dari controller
      },
      {
        name: 'Biaya Standby (Dasar)', // Ganti nama series
        data: standbySeries // Gunakan data dari controller
      }
    ],
    chart: {
      height: 300,
      stacked: true, // Ini sudah benar (stacked bar)
      type: 'bar',
      toolbar: { show: false }
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '30%',
        borderRadius: 8,
        startingShape: 'rounded',
        endingShape: 'rounded',
        borderRadiusApplication: 'around'
      }
    },
    colors: [config.colors.primary, config.colors.info], // Warna untuk Pompa (primary) dan Standby (info)
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 6,
      lineCap: 'round',
      colors: [cardColor]
    },
    legend: {
      show: true,
      horizontalAlign: 'left',
      position: 'top',
      markers: {
        size: 4,
        radius: 12,
        shape: 'circle',
        strokeWidth: 0
      },
      fontSize: '13px',
      fontFamily: fontFamily,
      fontWeight: 400,
      labels: {
        colors: legendColor,
        useSeriesColors: false
      },
      itemMargin: {
        horizontal: 10
      }
    },
    grid: {
      strokeDashArray: 7,
      borderColor: borderColor,
      padding: {
        top: 0,
        bottom: -8,
        left: 20,
        right: 20
      }
    },
    fill: {
      opacity: [1, 1]
    },
    xaxis: {
      categories: labels, // <-- GANTI: Gunakan data label 6 bulan
      labels: {
        style: {
          fontSize: '13px',
          fontFamily: fontFamily,
          colors: labelColor
        }
      },
      axisTicks: {
        show: false
      },
      axisBorder: {
        show: false
      }
    },
    yaxis: {
      labels: {
        style: {
          fontSize: '13px',
          fontFamily: fontFamily,
          colors: labelColor
        },
        // TAMBAHAN: Format Sumbu Y ke Rupiah
        formatter: function (value) {
          return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        }
      }
    },
    // TAMBAHAN: Format Tooltip ke Rupiah
    tooltip: {
        y: {
            formatter: function (value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
        }
    },
    responsive: [
      // ... (blok responsive Anda sudah benar, tidak perlu diubah) ...
      {
        breakpoint: 1700,
        options: {
          plotOptions: {
            bar: {
              borderRadius: 10,
              columnWidth: '35%'
            }
          }
        }
      },
      // ... (dst) ...
    ],
    states: {
      hover: {
        filter: {
          type: 'none'
        }
      },
      active: {
        filter: {
          type: 'none'
        }
      }
    }
  };

  // 3. Render Chart
  const totalBiayaListrik = new ApexCharts(totalBiayaListrikEl, totalBiayaListrikOptions);
  totalBiayaListrik.render();
}

  // dashboards-analytics.js

  // Growth Chart - Radial Bar Chart
  // --------------------------------------------------------------------
  const growthChartEl = document.querySelector('#growthChart');

  if (typeof growthChartEl !== 'undefined' && growthChartEl !== null) {
    // 1. Ambil data dari data-attributes
    const avgGrowthTanaman = parseInt(growthChartEl.dataset.tanamanGrowth) || 0; // <--- PASTIKAN INI BENAR
    const avgGrowthIkan = parseInt(growthChartEl.dataset.ikanGrowth) || 0;

    // 2. Ambil elemen tombol
    // INI ADALAH PERBAIKAN LOGIKA SELEKTOR
    const mainGrowthButton = document.querySelector('#growthBtnTanaman'); // Tombol utama
    const dropdownGrowthButton = document.querySelector('#growthBtnIkan'); // Tombol di dropdown
    // 2. Ambil elemen tombol dan label
    const growthChartLabel = document.querySelector('#growthChartLabel');

    // 3. Opsi Awal Chart (untuk Tanaman)
    const growthChartOptions = {
      series: [avgGrowthTanaman], // Mulai dengan data Tanaman
      labels: ['Siap Panen'],
      chart: {
        height: 200,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          size: 150,
          offsetY: 10,
          startAngle: -150,
          endAngle: 150,
          hollow: {
            size: '55%'
          },
          track: {
            background: cardColor,
            strokeWidth: '100%'
          },
          dataLabels: {
            name: {
              offsetY: 15,
              color: legendColor,
              fontSize: '15px',
              fontWeight: '500',
              fontFamily: fontFamily
            },
            value: {
              offsetY: -25,
              color: headingColor,
              fontSize: '22px',
              fontWeight: '500',
              fontFamily: fontFamily
            }
          }
        }
      },
      colors: [config.colors.primary], // Mulai dengan warna Tanaman
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          shadeIntensity: 0.5,
          gradientToColors: [config.colors.primary],
          inverseColors: true,
          opacityFrom: 1,
          opacityTo: 0.6,
          stops: [30, 70, 100]
        }
      },
      stroke: {
        dashArray: 5
      },
      grid: {
        padding: {
          top: -35,
          bottom: -10
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        },
        active: {
          filter: {
            type: 'none'
          }
        }
      }
    };

    // 4. Render Chart Awal
    const growthChart = new ApexCharts(growthChartEl, growthChartOptions);
    growthChart.render();

    // 5. Ambil Elemen Tombol (Logika Baru)
    const growthBtnTanaman = document.querySelector('#growthBtnTanaman');
    const growthBtnIkan = document.querySelector('#growthBtnIkan');

    // 6. Event Listeners (Logika Baru)

    // Listener untuk Tombol Tanaman
    if (growthBtnTanaman) {
      growthBtnTanaman.addEventListener('click', function () {
        // 1. Update Chart
        growthChart.updateOptions({
          series: [avgGrowthTanaman],
          colors: [config.colors.primary],
          fill: { gradient: { gradientToColors: [config.colors.primary] } }
        });
        // 2. Update Label Teks
        if (growthChartLabel) growthChartLabel.innerHTML = 'Rata-rata Pertumbuhan Tanaman';

        // 3. Update Tampilan Tombol (CSS)
        growthBtnTanaman.classList.add('active');
        growthBtnIkan.classList.remove('active');
      });
    }

    // Listener untuk Tombol Ikan
    if (growthBtnIkan) {
      growthBtnIkan.addEventListener('click', function () {
        // 1. Update Chart
        growthChart.updateOptions({
          series: [avgGrowthIkan],
          colors: [config.colors.info], // Ganti warna untuk Ikan
          fill: { gradient: { gradientToColors: [config.colors.info] } }
        });
        // 2. Update Label Teks
        if (growthChartLabel) growthChartLabel.innerHTML = 'Rata-rata Pertumbuhan Ikan';

        // 3. Update Tampilan Tombol (CSS)
        growthBtnIkan.classList.add('active');
        growthBtnTanaman.classList.remove('active');
      });
    }
  } // <-- Akhir dari blok "if (growthChartEl)"

  // Revenue Bar Chart
  // --------------------------------------------------------------------
  const revenueBarChartEl = document.querySelector('#revenueChart'),
    revenueBarChartConfig = {
      chart: {
        height: 95,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          barHeight: '80%',
          columnWidth: '75%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4,
          distributed: true
        }
      },
      grid: {
        show: false,
        padding: {
          top: -20,
          bottom: -12,
          left: -10,
          right: 0
        }
      },
      colors: [
        config.colors.primary,
        config.colors.primary,
        config.colors.primary,
        config.colors.primary,
        config.colors.primary,
        config.colors.primary,
        config.colors.primary
      ],
      dataLabels: {
        enabled: false
      },
      series: [
        {
          data: [40, 95, 60, 45, 90, 50, 75]
        }
      ],
      legend: {
        show: false
      },
      xaxis: {
        categories: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          show: false
        }
      }
    };
  if (typeof revenueBarChartEl !== undefined && revenueBarChartEl !== null) {
    const revenueBarChart = new ApexCharts(revenueBarChartEl, revenueBarChartConfig);
    revenueBarChart.render();
  }

  // Profit Report Line Chart
  // --------------------------------------------------------------------
  const profileReportChartEl = document.querySelector('#profileReportChart'),
    profileReportChartConfig = {
      chart: {
        height: 75,
        width: 240,
        type: 'line',
        toolbar: {
          show: false
        },
        dropShadow: {
          enabled: true,
          top: 10,
          left: 5,
          blur: 3,
          color: config.colors.warning,
          opacity: 0.15
        },
        sparkline: {
          enabled: true
        }
      },
      grid: {
        show: false,
        padding: {
          right: 8
        }
      },
      colors: [config.colors.warning],
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 5,
        curve: 'smooth'
      },
      series: [
        {
          data: [110, 270, 145, 245, 205, 285]
        }
      ],
      xaxis: {
        show: false,
        lines: {
          show: false
        },
        labels: {
          show: false
        },
        axisBorder: {
          show: false
        }
      },
      yaxis: {
        show: false
      },
      responsive: [
        {
          breakpoint: 1700,
          options: {
            chart: {
              width: 200
            }
          }
        },
        {
          breakpoint: 1579,
          options: {
            chart: {
              width: 180
            }
          }
        },
        {
          breakpoint: 1500,
          options: {
            chart: {
              width: 160
            }
          }
        },
        {
          breakpoint: 1450,
          options: {
            chart: {
              width: 140
            }
          }
        },
        {
          breakpoint: 1400,
          options: {
            chart: {
              width: 240
            }
          }
        }
      ]
    };
  if (typeof profileReportChartEl !== undefined && profileReportChartEl !== null) {
    const profileReportChart = new ApexCharts(profileReportChartEl, profileReportChartConfig);
    profileReportChart.render();
  }

  // Order Statistics Chart
  // --------------------------------------------------------------------
  const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
    orderChartConfig = {
      chart: {
        height: 165,
        width: 136,
        type: 'donut',
        offsetX: 15
      },
      labels: ['Electronic', 'Sports', 'Decor', 'Fashion'],
      series: [50, 85, 25, 40],
      colors: [config.colors.success, config.colors.primary, config.colors.secondary, config.colors.info],
      stroke: {
        width: 5,
        colors: [cardColor]
      },
      dataLabels: {
        enabled: false,
        formatter: function (val, opt) {
          return parseInt(val) + '%';
        }
      },
      legend: {
        show: false
      },
      grid: {
        padding: {
          top: 0,
          bottom: 0,
          right: 15
        }
      },
      states: {
        hover: {
          filter: { type: 'none' }
        },
        active: {
          filter: { type: 'none' }
        }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '75%',
            labels: {
              show: true,
              value: {
                fontSize: '1.125rem',
                fontFamily: fontFamily,
                fontWeight: 500,
                color: headingColor,
                offsetY: -17,
                formatter: function (val) {
                  return parseInt(val) + '%';
                }
              },
              name: {
                offsetY: 17,
                fontFamily: fontFamily
              },
              total: {
                show: true,
                fontSize: '13px',
                color: legendColor,
                label: 'Weekly',
                formatter: function (w) {
                  return '38%';
                }
              }
            }
          }
        }
      }
    };
  if (typeof chartOrderStatistics !== undefined && chartOrderStatistics !== null) {
    const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
    statisticsChart.render();
  }

  const incomeChartEl = document.querySelector('#incomeChart'),
    incomeChartConfig = {
      series: [
        {
          data: [21, 30, 22, 42, 26, 35, 29]
        }
      ],
      chart: {
        height: 200,
        parentHeightOffset: 0,
        parentWidthOffset: 0,
        toolbar: {
          show: false
        },
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 3,
        curve: 'smooth'
      },
      legend: {
        show: false
      },
      markers: {
        size: 6,
        colors: 'transparent',
        strokeColors: 'transparent',
        strokeWidth: 4,
        discrete: [
          {
            fillColor: config.colors.white,
            seriesIndex: 0,
            dataPointIndex: 6,
            strokeColor: config.colors.primary,
            strokeWidth: 2,
            size: 6,
            radius: 8
          }
        ],
        offsetX: -1,
        hover: {
          size: 7
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.3,
          gradientToColors: [config.colors.cardColor],
          opacityTo: 0.3,
          stops: [0, 100]
        }
      },
      grid: {
        borderColor: borderColor,
        strokeDashArray: 8,
        padding: {
          top: -20,
          bottom: -8,
          left: 0,
          right: 8
        }
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: true,
          style: {
            fontSize: '13px',
            colors: labelColor
          }
        }
      },
      yaxis: {
        labels: {
          show: false
        },
        min: 10,
        max: 50,
        tickAmount: 4
      }
    };
  if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
    const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
    incomeChart.render();
  }

  // Expenses Mini Chart - Radial Chart
  // --------------------------------------------------------------------
  const weeklyExpensesEl = document.querySelector('#expensesOfWeek'),
    weeklyExpensesConfig = {
      series: [65],
      chart: {
        width: 60,
        height: 60,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          startAngle: 0,
          endAngle: 360,
          strokeWidth: '8',
          hollow: {
            margin: 2,
            size: '40%'
          },
          track: {
            background: borderColor
          },
          dataLabels: {
            show: true,
            name: {
              show: false
            },
            value: {
              formatter: function (val) {
                return '$' + parseInt(val);
              },
              offsetY: 5,
              color: legendColor,
              fontSize: '12px',
              fontFamily: fontFamily,
              show: true
            }
          }
        }
      },
      fill: {
        type: 'solid',
        colors: config.colors.primary
      },
      stroke: {
        lineCap: 'round'
      },
      grid: {
        padding: {
          top: -10,
          bottom: -15,
          left: -10,
          right: -10
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        },
        active: {
          filter: {
            type: 'none'
          }
        }
      }
    };
  if (typeof weeklyExpensesEl !== undefined && weeklyExpensesEl !== null) {
    const weeklyExpenses = new ApexCharts(weeklyExpensesEl, weeklyExpensesConfig);
    weeklyExpenses.render();
  }
});
